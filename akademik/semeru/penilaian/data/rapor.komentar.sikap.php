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
require_once('rapor.komentar.sikap.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$nis = RequestData("nis", "");
$nama = RequestData("nama", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css?<?=filemtime('../../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/tinymce-small-toolbar.css?<?=filemtime('../../style/tinymce-small-toolbar.css')?>">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rapor.komentar.sikap.js?r=<?=filemtime('rapor.komentar.sikap.js')?>"></script>
</head>
<style>

</style>
<body style="padding: 5px">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="nis" value="<?=$nis?>">
<input type="hidden" id="nama" value="<?=$nama?>">

<?php
echo "<div style='position: relative; width: 99%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='250' valign='top'>";
echo "<span class='fg-secondary'>Siswa</span><br>";
echo "<span class='fs-16 fs-bold'>" . $nama . "</span><br>";
echo "<span class='fg-secondary'>" . $nis . "</span>";
echo "</td>";
echo "<td width='180' valign='middle'>";
echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<div style='position: absolute; top: 10px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>";
echo "</div>";
echo "</div>";

echo "<br>";

$arrjenis = array("SPI", "SOS");
$arrnmjenis = array("Spiritual", "Sosial");
$arrbg = array("#e8ffd4ff", "#dcf4ffff");
$njenis = count($arrjenis);

echo "<input type='hidden' id='njenis' name='njenis' value='$njenis'>";

for($i = 0; $i < $njenis; $i++)
{
    $kodeJenis = $arrjenis[$i];
    $namaJenis = $arrnmjenis[$i];
    $bg = $arrbg[$i];

    $predikat = 3;
    $komentar = "";
    $idkomenrapor = 0;
    $waktu = "";
    $penulis = "";

    $sql = "SELECT replid, predikat, komentar, penulis, DATE_FORMAT(waktu, '%d-%b-%Y %H:%i') AS fwaktu
              FROM jbsakad.komenrapor
             WHERE nis = '$nis'
               AND idsemester = '$idSemester'
               AND idkelas = '$idKelas'
               AND jenis = '$kodeJenis'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idkomenrapor = $row[0];
        $predikat = $row[1];
        $komentar = $row[2];
        $penulis = $row[3];
        $waktu = $row[4];
    }

    echo "<fieldset style='width: 95%; border: 1px solid #ccc; border-radius: 5px; padding: 15px; background-color: $bg;' class='tabShadow'>";
    echo "<legend class='fs-14 fg-secondary'>Sikap <b>$namaJenis</b></legend>";
    echo "<input type='hidden' id='kodejenis$i' name='kodejenis$i' value='$kodeJenis'>";
    echo "<input type='hidden' id='namajenis$i' name='namajenis$i' value='$namaJenis'>";
    echo "<input type='hidden' id='idkomenrapor$i' name='idkomenrapor$i' value='$idkomenrapor'>";

    echo "<table border='0' cellpadding='10' cellspacing='0' width='100%'>";
    echo "<tr>";
    echo "<td width='20%' valign='top'>";

    echo "<b>Predikat:</b><br>";
    ShowSelectPredikat($i);

    echo "</td>";
    echo "<td width='70%' valign='top'>";
    
    echo "<b>Komentar Sikap $namaJenis:</b><br>";
    echo "<div style='padding: 2px;' class='tabShadow bg-white'>";
    echo "<textarea name='komentar$i' id='komentar$i' rows='3'>$komentar</textarea>";
    echo "<span class='fg-secondary fst-italic'>$penulis, $waktu</span>";
    echo "</div><br>";
    echo "<div align='right'>";
    echo "<a onclick='simpanTemplateKomentarSikap($i)' style='color: blue; cursor: pointer; font-weight: normal; font-style: italic;'>";
    echo "<img src='../../images/ico/tambah.png'> simpan komentar sebagai template";
    echo "</a>";
    echo "</div>";
    echo "<br><br>";
    echo "<strong>Pilih Komentar dari Template: </strong><br>";
    echo "<table border='0'><tr><td>";
    echo "<span id='spKomentarSikap$i'>";
    ShowSelectKomentarSikap($db, $idTingkat, $kodeJenis, $i);
    echo "</span>";
    echo "</td><td>";
    echo "<img src='../../images/ico/refresh.png' class='cur-hand' onclick='refreshKomentarSikap($i)'>&nbsp;";
    echo "<input type='button' class='dialogButtonGray' value='pilih' onclick='pilihKomentarSikap($i)'>&nbsp;";
    echo "<input type='button' class='dialogButtonGray' value='kelola' onclick='showKomentarSikapDialog($i)'>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</fieldset><br><br>";
}

?>
<center>
<input type='button' id='btSimpan' value='Simpan' class='dialogButtonPositive w100 h30' onclick='simpan()'>
</center>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>