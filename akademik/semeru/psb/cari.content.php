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
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('cari.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");

$idProses = RequestData("idproses", 0);
$proses = RequestData("proses", "");
$idKelompok = RequestData("idkelompok", 0);
$kelompok = RequestData("kelompok", "");
$jenisCari = RequestData("jeniscari", "");
$jenisCariText = RequestData("jeniscaritext", "");
$cari = RequestData("cari", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pencarian Calon Siswa</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="cari.content.js?<?=filemtime('cari.content.js')?>"></script>
</head>
<body style="padding: 20px;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idproses" value="<?=$idProses?>">
<input type="hidden" id="proses" value="<?=$proses?>">
<input type="hidden" id="idkelompok" value="<?=$idKelompok?>">
<input type="hidden" id="kelompok" value="<?=$kelompok?>">
<input type="hidden" id="jeniscari" value="<?=$jenisCari?>">
<input type="hidden" id="jeniscaritext" value="<?=$jenisCariText?>">
<input type="hidden" id="cari" value="<?=$cari?>">

<?php 
$result = PrepareSearchData($db);
if ($result[0] != 1)
{
    echo "<br><br>$result[1]";
    exit();
}

$page = 1;
$nData = $result[2];
$nPage = $result[3];
$lsIdPage = $result[4];
?>

<input type="hidden" id='lsidpage' value='<?=json_encode($lsIdPage)?>'>
<table border='0' cellpadding='0' cellspacing='0' width='100%' align='center'>
<tr>
    <td width='50%' align='left'>
    </td>
    <td width='*' valign='bottom' align='right'>
        <span class='cur-hand' onclick='cetak()'><img src='../images/ico/print.png' border='0' title='Cetak' />&nbsp;cetak</span>&nbsp;&nbsp;
    </td>
</tr>
</table>


<div id='dvTableContent'>
<?php
    $stReplid = implode(",", $lsIdPage[$page - 1]);
    ShowSearchResult($db);
?>
</div>
<br>

<div id='dvPageControl'>
<?php
    ShowPageControl();
?>
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>