<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

function RpCreateFixtures($db)
{
    // Nama qualified sama dengan query produksi; TEMPORARY TABLE menutupi tabel asli
    // hanya untuk koneksi ini. Tidak ada INSERT/UPDATE/DELETE ke tabel permanen.
    $definitions = array(
        'jbsakad.siswa' => 'nis VARCHAR(20) PRIMARY KEY, nama VARCHAR(100), aktif INT, alumni INT, idkelas INT',
        'jbsakad.kelas' => 'replid INT PRIMARY KEY, kelas VARCHAR(50), idtingkat INT',
        'jbsakad.tingkat' => 'replid INT PRIMARY KEY, tingkat VARCHAR(50), departemen VARCHAR(50)',
        'jbsakad.departemen' => 'departemen VARCHAR(50), urutan INT, aktif INT',
        'jbsakad.riwayatkelassiswa' => 'nis VARCHAR(20), idkelas INT',
        'jbsfina.besarjtt' => 'replid INT PRIMARY KEY, nis VARCHAR(20), idpenerimaan INT, besar DECIMAL(15,0), lunas INT, keterangan VARCHAR(255), info2 VARCHAR(255)',
        'jbsfina.penerimaanjtt' => 'replid INT PRIMARY KEY, idbesarjtt INT, idjurnal INT, jumlah DECIMAL(15,0), info1 VARCHAR(255), tanggal DATE, keterangan VARCHAR(255), petugas VARCHAR(100)',
        'jbsfina.penerimaaniuran' => 'replid INT PRIMARY KEY, nis VARCHAR(20), idpenerimaan INT, idjurnal INT, jumlah DECIMAL(15,0), tanggal DATE, keterangan VARCHAR(255), petugas VARCHAR(100)',
        'jbsfina.datapenerimaan' => 'replid INT PRIMARY KEY, nama VARCHAR(100), departemen VARCHAR(50)',
        'jbsfina.tahunbuku' => 'replid INT PRIMARY KEY, tahunbuku VARCHAR(100), departemen VARCHAR(50), tanggalmulai DATE',
        'jbsfina.jurnal' => 'replid INT PRIMARY KEY, idtahunbuku INT, nokas VARCHAR(100)',
        'jbsumum.identitas' => 'replid INT PRIMARY KEY, nama VARCHAR(250), alamat1 VARCHAR(255), alamat2 VARCHAR(255), telp1 VARCHAR(20), email VARCHAR(100), departemen VARCHAR(50)'
    );
    foreach ($definitions as $table => $definition)
        $db->query('CREATE TEMPORARY TABLE ' . $table . ' (' . $definition . ') CHARACTER SET utf8mb4');
    $queries = array(
        "INSERT INTO jbsakad.departemen VALUES ('RA',1,0),('MI',2,1),('MTs',3,1)",
        "INSERT INTO jbsakad.tingkat VALUES (1,'A','RA'),(2,'I','MI'),(3,'VII','MTs')",
        "INSERT INTO jbsakad.kelas VALUES (1,'RA A',1),(2,'MI A',2),(3,'MTs A',3)",
        "INSERT INTO jbsakad.siswa VALUES ('001','Siswa Contoh',1,0,3),('002','Alumni Contoh',0,1,2),('003','Ann %_',0,0,3),('004','Data Orphan',1,0,3)",
        "INSERT INTO jbsakad.riwayatkelassiswa VALUES ('001',1)",
        "INSERT INTO jbsfina.tahunbuku VALUES (1,'2024','RA','2024-07-01'),(2,'2025','MI','2025-07-01'),(3,'2026','MI','2026-07-01')",
        "INSERT INTO jbsfina.datapenerimaan VALUES (1,'SPP RA','RA'),(2,'SPP MI','MI'),(3,'Sumbangan MI','MI')",
        "INSERT INTO jbsfina.jurnal VALUES (1,1,'RA-01'),(2,2,'MI-01'),(3,3,'MI-02')",
        "INSERT INTO jbsfina.besarjtt VALUES (1,'001',1,100,1,'','1'),(2,'001',2,200,1,'','2'),(3,'001',2,150,0,'Belum dibayar','3'),(4,'002',2,100,1,'','2'),(5,'004',2,50,1,'','2')",
        "INSERT INTO jbsfina.penerimaanjtt VALUES (1,1,1,40,'0','2024-08-01','','Petugas'),(2,1,2,50,'10','2025-08-01','<script>fixture</script>','Petugas'),(3,2,2,200,'0','2025-08-01','','Petugas'),(4,4,2,100,'0','2025-08-01','','Petugas'),(5,5,999,50,'0','2025-08-01','','Petugas')",
        "INSERT INTO jbsfina.penerimaaniuran VALUES (1,'001',3,2,25,'2025-09-01','','Petugas'),(2,'001',3,3,50,'2026-09-01','','Petugas')",
        "INSERT INTO jbsumum.identitas VALUES (1,'Sekolah Umum','','','','',''),(2,'Sekolah MI','','','','','MI')"
    );
    foreach ($queries as $query) $db->query($query);
}
