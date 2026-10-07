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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/hintinfo.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('rekapinput.riwayat.func.php');
require_once('rekapinput.rekap.func.php');

$departemen = $_REQUEST["departemen"];
$rentang = $_REQUEST["rentang"];
$keyword = $_REQUEST["keyword"];

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Rekapitulasi Input Nilai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rekapinput.rekap.js?<?=filemtime('rekapinput.rekap.js')?>"></script>
</head>
<body>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="rentang" value="<?=$rentang?>">
<input type="hidden" id="keyword" value="<?=$keyword?>">

<?php
$whereKeyword = "";
if (!empty($keyword))
    $whereKeyword = " AND MATCH(deskripsi) AGAINST ('$keyword' IN BOOLEAN MODE) "; 

$sql = "SELECT COUNT(ri.id)
          FROM jbsjs.riwayatinput ri
          LEFT JOIN jbssdm.pegawai pg ON ri.userid = pg.nip
         WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL $rentang DAY) AND CURDATE() 
           AND departemen = '$departemen' $whereKeyword
         ORDER BY ri.waktu DESC";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$totalData = $row[0];
if ($totalData == 0)
{
    HintInfo::ShowCenter("Belum tersedia data Rekapitulasi Input Nilai");
    exit;
}
echo "<input type='hidden' id='totaldata' value='$totalData'>";

echo "<div style='margin-left: 40px'>";

echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../images/ico/refresh.png' border='0'/>&nbsp;refresh";
echo "</span>&nbsp;&nbsp;";
echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
echo "<img src='../images/ico/print.png' border='0'/>&nbsp;cetak";
echo "</span>";

echo "</div>";
echo "<br>";

echo "<div id='dvTableContent'>";

$lsUser = [];
$sql = "SELECT DISTINCT IFNULL(ri.userid, 'jibas') AS fuserid, IFNULL(pg.nama, 'Adminisrator JIBAS') AS fusername
          FROM jbsjs.riwayatinput ri
          LEFT JOIN jbssdm.pegawai pg ON ri.userid = pg.nip
         WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL $rentang DAY) AND CURDATE()
           AND departemen = '$departemen' $whereKeyword
         ORDER BY fusername";
$res = $db->QueryDb($sql);
while ($row = mysqli_fetch_row($res))
{
    $lsUser[] = [$row[0], $row[1]];
}

$sql = "SELECT DISTINCT kategori
          FROM jbsjs.riwayatinput ri
         WHERE tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL $rentang DAY) AND CURDATE() 
           AND departemen = '$departemen' $whereKeyword";
$res = $db->QueryDb($sql);
$lsKategori = [];
while ($row = mysqli_fetch_row($res))
{
    $lsKategori[] = $row[0];
}
$nKategori = count($lsKategori);
$tableWidth = 30 + 180 + ($nKategori * 120);

echo "<table class='tab tabShadow' id='table' style='width:" . $tableWidth . "px'>";
echo "<tr>";
echo "<td class='header' style='width:30px' align='center'>No</td>";
echo "<td class='header' style='width:180px' align='left'>Petugas</td>";
for ($i = 0; $i < count($lsKategori); $i++)
{
    $kategori = $lsKategori[$i];
    echo "<td class='header' style='width:120px' align='center'>";
    echo "<b>$kategori</b><br>";
    echo "<span class='fs-10 fst-normal'>" . NamaKategori($kategori) . "</span>";
    echo "</td>";
}
echo "</tr>";
for ($i = 0; $i < count($lsUser); $i++)
{
    $nip = $lsUser[$i][0];
    $nama = $lsUser[$i][1];
    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>" . ($i + 1) . "</td>";
    echo "<td align='left'`><b>" . $nama . "</b><br><span class='fg-secondary'>" . $nip . "</span></td>";
    for ($j = 0; $j < count($lsKategori); $j++)
    {
        $kategori = $lsKategori[$j];
        echo "<td align='center' class='ff-courier fs-16'>" . GetCountInput($db, $nip, $kategori) . "</td>";
    }
    echo "</tr>";
}
echo "</table>";

echo "</div>";
echo "<br>";


?>


<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>
