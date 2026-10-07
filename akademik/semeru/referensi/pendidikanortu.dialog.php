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
require_once('pendidikanortu.dialog.func.php');

$mode = RequestData("mode", "manage"); // manage || select
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pendidikan Orang Tua</title>
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
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pendidikanortu.dialog.js?<?=filemtime('pendidikanortu.dialog.js')?>"></script>
    <style>
        /* Reset margins and ensure the body takes full height */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
        }

        /* The magic happens here */
        .app-container {
            display: flex;
            flex-direction: column;
            height: 100vh; /* Forces container to be exactly the height of the screen */
        }

        .header-bar {
            flex-shrink: 0; /* Prevents the header from squishing */
            background-color: white;
            padding: 10px;
        }

        .main-content {
            flex: 1; /* Tells this div to grow and fill ALL remaining vertical space */
            background-color: white;
            padding: 10px;
            overflow-y: auto; /* Enables scrolling inside the content area if text overflows */
        }

        .menu-bar {
            flex-shrink: 0; /* Prevents the menu from squishing */
            background-color: #efefef;
            height: 80px;
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="app-container">
    <div class="header-bar">
        <span class='fs-18 fg-secondary'>Pendidikan Orang Tua</span>
        <input type='hidden' id='mode' value='<?= $mode ?>'>
    </div>
    
    <div class="main-content">
        <div id="dvTableContent">
<?php   
        ShowTablePendidikanOrtu() 
?>
        </div>
    </div>
    
    <nav class="menu-bar">
        <span id='spJudul' class='fst-bold'>Tambah Pendidikan Orang Tua</span><br>
        <div style="margin-top: 5px;">
            <input type="hidden" id="pendidikanortuid" value="0">
            <span style="display: inline-block; width: 80px; margin-left: 10px">Pendidikan</span>
            <input type='text' id='pendidikanortu' class='inputbox' style='width: 200px'><br>
            <span style="display: inline-block; width: 80px; margin-left: 10px">Urutan</span>
            <input type='text' id='urutan' class='inputbox' maxlength="3" style='width: 50px'>
            <input type="button" class="dialogButtonPositive" style="min-height: 20px" value="Simpan" onclick="simpan()">  
            <input type="button" id='btBaru' class="dialogButtonGray" style="min-height: 20px; visibility: hidden;" value="Baru" onclick="baru()">  
        </div>
    </nav>
</div>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
