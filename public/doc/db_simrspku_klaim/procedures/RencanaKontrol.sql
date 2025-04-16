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

-- membuang struktur untuk procedure bpjs.RencanaKontrolCustom2
DELIMITER //
CREATE PROCEDURE `RencanaKontrol`(
	IN `PKUNJUNGAN` CHAR(19)
)
BEGIN
	SET lc_time_names = 'id_ID';
	SELECT *
	FROM (
	
	/* QUERY SURAT RENCANA RANAP --------------------------------------------------------------------------- */
SELECT inst.PPK ID_PPK, UPPER(inst.NAMA) NAMA_INSTANSI, inst.KOTA, inst.ALAMAT, inst.BPJS KODEBPJS
		 , jk.NOMOR , pj.JENIS IDPENJAMIN
		 , CONCAT(IFNULL(pst.noKartu,'Tanpa Asuransi/Umum '), ' ( MR. ',p.NORM,' )') NOMORKARTU, pst.norm NORMBPJS, pst.noKartu NOBPJS
		 , pst.nmJenisPeserta PESERTA, CONCAT(pst.nama,'  (',IF(pst.sex='L','Laki-laki','Perempuan'),')') NAMALENGKAP1
		 , CONCAT(IF(ps.GELAR_DEPAN='' OR ps.GELAR_DEPAN IS NULL,'',CONCAT(ps.GELAR_DEPAN,'. ')),UPPER(ps.NAMA),IF(ps.GELAR_BELAKANG='' OR ps.GELAR_BELAKANG IS NULL,'',CONCAT(', ',ps.GELAR_BELAKANG))) NAMA_LENGKAP
	    , DATE_FORMAT(ps.TANGGAL_LAHIR,'%d %M %Y') TANGGAL_LAHIR
	    , LPAD(ps.NORM, 8, '0') NORM
	    , IFNULL(DATE_FORMAT(pri.TANGGAL,'%d %M %Y'),IFNULL(DATE_FORMAT(k.KELUAR,'%d %M %Y'),DATE_FORMAT(k.MASUK,'%d %M %Y'))) DIBUAT_TANGGAL
-- 	    , DATE_FORMAT(jk.DIBUAT_TANGGAL,'%d') DIBUAT_TANGGAL
-- 	    , jk.DIBUAT_TANGGAL
-- 	    , IFNULL(jk.DIBUAT_TANGGAL,pri.DIBUAT_TANGGAL) DIBUAT_TANGGAL
-- 	    , DATE_FORMAT(IFNULL(jk.DIBUAT_TANGGAL,pri.DIBUAT_TANGGAL),'%d %M %Y') DIBUAT_TANGGAL
-- 	    , CONCAT(DATE_FORMAT(IFNULL(jk.DIBUAT_TANGGAL,pri.DIBUAT_TANGGAL),'%d'),'/',DATE_FORMAT(IFNULL(jk.DIBUAT_TANGGAL,pri.DIBUAT_TANGGAL),'%M'),'/',DATE_FORMAT(IFNULL(jk.DIBUAT_TANGGAL,pri.DIBUAT_TANGGAL),'%Y')) DIBUAT_TANGGAL
	    , r.DESKRIPSI RUANGAN
	    , IFNULL(master.getNamaLengkapPegawai(drk.NIP),master.getNamaLengkapPegawai(drso.NIP)) DOKTER
	    , IFNULL(master.getNamaLengkapPegawai(usr.NIP),master.getNamaLengkapPegawai(ris.NIP)) USRP
	    , IFNULL(master.getNamaLengkapPegawai(pri_dr.NIP),master.getNamaLengkapPegawai(drso.NIP)) DOKTER_PRI
	    , IFNULL(pri_smf.DESKRIPSI,'') SMF_DOKTER_PRI
	    , IFNULL(drk.NIP,drso.NIP) NIP
	    , bpjs.getDPJP(drpj.DPJP_PENJAMIN) DRSEP
	    , bpjs.getDPJP(drtj.DPJP_PENJAMIN) DRKONTROL
	    , pbpjs.nama SPESIALISTIK
	    , IFNULL(IFNULL(smf.DESKRIPSI,smfso.DESKRIPSI),'') SMF
	    , d.DIAGNOSIS, jk.NOMOR_ANTRIAN, jk.NOMOR_BOOKING
--	    , IF(dg.ID IS NULL, CONCAT(dms.CODE,'- ',dms.STR), `master`.getICD10(dg.KODE)) DIAGMASUK
	    , IF(dg.ID IS NULL, CONCAT(dms.CODE,'- ',dms.STR), IF(dg.KODE != "",`master`.getICD10(dg.KODE),dg.DIAGNOSA)) DIAGMASUK
	    , CONCAT(DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y'), ' & ', jk.JAM) JADWAL_KONTROL1
		 , DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y') JKONTROL
		 , CONCAT(DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y'),' & Estimasi Jam Pelayanan ',
		    IFNULL((SELECT
		                CONCAT(
		                    IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '11:00',
		                        '12:00 - 15:00',
		                        IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '09:00',
		                            '10:00 - 12:00',
		                            IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '07:00',
		                                '08:00 - 10:00',
		                                '08:00 - 10:00'))))
		            FROM regonline.reservasi r
		            WHERE r.TANGGALKUNJUNGAN = jk.TANGGAL
		                AND r.ID = jk.NOMOR_BOOKING
		           
		            LIMIT 1),'Belum ada, Silahkan ambil antrian')) AS JADWAL_KONTROL
	    , CONCAT(DATE_FORMAT(pri.TANGGAL, '%d-%m-%Y')) TGLSO, CONCAT(pri.INDIKASI,'\r',pri.DESKRIPSI) KETSO, jk.DESKRIPSI KET
	    , pri.INDIKASI INDIKASI_SO
	    , pri.DESKRIPSI DESKRIPSI_SO
	    , IF(rkbpjs.noSurat IS NULL, DATE_FORMAT(rkbpjs1.tglRencanaKontrol, '%d %M %Y'), DATE_FORMAT(rkbpjs.tglRencanaKontrol, '%d %M %Y')) JADWALBPJS
	    , DATE_FORMAT(jk.DIBUAT_TANGGAL, '%m') BLN, DATE_FORMAT(jk.DIBUAT_TANGGAL, '%Y') THN
	    , rt.DESKRIPSI RENCANA_TERAPI, rk.JENIS_KUNJUNGAN
		 /*, IF(jk.RUANGAN IS NULL, 
			  IFNULL(pri.NOMOR_REFERENSI, 'Nomor Surat BPJS Wajib diterbitkan'),
			   IF(IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat) IS NULL,
			      IF(rk.JENIS_KUNJUNGAN = 3, 'Pasca Ranap wajib terbit nomor surat kontrol BPJS',
			         IF(bpn.kode = pss.SUB_SPESIALIS_PENJAMIN, 'Nomor Surat BPJS Wajib diterbitkan',
			            IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3, 'NOMOR SURAT BPJS TIDAK TERBIT,\nKunjungan 1 Kali Pasca Rawat Inap sudah digunakan, Silahkan buat surat kontrol dari Kunjungan Rawat jalan terakhir sebelum Rawat Inap (Jika Rujukan Masih Aktif), atau Pasien dikembalikan ke Faskes Awal untuk mengambil rujukan baru'
								, jk.NOMOR_REFERENSI
							
							  )
			         )
			      ),
			      IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat)
			   )
			) NOSBPJS */
		 , IF(jk.RUANGAN IS NULL, 
			  IFNULL(pri.NOMOR_REFERENSI, 'Nomor Surat BPJS Wajib diterbitkan'),
			   IF(IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat) IS NULL,
			      IF(rk.JENIS_KUNJUNGAN = 3, 'Pasca Ranap wajib terbit nomor surat kontrol BPJS',
			         IF(bpn.kode = pss.SUB_SPESIALIS_PENJAMIN, 'Nomor Surat BPJS Wajib diterbitkan',
			            IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3, 'NOMOR SURAT BPJS TIDAK TERBIT,\nKunjungan 1 Kali Pasca Rawat Inap sudah digunakan, Silahkan buat surat kontrol dari Kunjungan Rawat jalan terakhir sebelum Rawat Inap (Jika Rujukan Masih Aktif), atau Pasien dikembalikan ke Faskes Awal untuk mengambil rujukan baru'
								, jk.NOMOR_REFERENSI
							
							  )
			         )
			      ),
			      IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat)
			   )
			) NOSBPJS
		 /*, IF(LEFT(rkbpjs.noSurat,1)='K',CONCAT('Silakan Validasi Rujukan di Pendaftaran : ',rkbpjs.noSurat),rkbpjs.noSurat) TESTNOSBPJS */
	    , IF(jk.RUANGAN IS NULL, CONCAT(DATE_FORMAT(pri.DIBUAT_TANGGAL, '%Y'),pri.NOMOR), CONCAT(DATE_FORMAT(jk.DIBUAT_TANGGAL, '%Y'), jk.NOMOR)) NOSURAT
	    , IF(jk.RUANGAN IS NULL, 'SURAT RENCANA INAP' , 'SURAT RENCANA KONTROL') HEADERBPJS
	    , IF(jk.RUANGAN IS NULL, 1 , 2) JENISKONTROL
	    , IF(rk.JENIS_KUNJUNGAN=3,pj.NOMOR,IFNULL(bk.noRujukan,'')) NORJK
		 , IF(rk.JENIS_KUNJUNGAN=3,DATE_FORMAT(p.TANGGAL,'%d-%m-%Y'),IFNULL(DATE_FORMAT(bk.tglRujukan, '%d-%m-%Y'),'')) TGLRJK
		 , IFNULL(IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3,'',IF(rk.JENIS_KUNJUNGAN!=3,CONCAT('Tgl. ',DATE_FORMAT(DATE_ADD(IFNULL(bk.tglRujukan,MAX(srp.TANGGAL)), INTERVAL 89 DAY), '%d-%m-%Y')),'1 Kali pada kunjungan pertama Setelah Rawat Inap')),'') MASABERLAKU
		 #, IF(rk.JENIS_KUNJUNGAN!=3,DATE_FORMAT(DATE_ADD(IFNULL(bk.tglRujukan,MAX(srp.TANGGAL)), INTERVAL 89 DAY), '%d-%m-%Y'),'1 Kali pada kunjungan pertama Setelah Rawat Inap') MASABERLAKU
		 , IF(rk.JENIS_KUNJUNGAN!=3,srp.BAGIAN_DOKTER,smf.DESKRIPSI) TUJUANRUJUK, bpn.nama, bpn.kode
		 , jrp.DESKRIPSI JENIS_RUANG_PERAWATAN, jp.DESKRIPSI JENIS_PERAWATAN
		 , (SELECT CONCAT(IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '11:00',
					'12:00 - 15:00'
						, IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '09:00',
							'10:00 - 12:00'
								, IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '07:00',
									'08:00 - 10:00'
										, '08:00 - 10:00'
										)
									)
								)
							)  
			FROM regonline.reservasi r 
			WHERE r.TANGGALKUNJUNGAN=jk.TANGGAL AND r.NORM=p.NORM AND r.`STATUS`=1
			LIMIT 1) JAM_PELAYANAN
	    FROM pendaftaran.kunjungan k
	    LEFT JOIN master.dokter drso ON drso.ID=k.DPJP
	    LEFT JOIN master.dokter_smf ds ON ds.DOKTER=drso.ID
	    LEFT JOIN master.referensi smfso ON smfso.ID=ds.SMF AND smfso.JENIS=26
  		 LEFT JOIN medicalrecord.jadwal_kontrol jk ON k.NOMOR = jk.KUNJUNGAN AND jk.`STATUS` !=0
  		 LEFT JOIN aplikasi.pengguna usr ON jk.OLEH = usr.ID
  		 LEFT JOIN `master`.ruangan r ON jk.RUANGAN = r.ID
       LEFT JOIN penjamin_rs.dpjp drtj ON jk.DOKTER=drtj.DPJP_RS AND drtj.PENJAMIN=2
       LEFT JOIN master.dokter drk ON drk.ID=drtj.DPJP_RS
       LEFT JOIN master.referensi smf ON jk.TUJUAN=smf.ID AND smf.JENIS=26
       LEFT JOIN master.penjamin_sub_spesialistik pss ON pss.SUB_SPESIALIS_RS=smf.ID AND pss.PENJAMIN=2
       LEFT JOIN bpjs.poli pbpjs ON pss.SUB_SPESIALIS_PENJAMIN=pbpjs.kode      
  		 LEFT JOIN medicalrecord.diagnosis d ON d.KUNJUNGAN = k.NOMOR
  		 LEFT JOIN medicalrecord.rencana_terapi rt ON rt.KUNJUNGAN = k.NOMOR
  		 LEFT JOIN medicalrecord.perencanaan_rawat_inap pri ON k.NOMOR=pri.KUNJUNGAN AND pri.`STATUS` !=0
	    LEFT JOIN master.dokter pri_dr ON pri_dr.ID=pri.DOKTER
	    LEFT JOIN master.dokter_smf pri_ds ON pri_ds.DOKTER=pri_dr.ID
	    LEFT JOIN master.referensi pri_smf ON pri_smf.ID=pri_ds.SMF AND pri_smf.JENIS=26
  		 LEFT JOIN aplikasi.pengguna ris ON pri.OLEH = ris.ID
  		 LEFT JOIN master.referensi jrp ON pri.JENIS_RUANG_PERAWATAN=jrp.ID AND jrp.JENIS=242
  		 LEFT JOIN master.referensi jp ON pri.JENIS_PERAWATAN=jp.ID AND jp.JENIS=243  		 
  		 LEFT JOIN bpjs.rencana_kontrol rkbpjs ON jk.NOMOR_REFERENSI=rkbpjs.noSurat AND rkbpjs.`status` !=0
  		 LEFT JOIN bpjs.rencana_kontrol rkbpjs1 ON pri.NOMOR_REFERENSI=rkbpjs1.noSurat AND rkbpjs1.`status` !=0
  		 LEFT JOIN `master`.ruangan rk ON k.RUANGAN = rk.ID
       , pendaftaran.pendaftaran p
       LEFT JOIN master.kartu_asuransi_pasien kap ON p.NORM=kap.NORM AND kap.JENIS=2
       LEFT JOIN bpjs.peserta pst ON kap.NOMOR=pst.noKartu
       LEFT JOIN pendaftaran.penjamin pj ON p.NOMOR=pj.NOPEN
       LEFT JOIN bpjs.kunjungan bk ON pj.NOMOR=bk.noSEP
       LEFT JOIN pendaftaran.surat_rujukan_pasien srp ON  bk.noRujukan=srp.NOMOR AND  srp.NORM=p.NORM 
       LEFT JOIN bpjs.poli bpn ON srp.BAGIAN_DOKTER=bpn.nama
       LEFT JOIN master.diagnosa_masuk dm ON p.DIAGNOSA_MASUK=dm.ID
       LEFT JOIN master.mrconso dms ON dm.ICD = dms.CODE AND dms.SAB IN ('ICD10_2020','ICD10_1998') AND dms.TTY !='HT' AND dms.TTY !='PS'
       LEFT JOIN medicalrecord.diagnosa dg ON p.NOMOR=dg.NOPEN AND dg.UTAMA=1 AND dg.INA_GROUPER=0 AND dg.`STATUS`!=0
       , pendaftaran.tujuan_pasien tp
       LEFT JOIN master.dokter dr ON dr.ID=tp.DOKTER
       LEFT JOIN penjamin_rs.dpjp drpj ON dr.ID=drpj.DPJP_RS AND drpj.PENJAMIN=2
    
       , `master`.pasien ps
       , (SELECT mp.NAMA, ai.PPK, w.DESKRIPSI KOTA, mp.ALAMAT, mp.BPJS
					FROM aplikasi.instansi ai
						, master.ppk mp
						, master.wilayah w
					WHERE ai.PPK=mp.ID AND mp.WILAYAH=w.ID) inst
 WHERE k.NOMOR = PKUNJUNGAN
   AND p.NOMOR = k.NOPEN
   AND ps.NORM = p.NORM
   AND tp.NOPEN = p.NOMOR
	AND tp.`STATUS`= 2
	GROUP BY k.NOMOR
	UNION ALL
	
	/* QUERY SURAT KONTROL --------------------------------------------------------------------------- */
	SELECT inst.PPK ID_PPK, UPPER(inst.NAMA) NAMA_INSTANSI, inst.KOTA, inst.ALAMAT, inst.BPJS KODEBPJS
		 , jk.NOMOR , pj.JENIS IDPENJAMIN
		 , CONCAT(IFNULL(pst.noKartu,'Tanpa Asuransi/Umum '), '  ( MR. ',p.NORM,' )') NOMORKARTU, pst.norm NORMBPJS, pst.noKartu NOBPJS
		 , pst.nmJenisPeserta PESERTA, CONCAT(pst.nama,'  (',IF(pst.sex='L','Laki-laki','Perempuan'),')') NAMALENGKAP1
		 , CONCAT(IF(ps.GELAR_DEPAN='' OR ps.GELAR_DEPAN IS NULL,'',CONCAT(ps.GELAR_DEPAN,'. ')),UPPER(ps.NAMA),IF(ps.GELAR_BELAKANG='' OR ps.GELAR_BELAKANG IS NULL,'',CONCAT(', ',ps.GELAR_BELAKANG))) NAMA_LENGKAP
	    , DATE_FORMAT(ps.TANGGAL_LAHIR,'%d %M %Y') TANGGAL_LAHIR
	    , LPAD(ps.NORM, 8, '0') NORM
--	    , IFNULL(DATE_FORMAT(pri.DIBUAT_TANGGAL,'%d %M %Y'),DATE_FORMAT(jk.DIBUAT_TANGGAL,'%d %M %Y')) DIBUAT_TANGGAL
	    , IFNULL(DATE_FORMAT(pri.DIBUAT_TANGGAL,'%d %M %Y'),IFNULL(DATE_FORMAT(k.KELUAR,'%d %M %Y'),DATE_FORMAT(k.MASUK,'%d %M %Y'))) DIBUAT_TANGGAL
-- 	    , DATE_FORMAT(jk.DIBUAT_TANGGAL,'%d') DIBUAT_TANGGAL
-- 	    , jk.DIBUAT_TANGGAL
-- 	    , IFNULL(pri.DIBUAT_TANGGAL,jk.DIBUAT_TANGGAL) DIBUAT_TANGGAL
	    , r.DESKRIPSI RUANGAN
	    , IFNULL(master.getNamaLengkapPegawai(drso.NIP),master.getNamaLengkapPegawai(drk.NIP)) DOKTER
	    , IFNULL(master.getNamaLengkapPegawai(usr.NIP),master.getNamaLengkapPegawai(ris.NIP)) USRP
	    , IFNULL(master.getNamaLengkapPegawai(pri_dr.NIP),master.getNamaLengkapPegawai(drso.NIP)) DOKTER_PRI
	    , IFNULL(pri_smf.DESKRIPSI,'') SMF_DOKTER_PRI
	    , IFNULL(drso.NIP,drk.NIP) NIP
	    , bpjs.getDPJP(drpj.DPJP_PENJAMIN) DRSEP
	    , bpjs.getDPJP(drtj.DPJP_PENJAMIN) DRKONTROL
	    , pbpjs.nama SPESIALISTIK
	    , IFNULL(smfso.DESKRIPSI,smf.DESKRIPSI) SMF
	    , d.DIAGNOSIS, jk.NOMOR_ANTRIAN, jk.NOMOR_BOOKING	   
--	    , IF(dg.ID IS NULL, IF(dm.ICD = "",'-',CONCAT(dms.CODE,'- ',dms.STR)), `master`.getICD10(dg.KODE)) DIAGMASUK
	    , IF(dg.ID IS NULL, CONCAT(dms.CODE,'- ',dms.STR), IF(dg.KODE != "",`master`.getICD10(dg.KODE),dg.DIAGNOSA)) DIAGMASUK
	    , CONCAT(DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y'), ' & ', jk.JAM) JADWAL_KONTROL1
	    , DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y') JKONTROL
		 , CONCAT(DATE_FORMAT(jk.TANGGAL, '%d-%m-%Y'),' & Estimasi Jam Pelayanan ',
		    IFNULL((SELECT
		                CONCAT(
		                    IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '11:00',
		                        '12:00 - 15:00',
		                        IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '09:00',
		                            '10:00 - 12:00',
		                            IF(DATE_FORMAT(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN, ':00'), '%H:%i:%s'), '%H:00') > '07:00',
		                                '08:00 - 10:00',
		                                '08:00 - 10:00'))))
		            FROM regonline.reservasi r
		            WHERE r.TANGGALKUNJUNGAN = jk.TANGGAL
		                AND r.ID = jk.NOMOR_BOOKING
		           
		            LIMIT 1),'Belum ada, Silahkan ambil antrian')) AS JADWAL_KONTROL
	    , CONCAT(DATE_FORMAT(pri.TANGGAL, '%d-%m-%Y')) TGLSO, CONCAT(pri.INDIKASI,'\n',pri.DESKRIPSI) KETSO, jk.DESKRIPSI KET
	    , pri.INDIKASI INDIKASI_SO
	    , pri.DESKRIPSI DESKRIPSI_SO
	    , IF(rkbpjs.noSurat IS NULL, DATE_FORMAT(rkbpjs1.tglRencanaKontrol, '%d %M %Y'), DATE_FORMAT(rkbpjs.tglRencanaKontrol, '%d %M %Y')) JADWALBPJS
	    , DATE_FORMAT(jk.DIBUAT_TANGGAL, '%m') BLN, DATE_FORMAT(jk.DIBUAT_TANGGAL, '%Y') THN
	    , rt.DESKRIPSI RENCANA_TERAPI, rk.JENIS_KUNJUNGAN
		 , IF(pri.KUNJUNGAN IS NULL, 
		 	  IF(IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat) IS NULL,
			      IF(rk.JENIS_KUNJUNGAN = 3, 'Pasca Ranap wajib terbit nomor surat kontrol BPJS',
			         IF(bpn.kode = pss.SUB_SPESIALIS_PENJAMIN, 'Nomor Surat BPJS Wajib diterbitkan',
			            IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3, 'NOMOR SURAT BPJS TIDAK TERBIT,\nKunjungan 1 Kali Pasca Rawat Inap sudah digunakan, Silahkan buat surat kontrol dari Kunjungan Rawat jalan terakhir sebelum Rawat Inap (Jika Rujukan Masih Aktif), atau Pasien dikembalikan ke Faskes Awal untuk mengambil rujukan baru'
								, CONCAT('K', inst.BPJS, DATE_FORMAT(jk.DIBUAT_TANGGAL, '%Y'), jk.NOMOR))
			         )
			      ),
			      IF(rkbpjs.noSurat IS NULL, rkbpjs1.noSurat, rkbpjs.noSurat)
			   ),
			  IFNULL(pri.NOMOR_REFERENSI, 'Nomor Surat BPJS Wajib diterbitkan')
			   
			) NOSBPJS	 
	    , IF(pri.KUNJUNGAN IS NULL,  CONCAT(DATE_FORMAT(jk.DIBUAT_TANGGAL, '%Y'), jk.NOMOR),CONCAT(DATE_FORMAT(pri.DIBUAT_TANGGAL, '%Y'),pri.NOMOR)) NOSURAT
	    , IF(pri.KUNJUNGAN IS NULL, 'SURAT RENCANA KONTROL', 'SURAT RENCANA INAP' ) HEADERBPJS
	    , IF(pri.KUNJUNGAN IS NULL, 2 , 1) JENISKONTROL
	    , IF(rk.JENIS_KUNJUNGAN=3,pj.NOMOR,IFNULL(bk.noRujukan,'')) NORJK
		 , IF(rk.JENIS_KUNJUNGAN=3,DATE_FORMAT(p.TANGGAL,'%d-%m-%Y'),IFNULL(DATE_FORMAT(bk.tglRujukan, '%d-%m-%Y'),'')) TGLRJK
--		 , IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3,'',IF(rk.JENIS_KUNJUNGAN!=3,IF(bk.tglRujukan IS NULL,IF(srp.TANGGAL IS NULL,'',DATE_FORMAT(DATE_ADD(MAX(srp.TANGGAL), INTERVAL 89 DAY), '%d-%m-%Y')),DATE_FORMAT(DATE_ADD(bk.tglRujukan, INTERVAL 89 DAY), '%d-%m-%Y')),'1 Kali pada kunjungan pertama Setelah Rawat Inap')) MASABERLAKU
		 , IFNULL(IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3,'',IF(rk.JENIS_KUNJUNGAN!=3,CONCAT('Tgl. ',DATE_FORMAT(DATE_ADD(IFNULL(bk.tglRujukan,MAX(srp.TANGGAL)), INTERVAL 89 DAY), '%d-%m-%Y')),'1 Kali pada kunjungan pertama Setelah Rawat Inap')),'') MASABERLAKU
--		 , IF(`master`.getJenisKunjunganSebelumnya(p.NORM, p.TANGGAL)=3,'',IF(rk.JENIS_KUNJUNGAN!=3,CONCAT('Tgl. ',DATE_FORMAT(DATE_ADD(IFNULL(bk.tglRujukan,MAX(srp.TANGGAL)), INTERVAL 89 DAY), '%d-%m-%Y')),'1 Kali pada kunjungan pertama Setelah Rawat Inap')) MASABERLAKU
		 , IF(rk.JENIS_KUNJUNGAN!=3,srp.BAGIAN_DOKTER,smf.DESKRIPSI) TUJUANRUJUK, bpn.nama, bpn.kode
		 , jrp.DESKRIPSI JENIS_RUANG_PERAWATAN, jp.DESKRIPSI JENIS_PERAWATAN
		 , (SELECT CONCAT(IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '11:00',
					'12:00 - 15:00'
						, IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '09:00',
							'10:00 - 12:00'
								, IF(date_format(STR_TO_DATE(CONCAT(r.JAM_PELAYANAN,':00'),'%H:%i:%s'),'%H:00') > '07:00',
									'08:00 - 10:00'
										, '08:00 - 10:00'
										)
									)
								)
							)  
			FROM regonline.reservasi r
			WHERE r.TANGGALKUNJUNGAN=jk.TANGGAL AND r.NORM=p.NORM AND r.`STATUS`=1
			LIMIT 1) JAM_PELAYANAN
	    FROM pendaftaran.kunjungan k
	    LEFT JOIN master.dokter drso ON drso.ID=k.DPJP
	    LEFT JOIN master.dokter_smf ds ON ds.DOKTER=drso.ID
	    LEFT JOIN master.referensi smfso ON smfso.ID=ds.SMF AND smfso.JENIS=26
  		 LEFT JOIN medicalrecord.jadwal_kontrol jk ON k.NOMOR = jk.KUNJUNGAN AND jk.`STATUS` !=0
  		 LEFT JOIN aplikasi.pengguna usr ON jk.OLEH = usr.ID
  		 LEFT JOIN `master`.ruangan r ON jk.RUANGAN = r.ID
       LEFT JOIN penjamin_rs.dpjp drtj ON jk.DOKTER=drtj.DPJP_RS AND drtj.PENJAMIN=2
       LEFT JOIN master.dokter drk ON drk.ID=drtj.DPJP_RS
       LEFT JOIN master.referensi smf ON jk.TUJUAN=smf.ID AND smf.JENIS=26
       LEFT JOIN master.penjamin_sub_spesialistik pss ON pss.SUB_SPESIALIS_RS=smf.ID AND pss.PENJAMIN=2
       LEFT JOIN bpjs.poli pbpjs ON pss.SUB_SPESIALIS_PENJAMIN=pbpjs.kode      
  		 LEFT JOIN medicalrecord.diagnosis d ON d.KUNJUNGAN = k.NOMOR
  		 LEFT JOIN medicalrecord.rencana_terapi rt ON rt.KUNJUNGAN = k.NOMOR
  		 LEFT JOIN medicalrecord.perencanaan_rawat_inap pri ON k.NOMOR=pri.KUNJUNGAN AND pri.`STATUS` !=0
	    LEFT JOIN master.dokter pri_dr ON pri_dr.ID=pri.DOKTER
	    LEFT JOIN master.dokter_smf pri_ds ON pri_ds.DOKTER=pri_dr.ID
	    LEFT JOIN master.referensi pri_smf ON pri_smf.ID=pri_ds.SMF AND pri_smf.JENIS=26
  		 LEFT JOIN aplikasi.pengguna ris ON pri.OLEH = ris.ID
  		 LEFT JOIN master.referensi jrp ON pri.JENIS_RUANG_PERAWATAN=jrp.ID AND jrp.JENIS=242
  		 LEFT JOIN master.referensi jp ON pri.JENIS_PERAWATAN=jp.ID AND jp.JENIS=243  		 
  		 LEFT JOIN bpjs.rencana_kontrol rkbpjs ON jk.NOMOR_REFERENSI=rkbpjs.noSurat AND rkbpjs.`status` !=0
  		 LEFT JOIN bpjs.rencana_kontrol rkbpjs1 ON pri.NOMOR_REFERENSI=rkbpjs1.noSurat AND rkbpjs1.`status` !=0
  		 LEFT JOIN `master`.ruangan rk ON k.RUANGAN = rk.ID
       , pendaftaran.pendaftaran p
       LEFT JOIN master.kartu_asuransi_pasien kap ON p.NORM=kap.NORM AND kap.JENIS=2
       LEFT JOIN bpjs.peserta pst ON kap.NOMOR=pst.noKartu
       LEFT JOIN pendaftaran.penjamin pj ON p.NOMOR=pj.NOPEN
       LEFT JOIN bpjs.kunjungan bk ON pj.NOMOR=bk.noSEP
       LEFT JOIN pendaftaran.surat_rujukan_pasien srp ON bk.noRujukan=srp.NOMOR AND srp.NORM=p.NORM
       LEFT JOIN bpjs.poli bpn ON srp.BAGIAN_DOKTER=bpn.nama
       LEFT JOIN master.diagnosa_masuk dm ON p.DIAGNOSA_MASUK=dm.ID
       LEFT JOIN master.mrconso dms ON dm.ICD = dms.CODE AND dms.SAB IN ('ICD10_2020','ICD10_1998') AND dms.TTY !='HT' AND dms.TTY !='PS'
       LEFT JOIN medicalrecord.diagnosa dg ON p.NOMOR=dg.NOPEN AND dg.UTAMA=1 AND dg.INA_GROUPER=0 AND dg.`STATUS`!=0
       , pendaftaran.tujuan_pasien tp
       LEFT JOIN master.dokter dr ON dr.ID=tp.DOKTER
       LEFT JOIN penjamin_rs.dpjp drpj ON dr.ID=drpj.DPJP_RS AND drpj.PENJAMIN=2
    
       , `master`.pasien ps
       , (SELECT mp.NAMA, ai.PPK, w.DESKRIPSI KOTA, mp.ALAMAT, mp.BPJS
					FROM aplikasi.instansi ai
						, master.ppk mp
						, master.wilayah w
					WHERE ai.PPK=mp.ID AND mp.WILAYAH=w.ID) inst
 WHERE k.NOMOR = PKUNJUNGAN
   AND p.NOMOR = k.NOPEN
   AND ps.NORM = p.NORM
   AND tp.NOPEN = p.NOMOR
	AND tp.`STATUS`= 2
	GROUP BY k.NOMOR
	) ab
GROUP BY JENISKONTROL
	;
 END//
DELIMITER ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
