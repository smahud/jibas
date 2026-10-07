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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('../../library/class/jpgraph.php');
require_once('../../library/class/jpgraph_bar.php');
require_once('../../library/class/jpgraph_line.php');
require_once('pendataan.nilai.dialog.func.php');

$departemen = RequestData("departemen", "");
$jenis = RequestData("jenis", "");
$kodeAspek = RequestData("kodeaspek", "");
$namaAspek = RequestData("namaaspek", "");
$komentar64 = RequestData("komentar64", "");
$idTingkat = RequestData("idtingkat", "");
$tingkat = RequestData("tingkat", "");
$idPelajaran = RequestData("idpelajaran", "");
$pelajaran = RequestData("pelajaran", "");
$index = RequestData("index", "");
$kodeJenis = RequestData("kodejenis", "");
$namaJenis = RequestData("namajenis", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Input Nilai</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../style/tinymce-small-toolbar.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/stringutil.js"></script>
    <script language="javascript" src="../../script/dateutil.js"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="komentar.template.dialog.js?<?=filemtime('komentar.template.dialog.js')?>"></script>
</head>
<body style="padding: 10px;"> 
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="jenis" value="<?=$jenis?>">
<input type="hidden" id="kodeaspek" value="<?=$kodeAspek?>">
<input type="hidden" id="namaaspek" value="<?=$namaAspek?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="index" value="<?=$index?>">
<input type="hidden" id="kodejenis" value="<?=$kodeJenis?>">
<input type="hidden" id="namajenis" value="<?=$namaJenis?>">

<?php 
    if ($jenis == "nilai") 
        echo "<span class='dialogTitle'>Template Komentar Nilai Rapor</span><br><br>";
    elseif ($jenis == "sikap") 
        echo "<span class='dialogTitle'>Template Komentar Sikap $namaJenis</span><br><br>";
?>

<table border="0" cellpadding="5" cellspacing="0" width="98%">
<tr>
    <td style="width: 80px">Departemen</td>
    <td><b><?=$departemen?></b></td>
</tr>
<tr>
    <td>Tingkat</td>
    <td><b><?=$tingkat?></b></td>
</tr>
<?php
if ($jenis == "nilai")
{
    echo "<tr>";
    echo "<td>Pelajaran</td>";
    echo "<td><b>$pelajaran</b></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Aspek</td>";
    echo "<td><b>$namaAspek</b></td>";
    echo "</tr>";
}
else if ($jenis == "sikap")
{
    echo "<tr>";
    echo "<td>Sikap</td>";
    echo "<td><b>$namaJenis</b></td>";
    echo "</tr>";
}
?>

<tr>
    <td>Urutan</td>
    <td>
        <input type="text" id="urutan" value="" class="inputbox fs-13" style="width: 60px;">
    </td>
</tr>
<tr>
    <td colspan="2">
        Komentar <?= $tag_mandatory ?>
        <textarea name='komentar' id='komentar' rows='3' style="width: 96%"><?= base64_decode($komentar64)?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <input type="button" id="btSimpan" value="Simpan" class="dialogButtonPositive" style="width: 100px;" onclick="simpan()">
        <input type="button" id="btTutup" value="Tutup" class="dialogButtonNegative" style="width: 100px;" onclick="window.close()">
    </td>
</tr>
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>