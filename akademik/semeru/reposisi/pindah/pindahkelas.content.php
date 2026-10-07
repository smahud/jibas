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
require_once('pindahkelas.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Pindah Kelas</title>
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
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pindahkelas.content.js?<?=filemtime('pindahkelas.content.js')?>"></script>
</head>
<body style="margin: 5px;"> 

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelasasal" value="<?=$idKelas?>">
<input type="hidden" id="kelasasal" value="<?=$kelas?>">

<div style="display: flex; width: 100%; height: 98vh; gap: 10px;">
    <div style="flex: 1; display: flex; flex-direction: column;">
        <div id='dvTableSiswaKelasAsal' style="flex: 1; overflow-y: scroll;">
<?php       ShowTableSiswaKelasAsal($db) ?>        
        </div>
        <div style="flex-shrink: 0; background: #efefef; height: 40px; padding: 10px;">
            <div style='display: inline-block; margin-right: 10px;'>
                Keterangan<br>
                <input type='text' id='keterangan' class='inputbox' maxlength="100" style="width: 300px">
            </div>
            <input type='button' class='dialogButtonPositive' value='Pindah >' style='width: 100px' onclick='pindahSiswa()'>
        </div>
    </div>
    <div style="flex: 1; display: flex; flex-direction: column;">
        <div style="flex-shrink: 0; background: #efefef; height: 40px; padding: 10px">
            <b>Kelas Tujuan</b><br>
            <span id='dvSelectKelasTujuan'>
<?php
            $idKelasTujuan = 0;
            ShowSelectKelasTujuan($db);
?>            
            </span>
            <img src='../../images/ico/refreshbw.png' class='cur-hand' onclick='refreshKelasTujuan()' title='refresh kelas tujuan'>
        </div>
        <div style="flex: 1;">
            <div id='dvTableSiswaKelasTujuan' style="flex: 1; overflow-y: scroll;">
<?php       ShowTableSiswaKelasTujuan($db) ?>        
        </div>
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