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
require_once('../../library/hintinfo.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('inputharian.content.func.php');

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
$bulan = RequestData("bulan", date('n'));
$tahun = RequestData("tahun", date("Y"));
$event = RequestData("event", "");
$idPresensi = RequestData("idpresensi", 0);

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
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="inputharian.content.js?<?=filemtime('inputharian.content.js')?>"></script>
</head>
<body style="margin: 5px;"> 

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="bulan" value="<?=$bulan?>">
<input type="hidden" id="tahun" value="<?=$tahun?>">
<input type="hidden" id="event" value="<?=$event?>">
<input type="hidden" id="idpresensi" value="<?=$idPresensi?>">

<div style="display: flex; width: 100%; height: 98vh; gap: 10px;">
    <div style="flex: 1; margin: 10px; border: 1px solid #ccc; border-radius: 5px; display: flex; flex-direction: column;" class="tabShadow" >
        <div style="flex-shrink: 0; height: 20px; margin: 10px; position: relative; ">
            <span style="position: absolute; top: 0; left: 0" class="fg-secondary fs-16">Riwayat Pendataan</span>
            <input type="button" style="position: absolute; top: 0; right: 0;" class="dialogButtonPositive" 
                   value="Pendataan Baru > " onclick="showInputForm(0)">
        </div>
        <div style="flex: 1; margin: 10px; overflow: auto;" >
<?php
        ShowRekapInputPresensi($db)
?>
        </div>
        
    </div>
    <div style="flex: 3; margin: 10px;">
        <div id="dvInputForm">

        </div>
    </div>
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>