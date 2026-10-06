<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 33.0 (Jan 05, 2026)
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

if (!IsIndexExist($db, "jbsfina", "penerimaanjtt", "IX_pembayaranjtt"))
{
    $sql = "ALTER TABLE `jbsfina`.`penerimaanjtt`
              ADD INDEX `IX_pembayaranjtt` (`tanggal`);";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "penerimaanjttcalon", "IX_pembayaranjttcalon"))
{
    $sql = "ALTER TABLE `jbsfina`.`penerimaanjttcalon`
              ADD INDEX `IX_pembayaranjttcalon` (`tanggal`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "penerimaaniuran", "IX_pembayaraniuran"))
{
    $sql = "ALTER TABLE `jbsfina`.`penerimaaniuran`
              ADD INDEX `IX_pembayaraniuran` (`tanggal`);";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "penerimaaniurancalon", "IX_pembayaraniurancalon"))
{
    $sql = "ALTER TABLE `jbsfina`.`penerimaaniurancalon`
              ADD INDEX `IX_pembayaraniurancalon` (`tanggal`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "penerimaanlain", "IX_penerimaanlain"))
{
    $sql = "ALTER TABLE `jbsfina`.`penerimaanlain`
              ADD INDEX `IX_penerimaanlain` (`tanggal`);";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "tabungan", "IX_tabungan"))
{
    $sql = "ALTER TABLE `jbsfina`.`tabungan`
              ADD INDEX `IX_tabungan` (`tanggal`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "tabunganp", "IX_tabunganp"))
{
    $sql = "ALTER TABLE `jbsfina`.`tabunganp`
              ADD INDEX `IX_tabunganp` (`tanggal`)";
    ExecIgnore($db, $sql);
}

if (!IsIndexExist($db, "jbsfina", "pengeluaran", "IX_pengeluaran"))
{
    $sql = "ALTER TABLE `jbsfina`.`pengeluaran`
              ADD INDEX `IX_pengeluaran` (`tanggal`)";
    ExecIgnore($db, $sql);
}


?>