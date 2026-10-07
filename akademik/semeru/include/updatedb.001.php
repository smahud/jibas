<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 36.0 (Oct 07, 2026)
 * @notes:
 *
 * Copyright (C) 2024 JIBAS (http://www.jibas.net)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 **[N]**/ ?>
<?php
if (!IsColumnExist($db, "jbsakad", "pelajaran", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`pelajaran` 
              ADD COLUMN `urutan` INT UNSIGNED NOT NULL AFTER `aktif`";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "pelajaran", "IX_pelajaran"))
{
    $sql = "ALTER TABLE `jbsakad`.`pelajaran`
              ADD INDEX `IX_pelajaran` (`sifat`, `aktif`, `urutan`)";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "rpp", "deskripsi_data"))
{
    $sql = "ALTER TABLE `jbsakad`.`rpp`
              ADD COLUMN `deskripsi_data` text DEFAULT NULL AFTER deskripsi";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE jbsakad.rpp ADD FULLTEXT(rpp, deskripsi_data)";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE jbsakad.rpp
             DROP INDEX `IX_rpp`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE jbsakad.rpp
              ADD INDEX `IX_rpp` (`koderpp`,`aktif`,`urutan`)";
    ExecIgnore($db, $sql);
}

$sql = "SELECT COUNT(replid)
          FROM jbsakad.rpp
         WHERE deskripsi_data IS NULL";
$nData = $db->ExecuteScalar($sql, 0);
if ($nData > 0)
{
    $sql = "SELECT replid, deskripsi
              FROM jbsakad.rpp
             WHERE deskripsi_data IS NULL";
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];

        $deskripsi = $row[1];
        $deskripsi_data = stripHtmlTags($deskripsi);

        $sql = "UPDATE jbsakad.rpp
                   SET deskripsi_data = ?
                 WHERE replid = ?";
        $stmt = $db->PrepareStatement($sql);
        $stmt->bind_param("si", $deskripsi_data, $replid);
        $stmt->execute();
    }
}

if (!IsIndexExist($db, "jbsakad", "guru", "IX_guru"))
{
    $sql = "ALTER TABLE jbsakad.guru
              ADD INDEX `IX_guru` (`statusguru`,`aktif`)";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "dasarpenilaian", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`dasarpenilaian`
              ADD COLUMN `urutan` TINYINT(3) NOT NULL DEFAULT 0 AFTER `aktif`";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "jam", "keterangan"))
{
    $sql = "ALTER TABLE `jbsakad`.`jam`
              ADD COLUMN keterangan VARCHAR(255) NOT NULL DEFAULT '' AFTER `jam2`";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "kalenderakademik", "keterangan"))
{
    $sql = "ALTER TABLE `jbsakad`.`kalenderakademik`
              ADD COLUMN `keterangan` VARCHAR(255) NOT NULL DEFAULT '' AFTER `kalender`;";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "kalenderakademik", "IX_kalenderakademik"))
{
    $sql = "ALTER TABLE `jbsakad`.`kalenderakademik`
              ADD INDEX `IX_kalenderakademik` (`aktif`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "aktivitaskalender", "IX_aktivitaskalender"))
{
    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender`
              ADD INDEX `IX_aktivitaskalender` (`tanggalawal`)";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "aktivitaskalender", "kode"))
{
    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender`
              ADD COLUMN `kode` varchar(10) NOT NULL DEFAULT '' AFTER `tanggalakhir`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender`
           MODIFY COLUMN `kegiatan` varchar(255) NOT NULL DEFAULT ''";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsumum", "tingkapendidikan", "urutan"))
{
    $sql = "ALTER TABLE `jbsumum`.`tingkatpendidikan`
              ADD COLUMN `urutan` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "siswa", "IX_siswa1"))
{
    $sql = "ALTER TABLE `jbsakad`.`siswa`
              ADD INDEX IX_siswa1 (`nisn`, `nik`, `nama`, `panggilan`, `darah`, `hpsiswa`, `hportu`, `info1`, `info2`, `aktif`, `alumni`, `pinsiswa`, `jarak`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "siswa", "IX_siswa2"))
{
    $sql = "ALTER TABLE `jbsakad`.`siswa`
              ADD INDEX IX_siswa2 (`alamatsiswa`, `alamatortu`, `alamatsurat`, `emailsiswa`, `emailayah`, `emailibu`, `kesehatan`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "riwayatkelassiswa", "IX_riwayatkelassiswa"))
{
    $sql = "ALTER TABLE `jbsakad`.`riwayatkelassiswa`
              ADD INDEX `IX_riwayatkelassiswa` (`mulai`,`aktif`,`status`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "calonsiswa", "IX_calonsiswa1"))
{
    $sql = "ALTER TABLE `jbsakad`.`calonsiswa`
              ADD INDEX IX_calonsiswa1 (`nopendaftaran`,`nisn`, `nik`, `nama`, `panggilan`, `darah`, `hpsiswa`, `hportu`, `info1`, `info2`, `aktif`, `pinsiswa`, `jarak`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "calonsiswa", "IX_calonsiswa2"))
{
    $sql = "ALTER TABLE `jbsakad`.`calonsiswa`
              ADD INDEX IX_calonsiswa2 (`alamatsiswa`, `alamatortu`, `alamatsurat`, `emailsiswa`, `emailayah`, `emailibu`, `kesehatan`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "calonsiswa", "IX_calonsiswa3"))
{
    $sql = "ALTER TABLE `jbsakad`.`calonsiswa`
              ADD INDEX IX_calonsiswa3 (`sum1`, `sum2`, `ujian1`, `ujian2`, `ujian3`, `ujian4`, `ujian5`, `ujian6`, `ujian7`, `ujian8`, `ujian9`, `ujian10`)";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsumum", "bagiannota"))
{
    $sql = "CREATE TABLE `jbsumum`.`bagiannota` (
                `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
                `bagian` VARCHAR(50) NOT NULL,
                `urutan` INT NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `UX_bagiannota`(`bagian`),
                KEY `IX_bagiannota` (`urutan`)
            ) ENGINE = InnoDB";
    ExecIgnore($db, $sql);

    $sql = "INSERT INTO jbsumum.bagiannota (bagian, urutan)
            VALUES ('Umum', 1),
                   ('Akademik', 2),
                   ('Keuangan', 3),
                   ('Kepegawaian', 4)";
    ExecIgnore($db, $sql);              
}

if (!IsTableExist($db, "jbsumum", "nota"))
{
    $sql = "CREATE TABLE  `jbsumum`.`nota` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                `nis` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                `nip` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                `nic` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                `kelompok` tinyint(3) NOT NULL DEFAULT '0' COMMENT '0 Semua, 1 Siswa, 2 Pegawai, 3 Calon Siswa',
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `bagian` varchar(50) NOT NULL,
                `judul` varchar(255) NOT NULL,
                `nota` varchar(5000) NOT NULL,
                `pemilik` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `FK_nota_siswa` (`nis`),
                KEY `FK_nota_pegawai` (`nip`),
                KEY `FK_nota_calonsiswa` (`nic`),
                KEY `FK_nota_bagiannota` (`bagian`),
                KEY `IX_nota` (`tanggal`,`bagian`,`kelompok`),
                FULLTEXT (`judul`,`nota`),
                CONSTRAINT `FK_nota_bagiannota` FOREIGN KEY (`bagian`) REFERENCES `jbsumum`.`bagiannota` (`bagian`) ON UPDATE CASCADE,
                CONSTRAINT `FK_nota_calonsiswa` FOREIGN KEY (`nic`) REFERENCES `jbsakad`.`calonsiswa` (`nopendaftaran`) ON UPDATE CASCADE,
                CONSTRAINT `FK_nota_pegawai` FOREIGN KEY (`nip`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE,
                CONSTRAINT `FK_nota_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE,
                CONSTRAINT `FK_nota_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_nota_pemilik` FOREIGN KEY (`pemilik`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsvcr", "dirshare", "IX_dirshare"))
{
    $sql = "ALTER TABLE `jbsvcr`.`dirshare`
              ADD INDEX `IX_dirshare` (`idroot`, `dirname`, `idguru`)";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "phsiswa", "exclude"))
{
    $sql = "ALTER TABLE `jbsakad`.`phsiswa`
              ADD COLUMN `exclude` tinyint(3) unsigned not null default 0 after alpa";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "statusguru", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`statusguru`
              ADD COLUMN `urutan` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 AFTER `status`";
    ExecIgnore($db, $sql);

    $sql = "UPDATE `jbsakad`.`statusguru`
               SET urutan = 1
             WHERE status = 'Guru Pelajaran'";
    ExecIgnore($db, $sql);

    $sql = "UPDATE `jbsakad`.`statusguru`
               SET urutan = 2
             WHERE status = 'Guru Honorer'";
    ExecIgnore($db, $sql);

    $sql = "UPDATE `jbsakad`.`statusguru`
               SET urutan = 3
             WHERE status = 'Asisten'";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsjs", "riwayatinput"))
{
    $sql = "CREATE TABLE  `jbsjs`.`riwayatinput` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `kategori` varchar(10) NOT NULL,
                `subkategori` varchar(10) NOT NULL,
                `userid` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
                `iddata` varchar(10) NOT NULL,
                `deskripsi` varchar(1000) NOT NULL,
                `info1` varchar(255) DEFAULT NULL,
                `info2` varchar(255) DEFAULT NULL,
                `info3` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `FK_riwayatinput_departemen` (`departemen`),
                KEY `FK_riwayatinput_pegawai` (`userid`),
                KEY `IX_riwayatinput` (`tanggal`,`kategori`,`iddata`,`subkategori`) USING BTREE,
                CONSTRAINT `FK_riwayatinput_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_riwayatinput_pegawai` FOREIGN KEY (`userid`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsjs", "riwayatinput", "app"))
{
    $sql = "ALTER TABLE `jbsjs`.`riwayatinput`
              ADD COLUMN `app` VARCHAR(10) NOT NULL DEFAULT 'JS' AFTER `deskripsi`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsjs`.`riwayatinput`
           MODIFY COLUMN `userid` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsjs`.`riwayatinput`
           MODIFY COLUMN `departemen` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "jenisujian", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`jenisujian`
              ADD COLUMN `urutan` TINYINT(3) UNSIGNED DEFAULT 0 AFTER `dasarpenilaian`;";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "pilihkomenpel", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`pilihkomenpel`
              ADD COLUMN `urutan` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `aktif`;";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`pilihkomenpel`
              ADD COLUMN penulis VARCHAR(100) NOT NULL DEFAULT '' AFTER urutan,
              ADD COLUMN waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER penulis;";   
    ExecIgnore($db, $sql);              
}

if (!IsColumnExist($db, "jbsakad", "pilihkomensos", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`pilihkomensos`
              ADD COLUMN `urutan` INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER `aktif`;";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`pilihkomensos`
              ADD COLUMN penulis VARCHAR(100) NOT NULL DEFAULT '' AFTER urutan,
              ADD COLUMN waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER penulis;";
    ExecIgnore($db, $sql); 

    $sql = "ALTER TABLE `jbsakad`.`pilihkomensos` MODIFY COLUMN `idpelajaran` INT(10) UNSIGNED DEFAULT NULL";
    ExecIgnore($db, $sql); 

    $sql = "ALTER TABLE `jbsakad`.`pilihkomensos` DROP INDEX `FK_pilihkomensos_pelajaran`,
             DROP FOREIGN KEY `FK_pilihkomensos_pelajaran`";
    ExecIgnore($db, $sql); 
}

if (!IsColumnExist($db, "jbsakad", "komenrapor", "penulis"))
{
    $sql = "ALTER TABLE `jbsakad`.`komenrapor`
              ADD COLUMN penulis VARCHAR(100) NOT NULL DEFAULT '' AFTER predikat,
              ADD COLUMN waktu DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER penulis;";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "nap", "penulis"))
{
    $sql = "ALTER TABLE `jbsakad`.`nap`
              ADD COLUMN `penulis` varchar(100) NOT NULL DEFAULT '' AFTER `komentar`,
              ADD COLUMN `waktu` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `penulis`;";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "alumni", "IX_alumni"))
{
    $sql = "ALTER TABLE `jbsakad`.`alumni`
              ADD INDEX `IX_alumni` (`tgllulus`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "mutasisiswa", "IX_mutasisiswa"))
{
    $sql = "ALTER TABLE `jbsakad`.`mutasisiswa`
              ADD INDEX `IX_mutasisiswa` (`tglmutasi`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "auditnilai", "IX_auditnilai"))
{
    $sql = "ALTER TABLE `jbsakad`.`auditnilai` ADD INDEX `IX_auditnilai` (`tanggal`)";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`auditnilai` ADD FULLTEXT(alasan, informasi)";
    ExecIgnore($db, $sql);
}

if (!IsFullTextIndexExist($db, "jbsjs", "riwayatinput"))
{
    $sql = "ALTER TABLE `jbsjs`.`riwayatinput` ADD FULLTEXT(deskripsi)";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "calonsiswa", "ketpindah"))
{
    $sql = "ALTER TABLE `jbsakad`.`calonsiswa`
              ADD COLUMN `ketpindah` VARCHAR(255) NULL DEFAULT '' AFTER `keterangan`;";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsakad", "infojadwal", "urutan"))
{
    $sql = "ALTER TABLE `jbsakad`.`infojadwal`
            ADD COLUMN `urutan` tinyint(3) NOT NULL DEFAULT 0 AFTER `terlihat`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`infojadwal`
               ADD INDEX `IX_infojadwal` (`aktif`, `urutan`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "aktivitaskalender", "IX_aktivitaskalender2"))
{
    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender` DROP INDEX `IX_aktivitaskalender`";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender` ADD INDEX `IX_aktivitaskalender2` (`tanggalawal`)";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender`
             DROP FOREIGN KEY `FK_aktivitaskalender_kalenderakademik`;";
    ExecIgnore($db, $sql);

    $sql = "ALTER TABLE `jbsakad`.`aktivitaskalender`
              ADD CONSTRAINT `FK_aktivitaskalender_kalenderakademik` FOREIGN KEY (`idkalender`) REFERENCES `kalenderakademik` (`replid`)
              ON DELETE RESTRICT
              ON UPDATE CASCADE;";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsakad", "siswa", "IX_siswa"))
{
    $sql = "ALTER TABLE `jbsakad`.`siswa`
              ADD INDEX `IX_siswa` (`nama`, `panggilan`, `aktif`, `alumni`, `tgllahir`, `penghasilanayah`, `penghasilanibu`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbssat", "frpresence", "IX_frpresence"))
{
    $sql = "ALTER TABLE `jbssat`.`frpresence`
              ADD INDEX `IX_frpresence` (`date_in`, `time_in`, `active`,`smssent`,`smssenthome`);";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbssat", "frpresensikegiatan", "IX_frpresensikegiatan"))
{
    $sql = "ALTER TABLE `jbssat`.`frpresensikegiatan`
             ADD INDEX `IX_frpresensikegiatan` (`date_in`, `time_in`, `active`,`smssent`,`smssenthome`);";
    ExecIgnore($db, $sql);
}
?>