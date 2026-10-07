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
require_once('../../library/hintinfo.php');
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
    <title>Legger Nilai Rapor</title>
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
    <script language="javascript" src="legger.rapor.content.all.js?r=<?=filemtime('legger.rapor.content.all.js')?>"></script>
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
$stidpel = "";
$pelarr = array();

$sql = "SELECT DISTINCT p.replid, p.nama
          FROM jbsakad.infonap i, jbsakad.pelajaran p
         WHERE i.idpelajaran = p.replid
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'";
if ($idPelajaran != 0)           
{
    $sql .= " AND i.idpelajaran = '$idPelajaran'";
}
$sql .= " ORDER BY p.nama";

$res = $db->QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $pelarr[] = array($row[0], $row[1]);

    if ($stidpel != "") $stidpel .= ",";
    $stidpel .= $row[0];
}
$npel = count($pelarr);

if ($stidpel == "")
{
    HintInfo::ShowCenter("Belum ada nilai rapor");
    exit();
}

$aspekarr = array();

$sql = "SELECT DISTINCT a.dasarpenilaian, d.keterangan
          FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a, jbsakad.dasarpenilaian d
         WHERE i.replid = n.idinfo
           AND n.idaturan = a.replid 	   
           AND a.dasarpenilaian = d.dasarpenilaian
           AND i.idpelajaran IN ($stidpel)  
           AND i.idsemester = '$idSemester' 
           AND i.idkelas = '$idKelas'";
$res = $db->QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $aspekarr[] = array($row[0], $row[1]);
}
$naspek = count($aspekarr);
$colwidth = $naspek == 0 ? "0" : round(600 / $naspek);

$sql = "SELECT aktif
          FROM jbsakad.tahunajaran
         WHERE replid = '$idTahunAjaran'";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$ta_aktif = (int) $row[0];

if ($ta_aktif == 0)
    $sql = "SELECT r.nis, s.nama
              FROM jbsakad.riwayatkelassiswa r, jbsakad.siswa s
             WHERE r.nis = s.nis
               AND r.idkelas = '$idKelas'
             ORDER BY s.nama";
else
    $sql = "SELECT nis, nama
              FROM jbsakad.siswa
             WHERE idkelas = '$idKelas'
               AND aktif = 1
             ORDER BY nama";
$res = $db->QueryDb($sql);

$siswa = array();
while($row = mysqli_fetch_row($res))
{
    $siswa[] = array($row[0], $row[1]);
}
$nsiswa = count($siswa);

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
echo "<td width='300' class='bg-table-header' rowspan='2'>Siswa</td>";
for($i = 0; $i < $naspek; $i++)
{
    echo "<td width='$colwidth' align='center' colspan='2' class='bg-table-header'>" . $aspekarr[$i][1]. "</td>";
}
echo "<td width='100' class='bg-table-header' align='center' rowspan='2'>Rata-Rata<br>Siswa</td>";
echo "</tr>";

$colwidth2 = $colwidth / 2;
echo "<tr>";
for($i = 0; $i < $naspek; $i++)
{
    echo "<td width='$colwidth2' align='center' class='bg-table-header'>Nilai Angka</td>";
    echo "<td width='$colwidth2' align='center' class='bg-table-header'>Nilai Huruf</td>";
}
echo "</tr>";

$npelspan = 3 + 2 * $naspek + 1;
for($p = 0; $p < $npel; $p++)
{
    $idpel = $pelarr[$p][0];
    $nmpel = $pelarr[$p][1];

    // PELAJARAN ROW TITLE
    echo "<tr height='25' >";
    echo "<td align='left' style='background-color: #eee' colspan='$npelspan'><strong>$nmpel</strong></td>";
    echo "</tr>";

    $ratapel = array();
    for($j = 0; $j < $naspek; $j++)
    {
        $ratapel[] = array(0, 0); // totna, divna
    }

    $totratasis = 0;
    $ntotratasis = 0;

    $no = 0;
    for($s = 0; $s < $nsiswa; $s++)
    {
        $no += 1;

        $nis = $siswa[$s][0];
        $nama = $siswa[$s][1];

        echo "<tr height='25'>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left' style='position: relative'>";
        echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
        echo "<span style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "</span>";
        echo "</td>";

        $ratasis = 0;
        $nratasis = 0;
        for($j = 0; $j < $naspek; $j++)
        {
            $asp = $aspekarr[$j][0];

            $na = "";
            $nh = "";
            $komentar = "";

            $sql = "SELECT nilaiangka, nilaihuruf, komentar
                      FROM jbsakad.infonap i, jbsakad.nap n, jbsakad.aturannhb a 
                     WHERE i.replid = n.idinfo 
                       AND n.nis = '$nis' 
                       AND i.idpelajaran = '$idpel' 
                       AND i.idsemester = '$idSemester' 
                       AND i.idkelas = '$idKelas'
                       AND n.idaturan = a.replid 	   
                       AND a.dasarpenilaian = '$asp'";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0)
            {
                $row = mysqli_fetch_row($res);
                $na = $row[0];
                $nh = $row[1];
                $komentar = $row[2];

                $ratasis += $na;
                $nratasis += 1;

                $ratapel[$j][0] += $na;
                $ratapel[$j][1] += 1;
            }
            echo "<td align='center' class='ff-courier fs-14'><strong>$na</strong></td>";
            echo "<td align='center'><strong>$nh</strong></td>";
        }
        $rata = ($nratasis == 0) ? "" : round($ratasis / $nratasis, 2);
        echo "<td align='center' class='ff-courier fs-14'><strong>$rata</strong></td>";
        echo "</tr>";

        if ($nratasis != 0)
        {
            $totratasis += $rata;
            $ntotratasis += 1;
        }
    }

    $valtotratasis = $ntotratasis == 0 ? "" : round($totratasis / $ntotratasis, 2);

    // RATA-RATA PER PELAJARAN
    echo "<tr height='25'>";
    echo "<td colspan='2' class='bg-gray-100' align='right'><i><strong>Rata-Rata $nmpel</strong></i></td>";
    for($j = 0; $j < $naspek; $j++)
    {
        $totratapel = $ratapel[$j][0];
        $nratapel = $ratapel[$j][1];
        $valratapel = $nratapel == 0 ? "" : round($totratapel / $nratapel, 2);
        echo "<td class='bg-gray-100 ff-courier fs-14' align='center'><strong>$valratapel</strong></td>";
        echo "<td class='bg-gray-100' align='center'><strong>&nbsp;</strong></td>";
    }
    echo "<td class='bg-gray-100 ff-courier fs-14' align='center'><strong>$valtotratasis</strong></td>";
    echo "</tr>";

    echo "<tr height='15'>";
    echo "<td colspan='$npelspan' style='background-color: #fff'>&nbsp;</td>";
    echo "</tr>";
}

echo "</table>";

?>
<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>