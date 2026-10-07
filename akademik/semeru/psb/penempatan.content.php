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
require_once('penempatan.content.func.php');

$departemen = RequestData("departemen", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Penempatan Calon Siswa</title>
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
    <script language="javascript" src="penempatan.content.js?<?=filemtime('penempatan.content.js')?>"></script>
</head>
<body style="padding: 0px; margin: 0px">

<input type="hidden" id="departemen" value="<?=$departemen?>">

<div style="width: 100%; height: 100vh; display: flex; gap: 5px;">
    <div style="flex: 1; overflow: auto; padding: 20px; height: 100vh; box-sizing: border-box;">
<?php
    $tab_relPath = "./";
    $tab_selectMode = "button";
    require_once ("tabs.calonsiswa.php");
?>
    </div>
    <div style="flex: 2; overflow: auto; padding: 20px; height: 100vh; box-sizing: border-box;">
        <div style="flex-shrink: 0; background: #efefef; height: 110px; padding: 10px">
            <b>Kelas Tujuan</b><br>
            <table border='0' cellspacing='0' cellpadding='2'>
            <tr>
                <td style='width: 80px'>Angkatan:</td>
                <td>
<?php               $idAngkatan = 0;
                    ShowSelectAngkatan($db);                    ?>
                </td>
            </tr>
            <tr>
                <td>Tahun Ajaran:</td>
                <td>
<?php               $idTahunAjaran = 0;
                    ShowSelectTahunAjaran($db);                    ?>
                </td>
            </tr>
            <tr>
                <td>Kelas:</td>
                <td>
                    <span id='spTingkat'>
<?php               $idTingkat = 0;    
                    ShowSelectTingkat($db);                    ?>
                    </span>
                    <span id='spKelas'>
<?php               $idKelas = 0;
                    ShowSelectKelas($db);                    ?>
                    </span>
                </td>
            </tr>
            </table>
        </div>
        <div style="flex: 1;">
            <div id='dvTableSiswaKelasTujuan' style="flex: 1; overflow-y: scroll;">
<?php
                ShowTableSiswaKelasTujuan($db);
?>
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