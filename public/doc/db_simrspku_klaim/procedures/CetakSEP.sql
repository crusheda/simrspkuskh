-- --------------------------------------------------------
-- Host:                         192.168.1.4
-- Versi server:                 8.0.41 - MySQL Community Server - GPL
-- OS Server:                    Linux
-- HeidiSQL Versi:               12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- membuang struktur untuk procedure bpjs.CetakSEPCustom
DELIMITER //
CREATE PROCEDURE `CetakSEP`(
	IN `PSEP` VARCHAR(20)
)
SELECT inst.NAMA NAMAINSTANSI,k.noSEP NOMORSEP, DATE_FORMAT(k.tglSEP,'%d-%m-%Y') TGLSEP
		 , IF(k.klsRawatNaik=0,klsr.deskripsi,klsn.deskripsi) klsRawat
		 , p.noKartu NOMORKARTU, pd.NORM NORM
		 , IF(k.poliTujuan = 'HDL' OR k.poliTujuan LIKE '%HEMO%','HEMODIALISA',IF(k.poliTujuan IS NULL OR k.poliTujuan='',pls.nama,pl.nama)) poliTujuan
		 , mr.DESKRIPSI UNITPELAYANAN
		 , IF(k.jmlCetak > 0,CONCAT('*Cetakan Ke ',k.jmlCetak, ' ',DATE_FORMAT(k.tglSEP,'%d-%m-%Y %H:%i:%s')),'') CETAKAN , k.catatan CATATAN
		 , p.nmJenisPeserta PESERTA, p.nama NAMALENGKAP, CONCAT(DATE_FORMAT(p.tglLahir,'%d-%m-%Y'), '  Kelamin : ',IF(p.sex='L','Laki-laki','Perempuan')) TGL_LAHIR
		 , IF(k.jenisPelayanan=1,'Rawat Inap','Rawat Jalan') JENISRAWAT
		 , IF((k.tujuanKunj=0 AND k.assesmentPel IN ('1','2','3','4')),'Rujukan Internal',tjk.deskripsi) TJKUNJ
		 , proc.deskripsi PROC, kdp.deskripsi PENUNJANG, assp.deskripsi ASPEL
		 , IF(p.sex='L','Laki-laki','Perempuan') JENISKELAMIN, p.nmKelas KELAS
		 , CONCAT(r.CODE,' (',r.STR,')') DIAGNOSA, rp.NAMA RUJUKAN
		 , p.prolanisPRB PRB, penj.DESKRIPSI PENJAMIN
		 , IF(k.cob=0,'-','Ya') COB, hp.NOMOR NOTELP
		 , IF(k.katarak=1,'* PASIEN OPERASI KATARAK','') KATARAK
		 , IF(dr.kode=drl.kode,dr.nama,drl.nama) DOKTER
		 , IF(k.tujuanKunj=0 AND k.assesmentPel IN ('1','2','3','4'),@namarujuk:=rjm.poliRujukan->>'$.nama','') POLIPERUJUK
 	    , IF(k.noRujukan LIKE '%V%',pp.NOMOR,IFNULL(k.noRujukan,'')) NORJK
 		 , IF(k.noRujukan LIKE '%V%',DATE_FORMAT(p.TANGGAL,'%d-%m-%Y'),IFNULL(DATE_FORMAT(rjm.tglKunjungan, '%d-%m-%Y'),'')) TGLRJK
		 , IF(k.noRujukan LIKE '%V%','Digunakan 1 kali kunjungan Pasca Rawat Inap',DATE_FORMAT(DATE_ADD(IFNULL(k.tglRujukan,srp.TANGGAL), INTERVAL 89 DAY), '%d-%m-%Y')) MASABERLAKU
		 , @koderujuk:=rjm.poliRujukan->>'$.kode' kode
		 , @namarujuk:=rjm.poliRujukan->>'$.nama' nama
     	 , IF(mr.JENIS_KUNJUNGAN!=3,@namarujuk,smf.DESKRIPSI) TUJUANRUJUK
     	 , IFNULL(CONCAT(rr.POS_ANTRIAN,'-',rr.`NO`),'TIDAK ADA ANTRIAN') ANTRIAN_ADMISI
	    , IFNULL(rr.ANTRIAN_POLI,'TIDAK ADA ANTRIAN') ANTRIAN_POLI
		 , IFNULL(rr.NOMOR_ANTRIAN,'TIDAK ADA ANTRIAN') ANTRIAN_DOKTER
		 , IFNULL(master.getAlamatPasien(p.NORM),mp.ALAMAT) ALAMATPASIEN
	FROM bpjs.kunjungan k
		  LEFT JOIN bpjs.peserta p ON k.noKartu = p.noKartu
		  LEFT JOIN master.ppk rp ON k.ppkRujukan = rp.BPJS
		  LEFT JOIN master.mrconso r ON k.diagAwal = r.CODE AND r.SAB IN ('ICD10_2020','ICD10_1998') AND r.TTY !='HT' AND r.TTY !='PS'
		  LEFT JOIN pendaftaran.penjamin pp ON k.noSEP=pp.NOMOR
		  LEFT JOIN pendaftaran.surat_rujukan_pasien srp ON srp.NOMOR=pp.NOMOR
		  LEFT JOIN pendaftaran.tujuan_pasien tp ON pp.NOPEN=tp.NOPEN
		  LEFT JOIN pendaftaran.pendaftaran pd ON pd.NOMOR=pp.NOPEN
		  LEFT JOIN `master`.pasien mp ON pd.NORM = mp.NORM
		  LEFT JOIN master.kontak_pasien hp ON pd.NORM=hp.NORM AND hp.JENIS=3
		  LEFT JOIN master.referensi smf ON tp.SMF=smf.ID AND smf.JENIS=26
		  LEFT JOIN master.ruangan mr ON tp.RUANGAN=mr.ID AND mr.JENIS=5
		  LEFT JOIN master.referensi penj ON k.penjamin=penj.ID AND penj.JENIS=80
		  LEFT JOIN bpjs.poli pl ON k.poliTujuan=pl.kode
		  LEFT JOIN bpjs.dpjp dr ON k.dpjpSKDP=dr.kode
		  LEFT JOIN bpjs.dpjp drl ON k.dpjpLayan=drl.kode
		  LEFT JOIN bpjs.referensi tjk ON k.tujuanKunj=tjk.kode AND tjk.jenis_referensi_id=7
		  LEFT JOIN bpjs.referensi proc ON k.flagProcedure =proc.kode AND proc.jenis_referensi_id=8
		  LEFT JOIN bpjs.referensi kdp ON k.kdPenunjang=kdp.kode AND kdp.jenis_referensi_id=9
		  LEFT JOIN bpjs.referensi assp ON k.assesmentPel=assp.kode AND assp.jenis_referensi_id=10
		  LEFT JOIN bpjs.referensi klsr ON k.klsRawat=klsr.kode AND klsr.jenis_referensi_id=3
		  LEFT JOIN bpjs.referensi klsn ON k.klsRawatNaik=klsn.kode AND klsn.jenis_referensi_id=11
		  LEFT JOIN penjamin_rs.dpjp drs ON drs.DPJP_PENJAMIN=k.dpjpSKDP AND drs.PENJAMIN=2 AND drs.`STATUS`=1
		  LEFT JOIN master.dokter md ON drs.DPJP_RS=md.ID AND md.`STATUS`=1
		  LEFT JOIN master.dokter_smf mds ON md.ID=mds.DOKTER AND mds.`STATUS`=1		  
		  LEFT JOIN master.penjamin_sub_spesialistik pss ON pss.SUB_SPESIALIS_RS=mds.SMF AND pss.PENJAMIN=2
		  LEFT JOIN bpjs.poli pls ON pss.SUB_SPESIALIS_PENJAMIN=pls.kode		  
        LEFT JOIN bpjs.poli bpnr ON srp.BAGIAN_DOKTER=bpnr.nama
        LEFT JOIN bpjs.rujukan_masuk rjm ON k.noRujukan=rjm.noKunjungan
		  LEFT JOIN regonline.reservasi rr ON k.noKartu=rr.NO_KARTU_BPJS AND DATE_FORMAT(k.tglSEP,'%y-%m-%d')=rr.TANGGALKUNJUNGAN AND rr.`STATUS` !=0      
		, (SELECT mp.NAMA 
			FROM aplikasi.instansi ai
				, master.ppk mp
			WHERE ai.PPK=mp.ID) inst
	WHERE k.cetak= 0 AND k.noSEP=PSEP
	GROUP BY k.noSEP//
DELIMITER ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
