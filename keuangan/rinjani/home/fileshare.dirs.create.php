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
require_once('fileshare.dirs.create.func.php');
require_once('fileshare.util.func.php');

$db = new Db();
$db->TryOpenExit();

$idDir = RequestData("iddir", 0);

$sql = "SELECT dirfullpath 
          FROM jbsvcr.dirshare 
         WHERE idroot = 0";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$rootName = $row[0];

$sql = "SELECT dirfullpath 
          FROM jbsvcr.dirshare 
         WHERE replid = '$idDir'";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_row($res);
$dirFullPath = $row[0];
$fullPath = str_replace($rootName, "", $dirFullPath);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Buat Folder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css?<?=filemtime('../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="fileshare.dirs.create.js?<?=filemtime('fileshare.dirs.create.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle">Buat Folder</span><br><br>

<input type="hidden" id="iddir" name="iddir" value="<?=$idDir?>" >
<input type="hidden" id="fullpath" name="fullpath" value="<?=$dirFullPath?>">

<table border="0" width="95%" cellpadding="5" cellspacing="0" align="center">
<tr>
    <td width='20%' align="right">Folder Induk:</td>
    <td>
        <span class='fg-maroon'><?= "(root)/$fullPath" ?></span>
    </td>
</tr>
<tr>
  <td align="right">Folder Baru:</td>
  <td align="left">
    <input type="text" name="folder" id="folder" class="inputbox" style="width: 350px;">
  </td>
</tr>
<tr>
	<td colspan="2" align="center">
        <br>
        <input type="button" name="btnSimpan" id="btnSimpan" value="Buat" class="dialogButtonPositive" onclick="simpan()">&nbsp;
        <input type="button" name="btnTutup" id="btnTutup" value="Tutup" class="dialogButtonNegative" onClick="window.close()">    
    </td>
</tr>
</table>
</form>


<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
