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
$bagian = RequestData("bagian", "");

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
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js"></script>
    <script language="javascript" src="rapor.komentar.siswa.js?r=<?=filemtime('rapor.komentar.siswa.js')?>"></script>
</head>
<body style="padding: 5px; background-color: #efefef;">

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
<input type="hidden" id="bagian" value="<?=$bagian?>">

<?php

$sql = "SELECT replid
          FROM jbsakad.infonap
         WHERE idpelajaran = $idPelajaran
           AND idsemester = $idSemester
           AND idkelas = $idKelas";
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    HintInfo::ShowLeft("Tidak ditemukan data nilai rapor untuk pelajaran $pelajaran. Hitung dahulu di menu Penentuan Nilai Rapor", 300);
    echo "</body>";
    echo "</html>";
    exit();
}
$row = mysqli_fetch_row($res);
$idInfoNap = $row[0];

$sql = "SELECT nis, nama
          FROM jbsakad.siswa
         WHERE idkelas = $idKelas 
           AND aktif = 1
         ORDER BY nama";		
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "Tidak ditemukan siswa di kelas $kelas";
    echo "</body>";
    echo "</html>";
    exit();
}

if ($bagian == "nilai")
    echo "<span class='fs-14'>Komentar Nilai Rapor $pelajaran</span><br><br>";
else if ($bagian == "sikap")
    echo "<span class='fs-14'>Komentar Sikap Sosial &amp; Spiritual</span><br><br>";

echo "<span class='ablue cur-hand' onclick=\"showDaftarKomentar()\">Lihat semua komentar</span><br><br>";

echo "<table class='tab tabShadow' id='tableSiswa' border='1' width='100%' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='10%' class='bg-table-header'>No</td>";
echo "<td colspan='2' class='bg-table-header' width='*'>Siswa</td>";
echo "</tr>";

$no = 0;
while ($row = mysqli_fetch_assoc($res))
{
    $no++;
    $nis = $row['nis'];
    $nama = $row['nama'];

    $statusKomentar = "";
    if ($bagian == "nilai")
    {
        $sql = "SELECT IF(komentar IS NULL, 0, IF(TRIM(komentar) = '', 0, 1))
                  FROM jbsakad.nap
                 WHERE nis = '$nis'
                   AND idinfo = $idInfoNap";
        $resKomentar = $db->QueryDb($sql);
        $rowKomentar = mysqli_fetch_row($resKomentar);
        $adaKomentar = $rowKomentar[0];
        $statusKomentar = $adaKomentar == 1 ? "&check;" : "&nbsp;";
    }
    else if ($bagian == "sikap")
    {
        $sql = "SELECT 1
                  FROM jbsakad.komenrapor
                 WHERE idkelas = $idKelas
                   AND nis = '$nis'
                   AND idsemester = $idSemester
                   AND (jenis = 'SOS' OR jenis = 'SPI') ";
        $resKomentar = $db->QueryDb($sql);
        $adaKomentar = mysqli_num_rows($resKomentar) > 0 ? 1 : 0;
        $statusKomentar = $adaKomentar == 1 ? "&check;" : "&nbsp;";
    }
    
    echo "<tr>";
    echo "<td width='10%' class='bg-table-number-column' align='center'>$no</td>";
    echo "<td width='*' class='cur-hand' onclick='showInputKomentar(\"$nis\", \"$nama\")'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "</td>";
    echo "<td width='10%' class='bg-light-purple fg-secondary fst-bold' align='center'>$statusKomentar</td>";
    echo "</tr>";
}
echo "</table>";

?>

</body>
</html>