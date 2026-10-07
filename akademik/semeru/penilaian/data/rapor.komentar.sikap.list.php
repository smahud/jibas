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
    <title>Daftar Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../style/tinymce-small-toolbar.css">
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
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">

<?php
echo "<div style='position: relative; width: 99%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='180' valign='top'>";
echo "<span class='fg-secondary'>Kelas</span><br>";
echo "<span class='fs-16 fs-bold'>" . $kelas . "</span><br>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<div style='position: absolute; top: 10px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>&nbsp;&nbsp;";
echo "<span class='cur-hand fg-secondary' onclick='cetak()' title='cetak'>";
echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
echo "</span>";
echo "</div>";
echo "</div>";

echo "<br>";

$arrjenis = array("SPI", "SOS");
$arrnmjenis = array("Spiritual", "Sosial");
$arrbg = array("#e8ffd4ff", "#dcf4ffff");
$njenis = count($arrjenis);

$sql = "SELECT nis, nama
          FROM jbsakad.siswa
         WHERE idkelas = $idKelas 
           AND aktif = 1
         ORDER BY nama";		
$res = $db->QueryDb($sql);
$siswaarr = [];
while($row = mysqli_fetch_row($res))
{
    $siswaarr[] = [ $row[0], $row[1] ];
}
$nsiswa = count($siswaarr);
if ($nsiswa == 0)
{
    echo "Siswa tidak ditemukan";
    exit();
}

$width = round(75 / $njenis);

echo "<div id='dvTableDaftar'>";
echo "<table class='tab tabShadow' id='tableDaftar' border='1' width='100%' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='5%' class='bg-table-header' align='center'>No</td>";
echo "<td width='20%' class='bg-table-header' align='center'>Siswa</td>";
for($i = 0; $i < $njenis; $i++)
{
    echo "<td width='$width%' class='bg-table-header' align='center'>" . $arrnmjenis[$i] . "</td>";
}
echo "</tr>";

for($s = 0; $s < $nsiswa; $s++)
{
    $nis = $siswaarr[$s][0];
    $nama = $siswaarr[$s][1];

    $no = $s + 1;
    echo "<tr height='25' align='left'>";
    echo "<td valign='top' align='center' class='bg-table-number-column'>$no</td>";
    echo "<td valign='top' align='left'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "</td>";
    for($i = 0; $i < $njenis; $i++)
    {
        $kodeJenis = $arrjenis[$i];
        
        $predikat = -1;
        $komentar = "";

        $sql = "SELECT predikat, komentar
                  FROM jbsakad.komenrapor
                 WHERE nis = '$nis'
                   AND idsemester = '$idSemester'
                   AND idkelas = '$idKelas'
                   AND jenis = '$kodeJenis'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $predikat = $row[0];
            $komentar = $row[1];
        }
        

        echo "<td valign='top' align='left'>";
        if ($predikat != -1)
            echo "Predikat: <b>" . NamaPredikat($predikat) . "</b><br>";
        echo $komentar;
        echo "</td>";
    }    
    echo "</tr>";
}
echo "</table>";
echo "</div>";  

?>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>