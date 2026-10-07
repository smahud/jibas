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
$harian = RequestData("harian", 0);
$pelajaran = RequestData("pelajaran", 0);
$dd1 = RequestData("dd1", "");
$mm1 = RequestData("mm1", "");
$yy1 = RequestData("yy1", "");
$dd2 = RequestData("dd2", "");
$mm2 = RequestData("mm2", "");
$yy2 = RequestData("yy2", "");

$tglAwal = "$yy1-$mm1-$dd1";
$tglAkhir = "$yy2-$mm2-$dd2";

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
    <script language="javascript" src="rapor.siswa.list.js?r=<?=filemtime('rapor.siswa.list.js')?>"></script>
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
<input type="hidden" id="harian" value="<?=$harian?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="dd1" value="<?=$dd1?>">
<input type="hidden" id="mm1" value="<?=$mm1?>">
<input type="hidden" id="yy1" value="<?=$yy1?>">
<input type="hidden" id="dd2" value="<?=$dd2?>">
<input type="hidden" id="mm2" value="<?=$mm2?>">
<input type="hidden" id="yy2" value="<?=$yy2?>">
<input type="hidden" id="tglawal" value="<?=$tglAwal?>">
<input type="hidden" id="tglakhir" value="<?=$tglAkhir?>">

<?php
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

echo "<span class='fs-14'>Nilai Rapor Siswa Kelas $kelas</span><br><br>";

echo "<span class='ablue cur-hand' onclick=\"showCetakRaporKelas()\">Cetak Rapor Kelas (Word)</span><br><br>";

echo "<table class='tab tabShadow' id='tableSiswa' border='1' width='100%' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='10%' class='bg-table-header'>No</td>";
echo "<td class='bg-table-header' width='*'>Siswa</td>";
echo "</tr>";

$no = 0;
while ($row = mysqli_fetch_assoc($res))
{
    $no++;
    $nis = $row['nis'];
    $nama = $row['nama'];
    
    echo "<tr>";
    echo "<td width='10%' class='bg-table-number-column' align='center'>$no</td>";
    echo "<td width='*' class='cur-hand' onclick='showLaporanRapor(\"$nis\", \"$nama\")'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

?>

</body>
</html>