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
require_once('../library/departemen.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('fileshare.files.func.php');
require_once('fileshare.util.func.php');
require_once('fileshare.config.php');

$db = new Db;
$db->TryOpenExit(true);

$iddir = RequestData("iddir", 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>File Sharing</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/mktree.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/mktree.js"></script>
    <script language="javascript" src="../script/vldr.js?r=<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="fileshare.files.js?r=<?=filemtime('fileshare.files.js')?>"></script>
    
</head>
<body>

<?php
$res = GetFullPath($db, $iddir);
$fullpath = $res[1];
$idguru = $res[0];

?>

<div style="width: 100%; position: relative">
<span style="display: inline-block; width: 600px">
    <span class='fs-14 fg-secondary'>&nbsp;<?= "(root)/$fullpath" ?></span><br>

<?php
    $totalSize = GetUserTotalSize($db, $idguru);
    $totalSizeMb = round($totalSize / (1024 * 1024), 2);
    $percent = round($totalSizeMb / $FILESHARE_USER_TOTALSIZE, 2) * 100;
    $allowUpload = $totalSizeMb < $FILESHARE_USER_TOTALSIZE;
    $fgColor = $allowUpload ? "fg-blue" : "fg-red";
?>
    <span class="fs-11 <?= $fgColor ?>">Total: <?= "$totalSizeMb MB / $FILESHARE_USER_TOTALSIZE MB ($percent%)" ?></span>    
</span>
<span style="position: absolute; right: 20px">
<?php
    if (SI_USER_ID() == $idguru && $allowUpload)
    {
        echo "<span class='cur-hand' onclick='uploadFile($iddir,\"$fullpath\")'>";
        echo "<img src='../images/ico/tambah.png' border='0' />&nbsp;upload";
        echo "</span>";
        echo "&nbsp;&nbsp;";
        echo "<span class='cur-hand' onclick='uploadFileUnzip($iddir,\"$fullpath\")'>";
        echo "<img src='../images/ico/tambah.png' border='0' />&nbsp;upload &amp; unzip";
        echo "</span>";
        echo "&nbsp;&nbsp;";
    }    
    echo "<span class='cur-hand' onclick='document.location.reload()'><img src='../images/ico/refresh.png' border='0'>&nbsp;muat ulang</span>";
?>    
</span>
</div>
<br>

<?php
ShowFiles($db, $iddir, $idguru, $fullpath);
?>

<div id="divDialog"></div>
<div id="toast-container"></div>

</body>
</html>