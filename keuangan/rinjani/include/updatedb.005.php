<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
/*
 * 2026-05-28: Buat table2 database untuk Online Payment Rinjani
 */
if (!IsTableExist($db, "jbsjs", "syncdata2"))
{
    $sql = "CREATE TABLE  `jbsjs`.`syncdata2` (
                `id` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
                `tablename` varchar(255) NOT NULL,
                `iddata` varchar(255) NOT NULL DEFAULT '0',
                `state` varchar(255) NOT NULL DEFAULT 'I',
                `dataload` tinyint(3) NOT NULL DEFAULT 1 COMMENT '1 Text, 2 ABase64',
                `status` tinyint(3) NOT NULL DEFAULT 0 COMMENT '0 Unsync, -1 Failed',
                `loginfo` varchar(1000) DEFAULT '',
                `logtime` datetime DEFAULT NULL,
                `nerror` int(10) unsigned NOT NULL DEFAULT 0,
                PRIMARY KEY (`id`),
                UNIQUE KEY `UX_syncdata2` (`tablename`,`iddata`) USING BTREE,
                KEY `IX_syncdata2` (`dataload`,`status`,`nerror`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "bank2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`bank2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `bank` varchar(255) NOT NULL,
                `bankno` varchar(50) NOT NULL,
                `bankvano` varchar(45) NOT NULL,
                `qrisexist` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `qrismime` varchar(30) NOT NULL,
                `qrisname` varchar(100) NOT NULL,
                `qrisid` varchar(100) NOT NULL,
                `qris` mediumtext NOT NULL,
                `bankname` varchar(255) NOT NULL,
                `bankloc` varchar(255) NOT NULL,
                `keterangan` varchar(255) NOT NULL,
                `aktif` tinyint(3) unsigned NOT NULL DEFAULT 1,
                `urutan` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `rekkas` varchar(15) NOT NULL,
                `rekpendapatan` varchar(15) NOT NULL,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                PRIMARY KEY (`replid`),
                UNIQUE KEY `UX_bank2` (`bankno`,`departemen`),
                KEY `FK_bank2_departemen` (`departemen`),
                KEY `FK_bank2_rekakun` (`rekkas`),
                KEY `FK_bank2_rekakun2` (`rekpendapatan`),
                KEY `IX_bank2` (`bankno`,`issync`,`bank`) USING BTREE,
                CONSTRAINT `FK_bank2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_bank2_rekakun` FOREIGN KEY (`rekkas`) REFERENCES `rekakun` (`kode`) ON UPDATE CASCADE,
                CONSTRAINT `FK_bank2_rekakun2` FOREIGN KEY (`rekpendapatan`) REFERENCES `rekakun` (`kode`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "tagihancount2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`tagihancount2` (
                `replid` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `bulan` tinyint(3) unsigned NOT NULL,
                `tahun` smallint(5) unsigned NOT NULL,
                `counter` int(10) unsigned NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_tagihancount2_idx` (`departemen`),
                KEY `IX_tagihancount2` (`bulan`,`tahun`) USING BTREE,
                CONSTRAINT `FK_tagihancount2` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "tagihansetcount2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`tagihansetcount2` (
                `replid` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `bulan` tinyint(3) unsigned NOT NULL,
                `tahun` smallint(5) unsigned NOT NULL,
                `counter` int(10) unsigned NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_tagihansetcount2_idx` (`departemen`),
                KEY `IX_tagihansetcount2` (`bulan`,`tahun`) USING BTREE,
                CONSTRAINT `FK_tagihansetcount2` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "tagihanset2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`tagihanset2` (
                `replid` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `nomor` varchar(100) NOT NULL,
                `nama` varchar(255) NOT NULL,
                `departemen` varchar(50) NOT NULL,
                `idtahunbuku` int(11) unsigned NOT NULL,
                `idtingkat` int(11) unsigned DEFAULT NULL,
                `idkelas` varchar(500) DEFAULT NULL,
                `nis` varchar(20) DEFAULT NULL,
                `idiuran` varchar(500) NOT NULL,
                `stiuran` varchar(500) NOT NULL,
                `petugas` varchar(30) DEFAULT NULL,
                `bulan` tinyint(3) unsigned NOT NULL,
                `tahun` smallint(5) unsigned NOT NULL,
                `keterangan` varchar(255) DEFAULT NULL,
                `tanggalbuat` datetime NOT NULL,
                `issync` tinyint(3) unsigned NOT NULL,
                `token` smallint(5) unsigned NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_tagihanset2_departemen` (`departemen`),
                KEY `FK_tagihanset2_pegawai` (`petugas`),
                KEY `FK_tagihanset2_tahunbuku` (`idtahunbuku`),
                KEY `FK_tagihanset2_siswa` (`nis`),
                KEY `IX_tagihanset2` (`idtahunbuku`,`bulan`,`tahun`,`issync`,`tanggalbuat`,`nomor`) USING BTREE,
                CONSTRAINT `FK_tagihanset2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihanset2_pegawai` FOREIGN KEY (`petugas`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihanset2_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihanset2_tahunbuku` FOREIGN KEY (`idtahunbuku`) REFERENCES `tahunbuku` (`replid`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "tagihansiswainfo2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`tagihansiswainfo2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idtagihanset` int(11) unsigned NOT NULL,
                `nis` varchar(20) NOT NULL,
                `bulan` int(11) unsigned NOT NULL,
                `tahun` int(11) unsigned NOT NULL,
                `notagihan` varchar(100) NOT NULL,
                `info` varchar(2000) NOT NULL,
                `jumlah` decimal(15,0) unsigned NOT NULL,
                `status` tinyint(4) unsigned NOT NULL,
                `aktif` tinyint(3) unsigned NOT NULL DEFAULT 1,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `token` smallint(5) unsigned NOT NULL,
                `ckdate` datetime DEFAULT NULL,
                `ckdesc` varchar(255) DEFAULT NULL,
                `ckjsuserid` varchar(20) DEFAULT NULL,
                `ckjsusername` varchar(255) DEFAULT NULL,
                `ckjsdevid` varchar(20) DEFAULT NULL,
                `ckjsdevname` varchar(500) DEFAULT NULL,
                `jsonfees` text NOT NULL,
                `servicefee` int(11) NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_tagihansiswainfo2_siswa` (`nis`),
                KEY `FK_tagihansiswainfo2_tagihanset` (`idtagihanset`),
                KEY `IX_tagihansiswainfo2` (`bulan`,`tahun`,`notagihan`,`status`,`aktif`,`issync`) USING BTREE,
                CONSTRAINT `FK_tagihansiswainfo2_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihansiswainfo2_tagihanset` FOREIGN KEY (`idtagihanset`) REFERENCES `tagihanset2` (`replid`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "tagihansiswadata2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`tagihansiswadata2` (
                `replid` int(11) unsigned NOT NULL AUTO_INCREMENT,
                `idtagihanset` int(11) unsigned NOT NULL,
                `nis` varchar(20) NOT NULL,
                `notagihan` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
                `bulan` tinyint(4) unsigned NOT NULL,
                `tahun` smallint(6) unsigned NOT NULL,
                `idbesarjtt` int(11) unsigned DEFAULT NULL,
                `idpenerimaan` int(11) unsigned DEFAULT NULL,
                `kode` varchar(5) NOT NULL,
                `penerimaan` varchar(255) NOT NULL,
                `jtagihan` decimal(15,0) unsigned NOT NULL,
                `jdiskon` decimal(15,0) unsigned NOT NULL,
                `jbesar` decimal(15,0) NOT NULL,
                `jbayar` decimal(15,0) NOT NULL,
                `jsisa` decimal(15,0) NOT NULL,
                `status` tinyint(4) unsigned NOT NULL DEFAULT 0,
                `aktif` tinyint(3) unsigned NOT NULL DEFAULT 1,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `token` smallint(6) unsigned NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_tagihansiswadata2_siswa` (`nis`),
                KEY `FK_tagihansiswadata2_tagihanset` (`idtagihanset`),
                KEY `FK_tagihansiswadata2_datapenerimaan` (`idpenerimaan`),
                KEY `FK_tagihansiswadata2_besarjtt` (`idbesarjtt`),
                KEY `IX_tagihansiswadata2` (`notagihan`,`bulan`,`tahun`,`status`,`aktif`,`issync`,`kode`) USING BTREE,
                CONSTRAINT `FK_tagihansiswadata2_besarjtt` FOREIGN KEY (`idbesarjtt`) REFERENCES `besarjtt` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihansiswadata2_datapenerimaan` FOREIGN KEY (`idpenerimaan`) REFERENCES `datapenerimaan` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihansiswadata2_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE,
                CONSTRAINT `FK_tagihansiswadata2_tagihanset` FOREIGN KEY (`idtagihanset`) REFERENCES `tagihanset2` (`replid`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "formatpesanpg2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`formatpesanpg2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `kelompok` varchar(25) NOT NULL,
                `pesan` varchar(1000) NOT NULL,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                PRIMARY KEY (`replid`),
                KEY `FK_formatpesanpg2_departemen` (`departemen`),
                KEY `IX_formatpesanpg2` (`kelompok`),
                CONSTRAINT `FK_formatpesanpg2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "formatnomortagihan2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`formatnomortagihan2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `awalan` varchar(5) NOT NULL,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                PRIMARY KEY (`replid`),
                KEY `FK_formatnomortagihan2_departemen` (`departemen`),
                CONSTRAINT `FK_formatnomortagihan2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "infobayar2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`infobayar2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) NOT NULL,
                `bagian` varchar(10) NOT NULL,
                `info` mediumtext NOT NULL,
                `issync` tinyint(3) unsigned NOT NULL DEFAULT 0,
                PRIMARY KEY (`replid`),
                KEY `FK_infobayar2_departemen` (`departemen`),
                KEY `IX_infobayar2` (`bagian`,`issync`) USING BTREE,
                CONSTRAINT `FK_infobayar2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "bankmutasi2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`bankmutasi2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) DEFAULT NULL,
                `bankno` varchar(50) NOT NULL,
                `jenis` tinyint(3) unsigned NOT NULL COMMENT '1 Simpan, 2 Ambil',
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `keterangan` varchar(255) NOT NULL,
                `petugas` varchar(30) DEFAULT NULL,
                `berkas` mediumtext NOT NULL,
                `adaberkas` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `nomormutasi` varchar(100) NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_bankmutasi2_departemen` (`departemen`),
                KEY `FK_bankmutasi2_bank2` (`bankno`),
                KEY `FK_bankmutasi2_pegawai` (`petugas`),
                CONSTRAINT `FK_bankmutasi2_bank2` FOREIGN KEY (`bankno`) REFERENCES `bank2` (`bankno`) ON UPDATE CASCADE,
                CONSTRAINT `FK_bankmutasi2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_bankmutasi2_pegawai` FOREIGN KEY (`petugas`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgtranslebih2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgtranslebih2` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `departemen` varchar(50) DEFAULT NULL,
                `metode` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `nomor` varchar(100) NOT NULL,
                `jlebihtrans` int(10) unsigned NOT NULL DEFAULT 0,
                `jlebihsisa` int(10) unsigned NOT NULL DEFAULT 0,
                `bankno` varchar(50) NOT NULL,
                `prstatus` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `pridmutasi` int(10) unsigned DEFAULT NULL,
                `prket` varchar(255) DEFAULT NULL,
                `prwaktu` datetime DEFAULT NULL,
                `prpetugas` varchar(50) DEFAULT NULL,
                `prmetode` tinyint(3) unsigned NOT NULL DEFAULT 0,
                `prjurnalbank` varchar(50) DEFAULT NULL,
                `pridtabungan` int(11) unsigned NOT NULL DEFAULT 0,
                `prnamatabungan` varchar(50) DEFAULT NULL,
                `prjurnaltabungan` varchar(50) DEFAULT NULL,
                `prpetugastf` varchar(50) DEFAULT NULL,
                PRIMARY KEY (`id`),
                KEY `IX_pgtranslebih2` (`tanggal`,`nomor`,`prstatus`),
                KEY `FK_pgtranslebih2_departemen` (`departemen`),
                KEY `FK_pgtranslebih2_bank2` (`bankno`),
                KEY `FK_pgtranslebih2_bankmutasi2` (`pridmutasi`),
                CONSTRAINT `FK_pgtranslebih2_bank2` FOREIGN KEY (`bankno`) REFERENCES `bank2` (`bankno`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtranslebih2_bankmutasi2` FOREIGN KEY (`pridmutasi`) REFERENCES `bankmutasi2` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtranslebih2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgtrans2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgtrans2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `paymentid` varchar(25) NOT NULL,
                `departemen` varchar(50) DEFAULT NULL,
                `jibasid` varchar(50) NOT NULL DEFAULT '',
                `nis` varchar(20) DEFAULT NULL,
                `nip` varchar(30) DEFAULT NULL,
                `nic` varchar(20) DEFAULT NULL,
                `username` varchar(255) NOT NULL DEFAULT '',
                `bank` varchar(100) NOT NULL,
                `bankno` varchar(45) NOT NULL,
                `nomorts` varchar(100) NOT NULL,
                `nomor` varchar(100) NOT NULL,
                `transaksi` varchar(5000) NOT NULL,
                `jenis` tinyint(3) unsigned NOT NULL COMMENT '1 Tagihan 2 Keranjang',
                `jpembayaran` int(11) NOT NULL DEFAULT 0,
                `jlayanan` int(11) NOT NULL DEFAULT 0,
                `jlebih` int(11) NOT NULL DEFAULT 0,
                `waktu` datetime NOT NULL,
                `tanggal` date NOT NULL,
                `idpetugas` varchar(30) NOT NULL,
                `petugas` varchar(255) NOT NULL,
                `ketver` varchar(255) NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_pgtrans2_siswa` (`nis`),
                KEY `FK_pgtrans2_pegawai` (`nip`),
                KEY `FK_pgtrans2_calonsiswa` (`nic`),
                KEY `FK_pgtrans2_idpetugas` (`idpetugas`),
                KEY `FK_pgtrans2_departemen` (`departemen`),
                KEY `IX_pgtrans2` (`nomor`,`nomorts`,`jenis`,`tanggal`,`bankno`,`idpetugas`) USING BTREE,
                CONSTRAINT `FK_pgtrans2_calonsiswa` FOREIGN KEY (`nic`) REFERENCES `jbsakad`.`calonsiswa` (`nopendaftaran`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtrans2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtrans2_idpetugas` FOREIGN KEY (`idpetugas`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtrans2_pegawai` FOREIGN KEY (`nip`) REFERENCES `jbssdm`.`pegawai` (`nip`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtrans2_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgtransdata2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgtransdata2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `idpgtrans` int(10) unsigned NOT NULL,
                `kategori` varchar(10) NOT NULL,
                `nama` varchar(255) NOT NULL DEFAULT '',
                `idtahunbuku` int(10) unsigned NOT NULL,
                `idpenerimaan` int(10) unsigned DEFAULT NULL,
                `idpenerimaanjtt` int(10) unsigned DEFAULT NULL,
                `idpenerimaaniuran` int(10) unsigned DEFAULT NULL,
                `idtabungan` int(10) unsigned DEFAULT NULL,
                `idtabungantr` int(10) unsigned DEFAULT NULL,
                `idtabunganp` int(10) unsigned DEFAULT NULL,
                `idtabunganptr` int(10) unsigned DEFAULT NULL,
                `kelompok` tinyint(3) unsigned NOT NULL COMMENT '1 Iuran 2 Tabungan 3 Tabungan Pegawai 4 Service Fee 5 Kelebihan',
                `jumlah` int(10) unsigned NOT NULL,
                `diskon` int(10) unsigned NOT NULL DEFAULT 0,
                `tersisa` int(10) unsigned NOT NULL DEFAULT 0,
                `nokas` varchar(50) NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_pgtransdata2_pgtrans2` (`idpgtrans`),
                KEY `FK_pgtransdata2_penerimaan` (`idpenerimaan`),
                KEY `FK_pgtransdata2_tahunbuku` (`idtahunbuku`),
                KEY `FK_pgtransdata2_penerimaanjtt` (`idpenerimaanjtt`),
                KEY `FK_pgtransdata2_tabungan` (`idtabungan`),
                KEY `FK_pgtransdata2_tabunganp` (`idtabunganp`),
                KEY `IX_pgtransdata2` (`kelompok`,`kategori`) USING BTREE,
                KEY `FK_pgtransdata2_penerimaaniuran` (`idpenerimaaniuran`),
                KEY `FK_pgtransdata2_tabungantr` (`idtabungantr`),
                KEY `FK_pgtransdata2_tabunganptr` (`idtabunganptr`),
                CONSTRAINT `FK_pgtransdata2_penerimaan` FOREIGN KEY (`idpenerimaan`) REFERENCES `datapenerimaan` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_penerimaaniuran` FOREIGN KEY (`idpenerimaaniuran`) REFERENCES `penerimaaniuran` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_penerimaanjtt` FOREIGN KEY (`idpenerimaanjtt`) REFERENCES `penerimaanjtt` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_pgtrans2` FOREIGN KEY (`idpgtrans`) REFERENCES `pgtrans2` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_tabungan` FOREIGN KEY (`idtabungan`) REFERENCES `datatabungan` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_tabunganp` FOREIGN KEY (`idtabunganp`) REFERENCES `datatabunganp` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_tabunganptr` FOREIGN KEY (`idtabunganptr`) REFERENCES `tabunganp` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_tabungantr` FOREIGN KEY (`idtabungantr`) REFERENCES `tabungan` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_pgtransdata2_tahunbuku` FOREIGN KEY (`idtahunbuku`) REFERENCES `tahunbuku` (`replid`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgtranslog2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgtranslog2` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `paymentid` varchar(25) NOT NULL,
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `metode` tinyint(4) unsigned NOT NULL COMMENT '1 Tagihan 2 Keranjang',
                `nomor` varchar(100) NOT NULL,
                `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT '1 Berhasil -1 Gagal',
                `kelompok` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT '0 Rincian 1 Hasil Akhir',
                `keterangan` varchar(255) NOT NULL,
                `jsonreturn` varchar(2500) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IX_pgtranslog2` (`tanggal`,`metode`,`nomor`,`kelompok`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "bankmutasidata2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`bankmutasidata2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `kategori` varchar(10) NOT NULL,
                `idmutasi` int(10) unsigned DEFAULT NULL,
                `idpenerimaan` int(11) unsigned NOT NULL,
                `idtabungan` int(11) unsigned NOT NULL,
                `idtabunganp` int(11) unsigned NOT NULL,
                `iddeposit` int(11) unsigned NOT NULL,
                `jumlah` int(11) unsigned NOT NULL,
                `nokas` varchar(50) NOT NULL,
                `keterangan` varchar(255) NOT NULL,
                PRIMARY KEY (`replid`),
                KEY `FK_bankmutasidata2_bankmutasi2` (`idmutasi`),
                CONSTRAINT `FK_bankmutasidata2_bankmutasi2` FOREIGN KEY (`idmutasi`) REFERENCES `bankmutasi2` (`replid`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "banksaldo2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`banksaldo2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) DEFAULT NULL,
                `bankno` varchar(50) NOT NULL,
                `kategori` varchar(10) NOT NULL,
                `idpenerimaan` int(11) unsigned NOT NULL,
                `idtabungan` int(11) unsigned NOT NULL,
                `idtabunganp` int(11) unsigned NOT NULL,
                `iddeposit` int(11) unsigned NOT NULL,
                `kelompok` tinyint(3) unsigned NOT NULL COMMENT '1 Pembayaran 2 Deposit 3 Biaya Layanan 4 Kelebihan',
                `saldo` bigint(20) unsigned NOT NULL,
                `lasttime` datetime NOT NULL,
                PRIMARY KEY (`replid`),
                UNIQUE KEY `UX_banksaldo2` (`departemen`,`bankno`,`kategori`,`idpenerimaan`,`idtabungan`,`idtabunganp`,`iddeposit`),
                KEY `FK_banksaldo2_departemen` (`departemen`),
                KEY `FK_banksaldo2_bank2` (`bankno`),
                CONSTRAINT `FK_banksaldo2_bank2` FOREIGN KEY (`bankno`) REFERENCES `bank2` (`bankno`) ON UPDATE CASCADE,
                CONSTRAINT `FK_banksaldo2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "bankdeposit2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`bankdeposit2` (
                `replid` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `departemen` varchar(50) DEFAULT NULL,
                `bankno` varchar(50) NOT NULL,
                `nama` varchar(255) NOT NULL,
                `keterangan` varchar(255) NOT NULL,
                `aktif` tinyint(11) unsigned NOT NULL DEFAULT 1,
                PRIMARY KEY (`replid`),
                KEY `FK_bankdeposit2_departemen` (`departemen`),
                KEY `FK_bankdeposit2_bank2` (`bankno`),
                KEY `IX_bankdeposit2` (`aktif`),
                CONSTRAINT `FK_bankdeposit2_bank2` FOREIGN KEY (`bankno`) REFERENCES `bank2` (`bankno`) ON UPDATE CASCADE,
                CONSTRAINT `FK_bankdeposit2_departemen` FOREIGN KEY (`departemen`) REFERENCES `jbsakad`.`departemen` (`departemen`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgresynclog2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgresynclog2` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `tanggal` date NOT NULL,
                `waktu` datetime NOT NULL,
                `status` tinyint(3) DEFAULT 0,
                `token` varchar(100) DEFAULT NULL,
                `nomor` varchar(100) DEFAULT NULL,
                `description` varchar(500) DEFAULT NULL,
                `protocol` varchar(10) NOT NULL,
                `message` varchar(500) NOT NULL,
                `jsondata64` text NOT NULL,
                `jsonreturn64` text NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IX_pgresynclog2` (`tanggal`,`token`,`protocol`,`nomor`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsColumnExist($db, "jbsfina", "multitransinfo", "pgnomor"))
{
    $sql = "ALTER TABLE `jbsfina`.`multitransinfo` 
           MODIFY COLUMN `paymentstatus` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '\'0: Manual, 5: PaymentGateway\'',
              ADD COLUMN `pgnomor` VARCHAR(100) AFTER `paymentdate`,
              ADD COLUMN `pgjumlah` INTEGER AFTER `pgnomor`,
              ADD COLUMN `pgbank` VARCHAR(100) AFTER `pgjumlah`";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "vasiswa2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`vasiswa2` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `nis` varchar(20) NOT NULL,
                `idbank` int(11) unsigned NOT NULL,
                `vano` varchar(100) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `FK_vasiswa2_siswa` (`nis`),
                KEY `FK_vasiswa2_bank2` (`idbank`),
                CONSTRAINT `FK_vasiswa2_bank2` FOREIGN KEY (`idbank`) REFERENCES `bank2` (`replid`) ON UPDATE CASCADE,
                CONSTRAINT `FK_vasiswa2_siswa` FOREIGN KEY (`nis`) REFERENCES `jbsakad`.`siswa` (`nis`) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsfina", "pgfinishresync2"))
{
    $sql = "CREATE TABLE  `jbsfina`.`pgfinishresync2` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `stidresync` mediumtext NOT NULL,
                `md5data` varchar(64) NOT NULL,
                `waktu` datetime NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IX_pgfinishresync2` (`md5data`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT='Menyimpan id data resync yang selesai diproses di SJS untuk di datasync lalu diproses lagi di PG'";
    ExecIgnore($db, $sql);
}

if (!IsTableExist($db, "jbsjs", "stateinfo"))
{
    $sql = "CREATE TABLE  `jbsjs`.`stateinfo` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `statekey` varchar(15) NOT NULL,
                `statevalue` varchar(50) NOT NULL,
                `waktu` datetime NOT NULL,
                `keterangan` varchar(255) NOT NULL,
                PRIMARY KEY (`id`),
                KEY `IX_stateinfo` (`statekey`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci COMMENT 'App state information'";
    ExecIgnore($db, $sql);
}
?>