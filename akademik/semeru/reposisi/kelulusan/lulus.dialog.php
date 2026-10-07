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
require_once('../data/keu.func.php');

$db = new Db();
$db->TryOpenExit();

$departemen = RequestData("departemen","");
$idTingkat = RequestData("idtingkat","");

$nSiswa = RequestData("nsiswa","");
$lsNis64 = RequestData("lsnis64","");
$lsSiswa = json_decode(base64_decode($lsNis64));

$departemenTujuan = RequestData("departementujuan","");
$angkatanTujuan = RequestData("angkatantujuan","");
$idAngkatanTujuan = RequestData("idangkatantujuan","");
$idTahunAjaranTujuan = RequestData("idtahunajarantujuan","");
$tahunAjaranTujuan = RequestData("tahunajarantujuan","");
$idTingkatTujuan = RequestData("idtingkattujuan","");
$tingkatTujuan = RequestData("tingkattujuan","");
$idKelasTujuan = RequestData("idkelastujuan","");
$kelastujuan = RequestData("kelastujuan","");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kelulusan Siswa</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js?<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="lulus.dialog.js?<?=filemtime('lulus.dialog.js')?>"></script>
</head>
<body>
<input type="hidden" id="nsiswa" value="<?= $nSiswa ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idtingkat" value="<?= $idTingkat ?>">
<input type="hidden" id="departementujuan" value="<?= $departemenTujuan ?>">
<input type="hidden" id="idangkatantujuan" value="<?= $idAngkatanTujuan ?>">
<input type="hidden" id="angkatantujuan" value="<?= $angkatanTujuan ?>">
<input type="hidden" id="idtingkattujuan" value="<?= $idTingkatTujuan ?>">
<input type="hidden" id="tingkattujuan" value="<?= $tingkatTujuan ?>">
<input type="hidden" id="idkelastujuan" value="<?= $idKelasTujuan ?>">
<input type="hidden" id="kelastujuan" value="<?= $kelastujuan ?>">
<input type="hidden" id="idtahunajarantujuan" value="<?= $idTahunAjaranTujuan ?>">
<input type="hidden" id="tahunajarantujuan" value="<?= $tahunAjaranTujuan ?>">

<span class="dialogTitle">Kelulusan Siswa</span><br><br>

<span class="fs-12 fst-bold">Departemen &amp; Kelas Tujuan</span><br>
<table cellpadding="5" cellspacing="0" style="margin-left: 10px; margin-top: 5px;">
<tr>
    <td style="width: 100px;">Departemen</td>
    <td>
        <b><?= $departemenTujuan ?></b>
    </td>
</tr>
<tr>
    <td>Tahun Ajaran &amp; Angkatan</td>
    <td>
        <b><?= "$tahunAjaranTujuan<br>angkatan $angkatanTujuan" ?></b>
    </td>
</tr>
<tr>
    <td>Tingkat &amp; Kelas</td>
    <td>
        <b><?= "$tingkatTujuan<br>kelas $kelastujuan" ?></b>
    </td>
</tr>
</table>
<br>
<span class="fs-12 fst-bold">Daftar Siswa</span><br>

<?php
echo "<table id='tableSiswa' class='tab tabShadow' width='98%' align='center' style='margin-top: 10px;'>";
echo "<tr align='center'>";
echo "<td class='bg-table-header' width='7%'>No</td>";
echo "<td class='bg-table-header' width='7%'>Lulus</td>";
echo "<td class='bg-table-header' width='*'>Siswa</td>";
echo "<td class='bg-table-header' width='25%'>NIS Baru</td>";
echo "<td class='bg-table-header' width='25%'>Keterangan</td>";
echo "</tr>";
$no = 0;
for($i = 0; $i < count($lsSiswa); $i++)
{
    $no += 1;

    $nis = $lsSiswa[$i][0];
    $nama = $lsSiswa[$i][1];
    $idKelasAwal = $lsSiswa[$i][2];

    $checkKeu = CheckStatusKeuangan($db, $nis);
    $checked = $checkKeu ? "checked" : "";

    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>$no</td>";
    echo "<td align='center'>";
    echo "<input type='checkbox' id='checklulus$no' class='inputbox' $checked>";
    echo "</td>";
    echo "<td style='position: relative;'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "&nbsp;&nbsp;<img src='../../images/ico/copy.png' class='cur-hand' title='salin nis' onclick='salinNis($no)'>";
    echo "<input type='hidden' id='nis$no' value='$nis'>";
    echo "<input type='hidden' id='nama$no' value='$nama'>";
    echo "<input type='hidden' id='idkelasawal$no' value='$idKelasAwal'>";
    if (!$checkKeu)
        echo "<img src='../../images/ico/alert.png' style='position: absolute; right: 5px; top: 10px; height: 20px;' class='cur-hand' title='ada iuran belum lunas' onclick='showIuran($no)'>";
    echo "</td>";
    echo "<td align='center'>";
    echo "<input type='text' id='nisbaru$no' class='inputbox' style='width: 140px;'>";
    echo "</td>";
    echo "<td align='center'>";
    echo "<input type='text' id='keterangan$no' class='inputbox' style='width: 140px;'>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
?>

<br>
<div style="margin-top: 10px; width: 98%; text-align: center;">
    <input type='button' id="btLulus" class='dialogButtonPositive' value='Luluskan' style='width: 100px' onclick='lulusSiswa()'>
    <input type='button' id="btBatal" class='dialogButtonNegative' value='Batal' style='width: 100px' onclick='window.close()'>
</div>


<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
