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
require_once('rapor.komentar.form.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");    
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
    <script language="javascript" src="rapor.komentar.form.js?r=<?=filemtime('rapor.komentar.form.js')?>"></script>
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
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="nis" value="<?=$nis?>">
<input type="hidden" id="nama" value="<?=$nama?>">

<?php
echo "<div style='position: relative; width: 99%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='250' valign='top'>";
echo "<span class='fg-secondary'>Pelajaran</span><br>";
echo "<span class='fs-16 fs-bold'>" . $pelajaran . "</span>";
echo "</td>";
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

$sql = "SELECT nilaimin 
          FROM jbsakad.infonap
         WHERE idpelajaran = $idPelajaran
           AND idsemester = $idSemester
           AND idkelas = $idKelas";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$nilaiMin = $row[0];

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a, jbsakad.dasarpenilaian d
         WHERE i.replid = n.idinfo 
           AND n.nis = '$nis' 
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'
           AND n.idaturan = a.replid 	   
           AND a.dasarpenilaian = d.dasarpenilaian
           AND d.aktif = 1";
$res = $db->QueryDb($sql);
$aspekarr = [];
while($row = mysqli_fetch_row($res))
{
    $aspekarr[] = [ $row[0], $row[1] ];
}
$naspek = count($aspekarr);
echo "<input type='hidden' id='naspek' name='naspek' value='$naspek'>";

for($i = 0; $i < $naspek; $i++)
{
    $kdaspek = $aspekarr[$i][0];
    $nmaspek = strtoupper($aspekarr[$i][1]);

    $sql = "SELECT n.nilaiangka, n.nilaihuruf, n.replid, n.komentar, n.penulis, DATE_FORMAT(n.waktu, '%d-%b-%Y %H:%i') AS fwaktu
              FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a 
             WHERE i.replid = n.idinfo 
               AND n.idaturan = a.replid 
               AND n.nis = '$nis' 
               AND i.idpelajaran = '$idPelajaran' 
               AND i.idsemester = '$idSemester' 
               AND i.idkelas = '$idKelas'	   
               AND a.dasarpenilaian = '$kdaspek'";
    $res = $db->QueryDb($sql);
    $nilaiExist = false;
    $na = "";
    $nh = "";
    $idnap = 0;
    $nkomentar = "";
    $penulis = "";
    $waktu = "";
    if (mysqli_num_rows($res) > 0)
    {
        $row = mysqli_fetch_row($res);
        $na = $row[0];
        $nh = $row[1];
        $idnap = $row[2];
        $nkomentar = $row[3];
        $penulis = $row[4];
        $waktu = $row[5];
        $nilaiExist = true;
    }

    echo "<br>";
    echo "<fieldset style='width: 97%; border: 1px solid #ccc; border-radius: 5px; padding: 15px; background-color: #ffffe9ff;' class='tabShadow'>";
    echo "<legend class='fs-14 fg-secondary'>Aspek <b>$nmaspek</b></legend>";
    echo "<input type='hidden' id='kdaspek$i' name='kdaspek$i' value='$kdaspek'>";
    echo "<input type='hidden' id='nmaspek$i' name='nmaspek$i' value='$nmaspek'>";
    echo "<input type='hidden' id='idnap$i' name='idnap$i' value='$idnap'>";
    echo "<input type='hidden' id='na$i' name='na$i' value='$na'>";

    echo "<table border='0' cellpadding='10' cellspacing='0' width='100%'>";
    echo "<tr>";
    echo "<td width='20%' valign='top'>";

    echo "<b>Nilai Rapor:</b><br>";
    echo "<table id='tableAspek$i' class='tab tabShadow' cellpadding='5' cellspacing='0' border='0' width='100%'>";
    echo "<tr style='height: 45px'>";
    echo "<td style='background-color: #e5f6ff' width='60%'>Nilai Angka</td>";
    echo "<td style='font-size: 12px;' align='center' width='40%'><strong>$na</strong></td>";
    echo "</tr>";
    echo "<tr style='height: 45px'>";
    echo "<td style='background-color: #e5f6ff'>Nilai Huruf</td>";
    echo "<td style='font-size: 12px;' align='center'><strong>$nh</strong></td>";
    echo "</tr>";
    echo "<tr style='height: 45px'>";
    echo "<td style='background-color: #e5f6ff'>Nilai KKM</td>";
    echo "<td style='font-size: 12px' align='center'><strong>$nilaiMin</strong></td>";
    echo "</tr>";
    echo "</table>";

    echo "</td>";
    echo "<td width='70%' valign='top'>";

    echo "<b>Komentar Rapor:</b><br>";
    echo "<div style='padding: 2px;' class='tabShadow bg-white'>";
    echo "<textarea name='komentar$i' id='komentar$i' rows='3'>$nkomentar</textarea>";
    echo "<span class='fg-secondary fst-italic'>$penulis, $waktu</span>";
    echo "</div><br>";
    echo "<div align='right'>";
    echo "<a onclick='simpanTemplateKomentarPelajaran($i)' style='color: blue; cursor: pointer; font-weight: normal; font-style: italic;'>";
    echo "<img src='../../images/ico/tambah.png'> simpan komentar sebagai template";
    echo "</a>";
    echo "</div>";
    echo "<br><br>";
    echo "<strong>Pilih Komentar dari Template: </strong><br>";
    echo "<table border='0'><tr><td>";
    echo "<span id='spKomentarPelajaran$i'>";
    ShowSelectKomentarPelajaran($db, $idPelajaran, $idTingkat, $kdaspek, $i);
    echo "</span>";
    echo "</td><td>";
    echo "<img src='../../images/ico/refresh.png' class='cur-hand' onclick='refreshKomentarPelajaran($i)'>&nbsp;";
    echo "<input type='button' class='dialogButtonGray' value='pilih' onclick='pilihKomentarPelajaran($i)'>&nbsp;";
    echo "<input type='button' class='dialogButtonGray' value='kelola' onclick='showKomentarPelajaranDialog($i)'>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</fieldset><br><br>";


}

echo "<center>";
if ($nilaiExist)
    echo "<input type='button' id='btSimpan' value='Simpan' class='dialogButtonPositive w100 h30' onclick='simpan()'>";
else 
    echo "<span class='fg-maroon fst-italic'>Belum dapat menyimpan karena belum ada nilai rapor</span>";
echo "</center>";

?>


<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>