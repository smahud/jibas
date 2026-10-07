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
require_once('komensikap.dialog.func.php');

$mode = RequestData("mode", "select"); // manage || select
$departemen = RequestData("departemen", 0);
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$kodeJenis = RequestData("kodejenis", "");
$namaJenis = RequestData("namajenis", "");
$index = RequestData("index", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Komentar Sikap <?= $nmJenis ?> </title>
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
    <script language="javascript" src="../script/tinymce/tinymce.min.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="komensikap.dialog.js?<?=filemtime('komensikap.dialog.js')?>"></script>
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
            height: 100px;
            padding: 10px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idtingkat" value="<?= $idTingkat ?>">
<input type="hidden" id="tingkat" value="<?= $tingkat ?>">
<input type="hidden" id="index" value="<?= $index ?>">
<input type="hidden" id="kodejenis" value="<?= $kodeJenis ?>">
<input type="hidden" id="namajenis" value="<?= $namaJenis ?>">
<input type="hidden" id="mode" value="<?= $mode ?>">

<div class="app-container">
    <div class="header-bar" style="position: relative;">
        <span class='fs-14 fg-secondary'>Komentar Sikap <?= $namaJenis ?></span>
        <div style="position: absolute; top: 5px; right: 15px; width: 400px; text-align: right; color: #666;">
            Departemen: <b><?= $departemen ?></b>, Tingkat: <b><?= $tingkat ?></b>
        </div>
    </div>
    
    <div class="main-content">
        <div id="dvTableContent">
<?php   
        ShowTableKomenSikap() 
?>
        </div>
    </div>
    
    <nav class="menu-bar">
        <span id='spJudul' class='fst-bold'>Tambah Komentar Sikap <?= $namaJenis ?></span><br>
        <div style="margin-top: 5px;">
            <input type="hidden" id="komensikapid" value="0">

            <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td width="75%" valign="top">
                    Komentar<br>
                    <textarea id="komensikap" name="komensikap" class="inputbox" style="width: 97%;"></textarea>
                </td>
                <td width="25%" valign="top">
                    Urutan<br>
                    <input type='text' id='urutan' class='inputbox' maxlength="3" style='width: 50px'><br><br>
                    <input type="button" class="dialogButtonPositive" style="min-height: 20px" value="Simpan" onclick="simpan()">  
                    <input type="button" id='btBaru' class="dialogButtonGray" style="min-height: 20px; visibility: hidden;" value="Baru" onclick="baru()">  
                </td>
            </tr>                
            </table>
            
        </div>
    </nav>
</div>

<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
