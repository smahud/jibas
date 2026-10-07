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
require_once('../../library/logger.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once("../../library/class/jpgraph.php");
require_once("../../library/class/jpgraph_bar.php");
require_once("../../library/class/jpgraph_line.php");
require_once('statistik.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$tahunMutasi = RequestData("tahunmutasi", 0);

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Statistik Mutasi Siswa</title>
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
    <script language="javascript" src="statistik.content.js?<?=filemtime('statistik.content.js')?>"></script>
</head>
<body style="padding: 0px; margin: 0px">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="tahunmutasi" value="<?=$tahunMutasi?>">

<?php       
    $arrData = GetStatistikData($db);
    $values = $arrData[0]; 
    $idlabels = $arrData[1];
    $labels = $arrData[2];

    if (count($values) == 0)
    {
        echo "<br><br>&nbsp;&nbsp;&nbsp;<i>belum ada data</i>";
        echo "</body></html>";
        exit();
    }
?>

<div style="width: 100%; height: 100vh; display: flex; gap: 5px;">
    <div style="flex: 1; overflow: auto; padding: 20px; height: 100vh; box-sizing: border-box; text-align: center;">
        <div id="dvStatistikChart" style="position: relative;">
<?php
            ShowStatistikChart($values, $labels);
?>        
        </div>
    </div>
    <div id="dvStatistikList" style="flex: 1; overflow: auto; padding: 20px; height: 100vh; box-sizing: border-box;">
        <div id="dvStatistikTable" style="position: relative;">
<?php
            ShowStatistikTable($values, $labels, $idlabels)
?>
        </div>
        <br><br>
        <input type="hidden" id='ndata' value='0'>
        <input type="hidden" id='npage' value='0'>
        <input type="hidden" id='lsidpage' value='[[]]'>
        <input type="hidden" id='streplid' value=''>
        <input type="hidden" id='activelabel' value=''>
        <input type="hidden" id='idjenismutasi' value=''>
        <input type="hidden" id='jenismutasi' value=''>
        <div id="dvStatistikDetail" style="position: relative;">

        </div>
        <br>
        <div id="dvPageControl">

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