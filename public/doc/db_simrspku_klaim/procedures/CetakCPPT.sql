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

-- membuang struktur untuk procedure medicalrecord.CetakCPPT
DELIMITER //
CREATE PROCEDURE `CetakCPPT`(
	IN `PNOPEN` CHAR(10),
	IN `PKUNJUNGAN` VARCHAR(19)
)
BEGIN
  SET @sqlText = CONCAT(
    'SELECT 
        CONCAT(DATE_FORMAT(cp.TANGGAL, ''%d-%m-%Y''), '' \r'', TIME(cp.TANGGAL)) AS TANGGAL,        
        IF((SELECT r.CONFIG->>''$.dietisen'' 
            FROM master.referensi r 
            WHERE r.JENIS = 32 AND r.ID = cp.JENIS) = ''true'',
            CONCAT(
                ''<b>A/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.SUBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>D/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.OBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>I/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.ASSESMENT), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>ME/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.PLANNING), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>''
            ),
            IF(cp.STATUS_SBAR = 1,
            CONCAT(
                ''<b>S/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.SUBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>B/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.OBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>A/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.ASSESMENT), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>R/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.PLANNING), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'',
                ''<b>Dokter/ :</b>: '', IFNULL(master.getNamaLengkapPegawai(dc.NIP), '''')
            ),
            IF(cp.STATUS_TBAK = 1,
            CONCAT(
                ''<b>Tulis/ :</b> '', REPLACE(REPLACE(master.remove_html_tags(cp.TULIS), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>Baca/ :</b> '', IF(cp.BACA = 0, ''Belum Baca'', ''Sudah Baca''), '' \r'',
                ''<b>Konfirmasi/ :</b> '', IF(cp.KONFIRMASI = 0, ''Belum Konfirmasi'', ''Sudah Konfirmasi''), '' \r'',
                ''<b>Dokter/ :</b> '', IFNULL(master.getNamaLengkapPegawai(dc.NIP), '''')
            ),
            CONCAT(
                ''<b>S/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.SUBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>O/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.OBYEKTIF), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>A/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.ASSESMENT), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>'', '' \r'',
                ''<b>P/ :</b> '', REPLACE(REPLACE(master.getReplaceFont(cp.PLANNING), ''<p'', ''<br><p''), ''<div style="">'', ''<br/>'') , '' <br/><br/>''
            )))) AS CATATAN,
         
        master.getReplaceFont(cp.INSTRUKSI) INSTRUKSI,
        IF(ref.REF_ID = ''4'', master.getNamaLengkapPegawai(d.NIP), '''') AS DOKTER,
        IF(ref.REF_ID = ''6'', master.getNamaLengkapPegawai(pr.NIP), 
        IF(ref.REF_ID NOT IN (''6'', ''4''), master.getNamaLengkapPegawai(p.NIP), '''')) AS PERAWAT, 
        ref.DESKRIPSI AS JNSPPA,
        CONCAT(
            IF(ref.REF_ID = ''4'', master.getNamaLengkapPegawai(d.NIP), 
            IF(ref.REF_ID = ''6'', master.getNamaLengkapPegawai(pr.NIP), 
            IF(ref.REF_ID NOT IN (''6'', ''4''), master.getNamaLengkapPegawai(p.NIP), ''''))), 
            '' \r'', 
            IF(cp.STATUS_SBAR = 1, ''( SBAR )'', 
            IF(cp.STATUS_TBAK = 1, ''( TBAK )'', ''''))
        ) AS PPA,
        CONCAT(DATE_FORMAT(vcp.TANGGAL, ''%d-%m-%Y''), '' \r'', TIME(vcp.TANGGAL)) AS TGLVERIFIKASI,
        master.getNamaLengkapPegawai(vr.NIP) AS VERIFIKATOR,
        CONCAT(master.getNamaLengkapPegawai(vr.NIP), '' \r'', DATE_FORMAT(vcp.TANGGAL, ''%d-%m-%Y''), '' \r'', TIME(vcp.TANGGAL)) AS VERIFIKASI,
        IF(cp.STATUS_SBAR = 1, ''SBAR'', IF(cp.STATUS_TBAK = 1, ''TBAK'', '''')) AS TBAK_SBAR
    FROM medicalrecord.cppt cp 
    LEFT JOIN master.referensi ref ON cp.JENIS = ref.ID AND ref.JENIS = 32 
    LEFT JOIN master.pegawai p ON cp.TENAGA_MEDIS = p.ID 
    LEFT JOIN master.dokter d ON cp.TENAGA_MEDIS = d.ID 
    LEFT JOIN master.dokter dc ON cp.DOKTER_TBAK_OR_SBAR = dc.ID 
    LEFT JOIN master.perawat pr ON cp.TENAGA_MEDIS = pr.ID 
    LEFT JOIN medicalrecord.verifikasi_cppt vcp ON cp.VERIFIKASI = vcp.ID 
    LEFT JOIN aplikasi.pengguna vr ON vcp.OLEH = vr.ID 
    LEFT JOIN pendaftaran.kunjungan pk ON cp.KUNJUNGAN = pk.NOMOR 
    WHERE cp.KUNJUNGAN = pk.NOMOR AND cp.STATUS != 0 AND pk.NOPEN = ''', PNOPEN, '''
    ', IF(PKUNJUNGAN = 0 OR PKUNJUNGAN = '''', '', CONCAT(' AND cp.KUNJUNGAN = ''', PKUNJUNGAN, '''')), ' 
    ORDER BY cp.TANGGAL'
  );
--  AND pk.STATUS != 0 (menghilangkan validasi apabila KUNJUNGAN sudah dibatalkan)
  PREPARE stmt FROM @sqlText;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END//
DELIMITER ;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
