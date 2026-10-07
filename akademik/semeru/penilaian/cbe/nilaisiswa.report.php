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
require_once('../../library/colorfactory.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../library/userinfo.php');
require_once('../../include/errorhandler.php');
require_once("nilaipel.laporan.func.php");

$departemen = RequestData("departemen", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$jenis = RequestData("jenis", "");
$jumlah = RequestData("jumlah", "");
$idSiswa = RequestData("idsiswa", 0);
$nama = RequestData("nama", "");
$nis = RequestData("nis", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Hasil Ujian Siswa</title>
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
    <script language="javascript" src="nilaisiswa.report.js?r=<?=filemtime('nilaisiswa.report.js')?>"></script>
</head>
<body style="padding: 5px;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="jumlah" value="<?=$jumlah?>">
<input type="hidden" id="jenis" value="<?=$jenis?>">
<input type="hidden" id="idsiswa" value="<?=$idsiswa?>">
<input type="hidden" id="nama" value="<?=$nama?>">
<input type="hidden" id="nis" value="<?=$nis?>">

<?php

$userInfo = UserInfo::Siswa($db, $nis);
if ($userInfo->Exist == false)
{
    echo "<i>Tidak ditemukan data siswa $nis /khnck</i>";
    exit();
}

echo "<div style='position: relative; width: 99%'>";

echo "<div id='divSectionUser'>";
UserInfo::ShowSiswaAvatar($userInfo);
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

if ($idPelajaran == 0)
{
    $sql = "SELECT DISTINCT IFNULL(us.idujianremed, us.idujian)
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND us.nis = '$nis'
               AND p.status in ($jenis) 
             ORDER BY u.tanggal DESC                   
             LIMIT $jumlah";
}
else 
{
    $sql = "SELECT DISTINCT IFNULL(us.idujianremed, us.idujian)
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND us.nis = '$nis'
               AND p.idpelajaran = '$idPelajaran'
               AND p.status in ($jenis) 
             ORDER BY u.tanggal DESC                   
             LIMIT $jumlah";
}

$idUjianList = "";
$res = $db->QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    if ($idUjianList != "") $idUjianList .= ",";
    $idUjianList .= $row[0];
}

if ($idUjianList == "")
{
    echo "Belum ada data nilai ujian";
    exit();
}

$stIdUjian = "";
$lsIdUjian = [];
$sql = "SELECT DISTINCT u.id, u.judul, IFNULL(u.idremedujian, 0) AS idremedujian, p.status,
               u.skalanilai, u.kkm 
          FROM jbscbe.ujian u, jbscbe.pengujian p
         WHERE u.idpengujian = p.id
           AND u.id IN ($idUjianList)
         ORDER BY u.tanggal DESC";
$res = $db->QueryDb($sql);
while($row = mysqli_fetch_row($res))
{
    $idUjian = $row[0];
    $judul = $row[1];
    $idRemedUjian = $row[2];
    $sifat = $row[3];
    $skalaNilai = $row[4];
    $kkm = $row[5];

    if ($stIdUjian != "") 
        $stIdUjian .= ",";
    $stIdUjian .= $idUjian;

    $lsIdUjian[] = array($idUjian, $idRemedUjian, $judul, $sifat, $skalaNilai, $kkm);
}

echo "<div id='dvTableContent'>";
echo "<table class='tab tabShadow' id='tableUjian' border='1' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='40' class='bg-table-header' align='center'>No</td>";
echo "<td class='bg-table-header' width='400'>Ujian</td>";
echo "<td class='bg-table-header' width='70' align='center'>Nilai</td>";
echo "<td class='bg-table-header' width='70' align='center'>Status</td>";
echo "<td class='bg-table-header' width='70' align='center'>Benar</td>";
echo "<td class='bg-table-header' width='70' align='center'>Salah</td>";
echo "<td class='bg-table-header' width='80' align='center'>Waktu</td>";
echo "</tr>";

$jumlahData = count($lsIdUjian);
for($i = 0; $i < $jumlahData; $i++)
{
    $no = $i + 1;

    $data = $lsIdUjian[$i];
    $idUjian = $data[0];
    $idRemedUjian = $data[1];
    $judul = $data[2];
    $sifatUjian = $data[3];
    $skalaNilai = $data[4];
    $kkm = $data[5];

    $cf = new ColorFactory(0, $skalaNilai);

    $idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

    $sql = "SELECT COUNT(id) 
              FROM jbscbe.ujianserta
             WHERE idujian = $idUjianInUjianSerta
               AND nis = '$nis'
               AND idujianremed IS NOT NULL";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $haveRemed = $row[0] != 0;

    $sql = "SELECT u.id, u.jbenar, u.jsalah, u.tbobot, u.tnilai, 
                   u.nilai, u.elapsed, u.idujian, 
                   IFNULL(u.idujianremed, 0) AS idujianremed,
                   DATE_FORMAT(u.tanggal, '%d-%m-%Y %H:%i') AS tanggal, u.status,
                   DATE_FORMAT(u.tanggal, '%Y%m%d%H%i') AS tanggalsort     
              FROM jbscbe.ujianserta u, jbsakad.siswa sp 
             WHERE u.nis = sp.nis
               AND sp.nis = '$nis'
               AND u.idujian = '$idUjianInUjianSerta'
               AND u.status IN (1,2)";

    if ($sifatUjian == 1)
    {
        if ($idRemedUjian != 0)
        {
            // Bila yang dipilih ujian remedial, maka nilai terakhir adalah hasil remedial
            $sql .= " AND u.idujianremed = '$idUjian'";
        }
        else
        {
            // Bila yang dipilih ujian awal, maka nilai ditampilkan adalah 
            //  bila ada remedial, maka nilai awal 
            $lastData = $haveRemed ? 0 : 1;
            $sql .= " AND u.lastdata = '$lastData'";
        }
    }

    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $jbenar = $row['jbenar'];
    $jsalah = $row['jsalah'];
    $nilai  = $row['nilai'];
    $elapsed = $row['elapsed'];
    $tanggal = $row['tanggal'];
    $status = $row['status'];

    $namaStatusNilai = NamaStatusNilai($nilai, $kkm, $status);
    $nilaiColor = $cf->GetColorCode($nilai);

    echo "<tr height='25' align='left' valign='center'>";
    echo "<td align='center' class='bg-table-number-column'>$no</td>";
    echo "<td>";
    echo "<b>$judul</b><br><span class='fg-secondary'>$tanggal</span>";
    echo "</td>";
    echo "<td align='center' style='background-color: $nilaiColor; color: white;'>$nilai</td>";
    echo "<td align='center'>$namaStatusNilai</td>";
    echo "<td align='center'>$jbenar</td>";
    echo "<td align='center'>$jsalah</td>";
    echo "<td align='center'>$elapsed menit</td>";
    echo "</tr>";
}

echo "</table>";
echo "</div>";

?>

</body>
</html>