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
require_once('inputpp.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$jam = RequestData("jam", "");
$menit = RequestData("menit", "");
$tanggal = RequestData("tanggal", "");
$waktu = str_pad($jam, 2, "0", STR_PAD_LEFT) . ":". str_pad($menit, 2, "0", STR_PAD_LEFT) . ":00";

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Input Presensi Harian</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="inputpp.content.js?<?=filemtime('inputpp.content.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="jam" value="<?=$jam?>">
<input type="hidden" id="menit" value="<?=$menit?>">
<input type="hidden" id="tanggal" value="<?=$tanggal?>">
<input type="hidden" id="waktu" value="<?=$waktu?>">

<?php
$sql = "SELECT p.replid, p.gurupelajaran, p.keterangan, p.materi, p.objektif, p.refleksi, p.rencana, p.keterlambatan, 
               p.jumlahjam, p.jenisguru, p.idkelas, p.idsemester, p.idpelajaran, p.tanggal, g.nama, k.idtahunajaran, p.jam 
          FROM jbsakad.presensipelajaran p, jbssdm.pegawai g, jbsakad.kelas k 
         WHERE k.replid = '$idKelas' 
           AND p.idsemester='$idSemester' 
           AND p.idpelajaran='$idPelajaran' 
           AND p.tanggal = '$tanggal' 
           AND p.jam = '$waktu' 
           AND g.nip = p.gurupelajaran 
           AND p.idkelas = k.replid";	   	
$idPresensi = 0;
$nip = "";
$nama = "";
$keterangan = "";
$refleksi = "";
$materi = "";
$idStatusGuru = 0;
$jumlahJam = 0;
$keterlambatan = 0;

$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_assoc($res)) 
{
    $idPresensi = $row["replid"];
    $nip = $row["gurupelajaran"];
    $nama = $row["nama"];
    $keterangan = $row["keterangan"];
    $refleksi = $row["refleksi"];
    $materi = $row["materi"];
    $isStatusGuru = $row["jenisguru"];
    $jumlahJam = $row["jumlahjam"];
    $keterlambatan = $row["keterlambatan"];
    $title = "Ubah Presensi Pelajaran";

    $sqlSiswa = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif 
                   FROM jbsakad.siswa s, jbsakad.ppsiswa p, jbsakad.kelas k 
                  WHERE p.idpp = '$idPresensi' 
                    AND p.nis = s.nis 
                    AND s.idkelas = k.replid 
                  ORDER BY s.nama";
}
else 
{
    $title = "Pendataan Presensi Pelajaran";
    $sqlSiswa = "SELECT s.nis, s.nama, s.idkelas, k.kelas, s.aktif 
                   FROM jbsakad.siswa s, jbsakad.kelas k 
                  WHERE s.idkelas = '$idKelas' 
                    AND s.aktif = 1 
                    AND s.alumni = 0 
                    AND k.replid = s.idkelas 
                  ORDER BY s.nama";
}
?>

<table border="0" cellpadding="5" cellspacing="0" width="70%" align="center">
<tr>
    <td colspan="2">
        <span class="fs-18 fs-bold"><?= $title ?></span><br><br>
        <input type="hidden" id="idpresensi" value="<?=$idPresensi ?>">
    </td>
</tr>    
<tr>
    <td style="width: 100px;">Guru <?= $tag_mandatory ?></td>
    <td>
        <input id="nipguru" type="text" class="inputbox_readonly"  onclick="showSearchGuru()" style="width: 100px" readonly value="<?= $nip ?>">
        <input id="namaguru" type="text" class="inputbox_readonly"  onclick="showSearchGuru()" style="width: 180px" readonly value="<?= $nama ?>">
        <input type="button" class="dialogButtonGray" value="..." onclick="showSearchGuru()">
    </td>
</tr>
<tr>
    <td style="width: 100px;">Status Guru <?= $tag_mandatory ?></td>
    <td>
<?php
        ShowSelectStatusGuru($db);
?>
    </td>
</tr>
<tr>
	<td>Jumlah Jam Mengajar <?= $tag_mandatory ?></td>
    <td><input type="text" name="jumlah" id="jumlah" class="inputbox" size="3" maxlength="4" value="<?=$jumlahJam ?>"> jam</td>
</tr>
<tr>
	<td>Keterlambatan</td>
    <td><input type="text" name="telat" id="telat" class="inputbox" size="3" maxlength="4" value="<?=$keterlambatan ?>"/> menit</td>
</tr>
<tr>
	<td>Materi <?= $tag_mandatory ?></td>
    <td>
        <textarea name="materi" id="materi" class="inputbox" rows="3" cols="60"><?=$materi ?></textarea>
    </td>
</tr>
<tr>
	<td>Refleksi</td>
    <td>
        <textarea name="refleksi" id="refleksi" class="inputbox" rows="3" cols="60"><?= $refleksi ?></textarea>
    </td>
</tr>
<tr>
	<td>Keterangan</td>
    <td>
        <textarea name="keterangan" id="keterangan" class="inputbox" rows="2" cols="60"><?= $keterangan ?></textarea>
    </td>
</tr>
</table>
<br>

<?php
echo "<table class='tab tabShadow' id='table' width='70%' align='center'>";
echo "<tr height='30' class='header'>";
echo "<td width='5%' align='center'>No</td>";
echo "<td width='*'>Siswa</td>";
echo "<td width='8%' align='center'>Hadir</td>";
echo "<td width='8%' align='center'>Ijin</td>";
echo "<td width='8%' align='center'>Sakit</td>";
echo "<td width='8%' align='center'>Alpa</td>";
echo "<td width='8%' align='center'>Cuti</td>";
echo "<td width='20%' align='center'>Keterangan</td>";
echo "</tr>";

$cnt = 0;
$res = $db->QueryDb($sqlSiswa);
while($row = mysqli_fetch_array($res))
{
    $cnt += 1;

    $nis = $row["nis"];
    $nama = $row["nama"];

    $idPp = 0;
    $statusHadir = 0;
    $catatan = "";

    $sql = "SELECT replid, statushadir, catatan 
              FROM jbsakad.ppsiswa 
             WHERE idpp = '$idPresensi' 
               AND nis = '$nis'";
    $res2 = $db->QueryDb($sql);
    if ($row2 = mysqli_fetch_array($res2))
    {
        $idPp = $row2["replid"];
        $statusHadir = $row2["statushadir"];
        $catatan = $row2["catatan"];
    }

    $radioName = "status$cnt";

    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>" . $cnt . "</td>";
    echo "<td style='position: relative;'>";
    echo "<b>$nama<b><br><span class='fg-secondary'>$nis</span>";
    echo "<input type='hidden' id='idpp$cnt' value='$idPp'>";
    echo "<input type='hidden' id='nis$cnt' value='$nis'>";
    echo "<span style='position:absolute; right: 10px; top: 8px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='showInfoSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$nis\")'>";
    echo "</span>";
    echo "</td>";

    $checked = $statusHadir == 0 ? "checked" : "";
    $bgColor = $statusHadir == 0 ? "#d0eefc" : "#ffffff";
    echo "<td align='center' id='row0$cnt' style='background-color: $bgColor;'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='0' $checked onclick='onStatusChanged($cnt, 0, \"#d0eefc\")'></td>";

    $checked = $statusHadir == 1 ? "checked" : "";
    $bgColor = $statusHadir == 1 ? "#caf1d4" : "#ffffff";
    echo "<td align='center' id='row1$cnt' style='background-color: $bgColor;'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='1' $checked onclick='onStatusChanged($cnt, 1, \"#caf1d4\")'></td>";

    $checked = $statusHadir == 2 ? "checked" : "";
    $bgColor = $statusHadir == 2 ? "#ecd9ea" : "#ffffff";
    echo "<td align='center' id='row2$cnt' style='background-color: $bgColor;'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='2' $checked onclick='onStatusChanged($cnt, 2, \"#ecd9ea\")'></td>";

    $checked = $statusHadir == 3 ? "checked" : "";
    $bgColor = $statusHadir == 3 ? "#e9caca" : "#ffffff";
    echo "<td align='center' id='row3$cnt' style='background-color: $bgColor;'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='3' $checked onclick='onStatusChanged($cnt, 3, \"#e9caca\")'></td>";

    $checked = $statusHadir == 4 ? "checked" : "";
    $bgColor = $statusHadir == 4 ? "#ebe2ce" : "#ffffff";
    echo "<td align='center' id='row4$cnt' style='background-color: $bgColor;'><input type='radio' style='transform: scale(1.35)' name='$radioName' value='4' $checked onclick='onStatusChanged($cnt, 4, \"#ebe2ce\")'></td>";

    echo "<td align='center'><input type='text' class='inputbox' id='catatan$cnt' value='$catatan'></td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<input type='hidden' id='nsiswa' value='$cnt'>";

echo "<table border='0' width='70%' align='center'>";
echo "<tr>";
echo "<td align='right'>";
echo "<input type='button' id='btSimpan' class='dialogButtonPositive' style='width: 100px; margin-right: 10px;' value='Simpan' onclick='simpan()'>";
if ($idPresensi != 0)
    echo "<input type='button' id='btHapus' class='dialogButtonNegative' style='width: 100px;' value='Hapus' onclick='hapus()'>";
echo "</td></tr></table>";
?>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>