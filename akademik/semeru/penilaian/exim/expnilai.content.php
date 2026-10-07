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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../library/common.func.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/departemen.php');
require_once('../../library/msg.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');
require_once('../../library/userinfo.php');
require_once('expnilai.content.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Excel Nilai</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js"></script>
    <script language="javascript" src="expnilai.content.js?r=<?=filemtime('expnilai.content.js')?>"></script>
</head>
<body style="padding: 10px;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">

<table class="tab tabShadow" cellpadding="5" cellspacing="0" >
<tr style="background-color: #e5f6ff; height: 24px;">
    <td width="30">&nbsp;</td>
    <td width="30">A</td>
    <td width="125">B</td>
    <td width="200">C</td>
    <td width="100">D</td>
    <td width="200">E</td>
    <td width="100">F</td>
    <td width="120">G</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">1</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;"><font style="font-size: 12px; font-weight: bold;">FORM NILAI</font></td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">2</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">3</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">DEPARTEMEN (*):</td>
    <td><?=$departemen?></td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">4</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KELAS:</td>
    <td><?=$nmkelas?></td>
    <td style="border-style: none;">TINGKAT:</td>
    <td><?=$nmtingkat?></td>
    <td style="border-style: none;">ID KELAS (*):</td>
    <td><?=$kelas?></td>
</tr>
<tr>
    <td style="background-color: #e5f6ff; height: 24px;" align="center">5</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">NIP GURU (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">NAMA GURU:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">6</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">PELAJARAN:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">ASPEK:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">JENIS UJIAN:</td>
    <td>&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">7</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KODE UJIAN (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">8</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">TANGGAL (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">BULAN (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">TAHUN (*):</td>
    <td>&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">9</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">RPP:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">10</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">MATERI (*):</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">11</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">KETERANGAN:</td>
    <td>&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">12</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<tr style="height: 24px">
    <td style="background-color: #e5f6ff;" align="center">13</td>
    <td class="header">No</td>
    <td class="header">NIS (*)</td>
    <td class="header">Nama</td>
    <td class="header">Nilai (*)</td>
    <td class="header">Keterangan</td>
    <td style="border-style: none;">&nbsp;</td>
    <td style="border-style: none;">&nbsp;</td>
</tr>
<?php
$sql = "SELECT nis, nama 
          FROM jbsakad.siswa 
         WHERE idkelas = '$idKelas' 
           AND aktif = 1 
           AND alumni = 0 
         ORDER BY nama ASC";
$res = $db->QueryDb($sql);
$no = 0;
$rownum = 13;
while($row = mysqli_fetch_array($res))
{
    ?>
    <tr style="height: 24px;">
        <td style="background-color: #e5f6ff;" align="center"><?= ++$rownum ?></td>
        <td><?= ++$no ?></td>
        <td><?= $row['nis'] ?></td>
        <td><?= $row['nama'] ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td style="border-style: none;">&nbsp;</td>
        <td style="border-style: none;">&nbsp;</td>
    </tr>
<?php
}
echo "</table>";

$nmfile = "FORM_NILAI_";
$nmfile .= "K-" . SafeName($kelas) . "_";
$nmfile .= "T-" . SafeName($tingkat) . "_";
$nmfile .= "TA-" . SafeName($tahunAjaran) . ".xlsx";
?>
<br>
<table border="0" cellpadding="2">
<tr>
    <td>
        <strong>Nama File (*.xlsx):</strong>
        <input type="text" id="filename" name="filename" class="inputbox" style='background-color: #f9ffc9;' size="50" value="<?=$nmfile?>">
    </td>
    <td>
        <input onclick="cetakExcel()" type="button" class="dialogButtonPositive" style="width: 200px; height: 40px;" value="Simpan Form Nilai Excel">
    </td>
</tr>
</table>

</body>
</html>