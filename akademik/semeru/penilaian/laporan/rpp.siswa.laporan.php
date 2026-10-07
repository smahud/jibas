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
require_once('../../library/class/jpgraph.php');
require_once('../../library/class/jpgraph_bar.php');
require_once('../../library/class/jpgraph_line.php');

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
$idJenisPengujian = RequestData("idjenispengujian", 0);
$jenisPengujian = RequestData("jenispengujian", "");
$idRpp = RequestData("idrpp", 0);
$kodeRpp = RequestData("koderpp", "");
$rpp = RequestData("rpp", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Rapor Siswa</title>
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
    <script language="javascript" src="rpp.siswa.laporan.js?r=<?=filemtime('rpp.siswa.laporan.js')?>"></script>
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
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="idjenispengujian" value="<?=$idJenisPengujian?>">
<input type="hidden" id="jenispengujian" value="<?=$jenisPengujian?>">
<input type="hidden" id="idrpp" value="<?=$idRpp?>">
<input type="hidden" id="koderpp" value="<?=$kodeRpp?>">
<input type="hidden" id="rpp" value="<?=$rpp?>">

<?php
echo "<div style='position: relative; width: 99%'>";

echo "<div id='divSectionInfo'>";
echo "<b>$rpp</b><br><i>$kodeRpp</i>";
echo "</div><br>";

echo "<div style='position: absolute; top: 10px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>&nbsp;&nbsp";
echo "<span class='cur-hand fg-secondary' onclick='cetak()' title='cetak'>";
echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
echo "</span>";
echo "</div>";
echo "</div>";

echo "<br>";

$sql = "SELECT s.nis, round(SUM(nilaiujian)/(COUNT(DISTINCT u.replid)),2), s.nama 
          FROM jbsakad.nilaiujian n, jbsakad.siswa s, jbsakad.ujian u, jbsakad.jenisujian j 
         WHERE n.idujian = u.replid 
           AND u.idsemester = '$idSemester' 
           AND u.idkelas = '$idKelas' 
           AND u.idrpp = '$idRpp' 
           AND u.idpelajaran = '$idPelajaran' 
           AND s.nis = n.nis 
           AND u.idjenis = j.replid 
           AND s.idkelas = '$idKelas' 
           AND s.aktif = 1";
if ($idJenisPengujian != 0)            
    $sql .= " AND u.idjenis = '$idJenisPengujian'";
$sql .= " GROUP BY s.nis
          ORDER BY s.nama";

$values = [];
$labels = [];          
$siswas = [];
$no = 0;
$res = $db->QueryDb($sql);
while ($row = mysqli_fetch_row($res))
{
    $no += 1;
    
    $labels[] = $no;
    $values[] = $row[1];
    $siswas[] = [ $row[0], $row[2] ];
}

if (count($labels) == 0)
{
    echo "<i>Belum ada data rerata nilai RPP</i>";
    echo "</div>";
    echo "</body></html>";
    exit();
}

$chartData[] = "barchart";
$chartData[] = "Rata-rata Nilai RPP $kodeRpp";
$chartData[] = "Siswa";
$chartData[] = "Rerata";
$chartData[] = $values;
$chartData[] = $labels;

$data =  base64_encode(json_encode($chartData));

echo "<div id='dvContent'>";

echo "<div style='width: 100%; text-align: center'>";
echo "<img src='../../library/barchart.factory.php?data=$data'>";    
echo "</div>";
echo "<br>";

echo "<div style='width: 100%; text-align: center'>";
echo "<table class='tab tabShadow' id='tableNilai' border='1' width='50%' cellpadding='3' align='center'>";
echo "<tr height='25' align='left'>";
echo "<td width='10%' class='bg-table-header' align='center'>No</td>";
echo "<td width='*' class='bg-table-header' align='left'>Siswa</td>";
echo "<td width='20%' class='bg-table-header' align='center'>Rerata</td>";
echo "</tr>";

$cnt = 0;
foreach ($siswas as $i => $siswa)
{
    $cnt += 1;

    $nis = $siswa[0];
    $nama = $siswa[1];
    
    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>" . $cnt . "</td>";
    echo "<td align='left' style='position: relative;'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "<span style='position: absolute; right: 5px; top: 5px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "</span>";
    echo "</td>";
    echo "<td align='center' class='ff-courier fs-12 fst-bold'>" . $values[$i] . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

echo "</div>";
?>
</body>
</html>