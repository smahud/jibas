<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
require_once('../library/rupiah.php');
require_once('../library/departemen.php');
require_once('../library/colorfactory.php');
require_once('../library/common.func.php');
require_once('../library/userinfo.php');
require_once('../util/peek.php');
require_once('dashboard.func.php');
require_once('dashboard.keu.func.php');
require_once('dashboard.nilai.func.php');
require_once('dashboard.presensi.func.php');
require_once('dashboard.ujian.func.php');
require_once('dashboard.nota.func.php');

$replid = RequestData("replid", 0);

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dashboard Siswa</title>
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
    <script language="javascript" src="dashboard.js?<?=filemtime('dashboard.js')?>"></script>
    <script language="javascript" src="dashboard.keu.js?<?=filemtime('dashboard.keu.js')?>"></script>
    <script language="javascript" src="dashboard.info.js?<?=filemtime('dashboard.info.js')?>"></script>
    <script language="javascript" src="dashboard.nilai.js?<?=filemtime('dashboard.nilai.js')?>"></script>
    <script language="javascript" src="dashboard.presensi.js?<?=filemtime('dashboard.presensi.js')?>"></script>
    <script language="javascript" src="dashboard.ujian.js?<?=filemtime('dashboard.ujian.js')?>"></script>
    <script language="javascript" src="dashboard.nota.js?<?=filemtime('dashboard.nota.js')?>"></script>
</head>
<body style="padding: 10px;">

<div>
    <span id="divInfoSiswa" style="display: inline-block; width: 700px">
<?php
    ShowInfoSiswa($db);
?>
    </span>
    <span style="vertical-align: center ">
        <input type='button' class='dialogButtonGray' value='< Kembali' onclick='window.history.back()'>&nbsp;&nbsp;
        <span class='cur-hand' onclick="document.location.reload()">
            <img src='../images/ico/refresh.png' title='refresh'>&nbsp;muat ulang
        </span>
    </span>
</div>
<br>



<div id="tabDashboardSiswa">
    <ul>
        <li><a href="#tabs-0" style="width: 80px;">Informasi</a></li>
        <li><a href="#tabs-1" style="width: 80px;">Keuangan</a></li>
        <li><a href="#tabs-2" style="width: 80px;">Nilai</a></li>
        <li><a href="#tabs-3" style="width: 80px;">Presensi</a></li>
        <li><a href="#tabs-4" style="width: 80px;">Ujian CBE</a></li>
        <li><a href="#tabs-5" style="width: 120px;">Nota Siswa</a></li>
    </ul>
    <div id="tabs-0" style="padding: 2px">
<?php
        include "dashboard.info.php";
?>
    </div>
    <div id="tabs-1" style="padding: 2px">
<?php
        include "dashboard.keu.php";
?>
    </div>
    <div id="tabs-2" style="padding: 2px">
<?php
        include "dashboard.nilai.php";
?>
    </div>
    <div id="tabs-3" style="padding: 2px">
<?php
        include "dashboard.presensi.php";
?>
    </div>
    <div id="tabs-4" style="padding: 2px">
<?php
        include "dashboard.ujian.php";
?>
    </div>
    <div id="tabs-5" style="padding: 2px">
<?php
        include "dashboard.nota.php";
?>
    </div>
</div>
 

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>