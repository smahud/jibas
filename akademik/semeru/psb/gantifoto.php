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
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('gantifoto.func.php');

$replid = RequestData("replid", 0);

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Ganti Foto Siswa</title>
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
    <script language="javascript" src="gantifoto.js?<?=filemtime('gantifoto.js')?>"></script>
</head>
<body style="padding: 20px;">

<input type="hidden" id="replid" value="<?=$replid?>">
<input type='button' class='dialogButtonGray' value=' < kembali ' onclick='window.history.back()'>&nbsp;&nbsp;

<span class='fs-14 fst-bold'>Ganti Foto</span>
<br><br>
<table border='0' width='100%'>
<tr>
    <td width='20%' align='center'  valign='top'>
        <div id='dvCurrentFoto'>
<?php   
        ShowCurrentFoto($db);
?>        
        </div>
    </td>
    <td width='*' valign='top' align='left'>

        <table width='300' cellpadding='5' border='0'>
        <tr>
            <td align="center">
                <span class='box-outline-blue cur-hand' onclick='bukaWebcam()'>
                    buka webcam
                </span>&nbsp;
                <span class='box-outline-blue cur-hand' onclick='pilihGambar()'>
                    pilih gambar
                </span>
            </td>
        </tr>
        <tr>
            <td align='center'>
                <input type='hidden' id='imData'>
                <img id='imPreview'>        
            </td>
        </tr>
        <tr>
            <td align='center'>
                <input type='button' id='btnSimpan' onclick='simpanFoto()' class='dialogButtonGreen' style="display: none;" value='Simpan' onclick='simpanFoto()'>
            </td>
        </tr>
        </table>
        <br><br>
        
    </td>
</tr>
</table>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>