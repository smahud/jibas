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
require_once('../include/sessionchecker.php');
require_once('../include/sessioninfo.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../include/getheader2.php');
require_once('../library/common.func.php');

$departemen = RequestData('departemen', '');
$proses = RequestData('proses', 0);
$replid = RequestData('replid', 0);

$db = new Db();
$db->TryOpenExit(true);

// load names
$prosesName = '';
$kelompokName = '';

$sql = "SELECT p.proses, k.kelompok 
          FROM jbsakad.prosespenerimaansiswa p JOIN jbsakad.kelompokcalonsiswa k ON k.idproses = p.replid 
         WHERE p.replid = $proses 
           AND k.replid = $replid";
$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_assoc($res))
{
    $prosesName = $row['proses'];
    $kelompokName = $row['kelompok'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../style/style.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Calon Siswa</title>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr><td align="left" valign="top">

<?= getHeader2($db, $departemen) ?>

<center>
  <font size="4"><strong>DAFTAR CALON SISWA</strong></font><br />
</center><br /><br />
<table>
<tr>
    <td><strong>Departemen</strong> </td>
    <td><strong>:&nbsp;<?= $departemen ?></strong></td>
</tr>
<tr>
    <td><strong>Penerimaan</strong></td>
    <td><strong>:&nbsp;<?= $prosesName ?></strong></td>
</tr>
<tr>
    <td><strong>Kelompok</strong></td>
    <td><strong>:&nbsp;<?= $kelompokName ?></strong></td>
</tr>

</table>
    <br />
    <table class="tab" id="table" border="1" cellpadding="2" style="border-collapse:collapse" cellspacing="0" width="100%" align="left">
   <tr height="30">
        <td width="4%" class="header" align="center">No</td>
        <td width="15%" class="header" align="center">No Pendaftaran</td>
        <td width="30%" class="header" align="center">Nama</td>
        <td width="*" class="header" align="center">Keterangan</td>
    </tr>
<?php
$sql = "SELECT nopendaftaran, nama, keterangan FROM jbsakad.calonsiswa WHERE idkelompok = $replid";
$res = $db->QueryDb($sql);
$cnt = 0;
while ($row = mysqli_fetch_assoc($res)) { ?>
    <tr height="25">
        <td align="center"><?= ++$cnt ?></td>
        <td align="center"><?= $row['nopendaftaran'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td><?= $row['keterangan'] ?></td>
    </tr>
<?php } ?>
    <!-- END TABLE CONTENT -->
    </table>

    
    

</td></tr>
</table>

<div style="width: 100%; text-align: center; margin-top: 20px">
    <input type="button" name="Tutup" id="Tutup" value="Tutup" class="dialogButtonPositive" onClick="window.close()" />
</div>

</body>
</html>
