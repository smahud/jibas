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

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Legger Nilai</title>
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
    <script language="javascript" src="legger.nilai.content.js?r=<?=filemtime('legger.nilai.content.js')?>"></script>
</head>
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

<?php
$info = array();
$sql = "SELECT DISTINCT u.idjenis, j.jenisujian
          FROM jbsakad.ujian u, jbsakad.jenisujian j
         WHERE u.idjenis = j.replid
           AND u.idpelajaran = '$idPelajaran'
           AND u.idkelas = '$idKelas'
           AND u.idsemester = '$idSemester'
         ORDER BY j.jenisujian";
$res = $db->QueryDb($sql);
$njenis = 0;
while($row = mysqli_fetch_row($res))
{
    $idjenis = $row[0];
    $namajenis = $row[1];
    
    $sql = "SELECT replid, DAY(tanggal), MONTH(tanggal)
              FROM jbsakad.ujian
             WHERE idpelajaran = '$idPelajaran'
               AND idkelas = '$idKelas'
               AND idsemester = '$idSemester'
               AND idjenis = '$idjenis'
             ORDER BY tanggal";
    $res2 = $db->QueryDb($sql);
    $nujian = mysqli_num_rows($res2);
    $idujian = "";
    $tglujian = "";
    while($row2 = mysqli_fetch_row($res2))
    {
        if ($idujian != "")
            $idujian .= "#";
        $idujian .= $row2[0];
        
        if ($tglujian != "")
            $tglujian .= "#";
        $tglujian .= $row2[1] . "/" . $row2[2];    
    }
    
    $info[$njenis][0] = $idjenis;
    $info[$njenis][1] = $namajenis;
    $info[$njenis][2] = $nujian;
    $info[$njenis][3] = $idujian;
    $info[$njenis][4] = $tglujian;
    
    $njenis += 1;
}

$sql = "SELECT aktif
          FROM jbsakad.tahunajaran
         WHERE replid = '$idTahunAjaran'";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta_aktif = (int)$row[0];

if ($ta_aktif == 0)
    $sql = "SELECT r.nis, s.nama
              FROM jbsakad.riwayatkelassiswa r, jbsakad.siswa s
             WHERE r.nis = s.nis
               AND r.idkelas = '$idKelas'
             ORDER BY nama";
else
    $sql = "SELECT nis, nama
              FROM jbsakad.siswa
             WHERE idkelas = '$idKelas'
               AND aktif = 1
             ORDER BY nama";

$res = $db->QueryDb($sql);
$siswa = array();
$nsiswa = 0;
while($row = mysqli_fetch_row($res))
{
    $siswa[$nsiswa][0] = $row[0];
    $siswa[$nsiswa][1] = $row[1];
    $nsiswa += 1;
}

$allwidth = 30 + 350;
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    $allwidth += $nujian * 60;
}

echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>&nbsp;&nbsp";
echo "<span class='cur-hand fg-secondary' onclick='cetakExcel()' title='excel'>";
echo "<img src='../../images/ico/excel.png' border='0'>&nbsp;excel";
echo "</span>";

echo "<br><br>";

echo "<table class='tab tabShadow' id='table' cellpadding='2' cellspacing='0' width='$allwidth'>";
echo "<tr>";
echo "<td width='30' class='bg-table-header' rowspan='2' align='center'>No</td>";
echo "<td width='350' class='bg-table-header' rowspan='2'>Siswa</td>";
for($i = 0; $i < $njenis; $i++)
{
    $namajenis = $info[$i][1];
    $nujian = $info[$i][2];
    $width = 60 * $nujian; 
    echo "<td width='$width' colspan='$nujian' align='center' class='bg-table-header'>$namajenis</td>";
}
echo "</tr>";
echo "<tr>";
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    $tglujian = $info[$i][4];
    $arrtgl = explode("#", $tglujian);
    for($j = 0; $j < count($arrtgl); $j++)
    {
        echo "<td width='60' align='center' class='bg-table-header'>" . $arrtgl[$j]. "</td>";
    }
}
echo "</tr>";

$rerata = [];
for($j = 0; $j < $njenis; $j++)
{
    for($u = 0; $u < $nujian; $u++)
    {
        $pos = $j + $u;
        $rerata[] = [0, 0];
    }
}

for($s = 0; $s < $nsiswa; $s++)
{
    $no = $s + 1;
    $nis = $siswa[$s][0];
    $nama = $siswa[$s][1];
    
    echo "<tr height='25'>";
    echo "<td align='center' class='bg-table-number-column'>$no</td>";
    echo "<td align='left' style='position: relative;'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "<span style='position: absolute; right: 5px; top: 5px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "</span>";
    echo "</td>";
    
    for($j = 0; $j < $njenis; $j++)
    {
        $idujian = $info[$j][3];
        $arrujian = explode("#", $idujian);
        $nujian = count($arrujian);
        for($u = 0; $u < $nujian; $u++)
        {
            $id = $arrujian[$u];
            $sql = "SELECT nilaiujian
                      FROM jbsakad.nilaiujian
                     WHERE nis = '$nis'
                       AND idujian = '$id'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                echo "<td align='center' class='ff-courier fs-14'>$row[0]</td>";

                $pos = $j + $u;
                $rerata[$pos][0] += $row[0];
                $rerata[$pos][1] += 1;
            }
            else
            {
                echo "<td align='center'>-</td>";
            }
        }
    }
    echo "</tr>";
}

echo "<tr style='height: 35px;'>";
echo "<td colspan='2' align='right' class='bg-gray-100'><b>Rerata</b></td>";
for($i = 0; $i < $njenis; $i++)
{
    $nujian = $info[$i][2];
    for($j = 0; $j < $nujian; $j++)
    {
        $pos = $i + $j;

        if ($rerata[$pos][1] > 0)
        {
            $rr = round($rerata[$pos][0] / $rerata[$pos][1], 2);
            echo "<td align='center' class='bg-gray-100 ff-courier fs-14'><b>$rr</b></td>";
        }
        else
        {
            echo "<td align='center' class='bg-gray-100 ff-courier fs-14'><b>-</b></td>";
        }
    }
}
echo "</tr>";

echo "</table>";

?>
<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>