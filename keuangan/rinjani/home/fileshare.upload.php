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
require_once('fileshare.config.php');
require_once('fileshare.util.func.php');

$idDir = RequestData("iddir", 0);
$fullPath = RequestData("fullpath", "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Upload File</title>
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
    <script language="javascript" src="fileshare.upload.js?<?=filemtime('fileshare.upload.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle">Upload File</span><br><br>

<form name="uploadform" id="uploadform" enctype="multipart/form-data">
<input type="hidden" id="fullpath" name="fullpath" readonly value="<?=$fullPath?>" >
<input type="hidden" id="iddir" name="iddir" readonly value="<?=$idDir?>" >
<input type="hidden" id="maxsize" name="maxsize" value="<?=$FILESHARE_UPLOAD_MAXSIZE?>">
<table border="0" width="95%" cellpadding="2" cellspacing="2" align="center">
<tr>
    <td width='12%' align="right">Tujuan</td>
    <td>
        &nbsp;<strong>(root)/<?=$fullPath?></strong>
    </td>
</tr>
<tr>
  <td align="right">File #1</td>
  <td align="left"><input name="file1" id="file1" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #2</td>
  <td align="left"><input name="file2" id="file2" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #3</td>
  <td align="left"><input name="file3" id="file3" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #4</td>
  <td align="left"><input name="file4" id="file4" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #5</td>
  <td align="left"><input name="file5" id="file5" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #6</td>
  <td align="left"><input name="file6" id="file6" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
  <td align="right">File #7</td>
  <td align="left"><input name="file7" id="file7" class="inputbox" onchange='checkFileSize(this)' style="width: 350px;" type="file"></td>
</tr>
<tr>
	<td colspan="2" align="center">
        <br>
        <input type="button" name="btnSimpan" id="btnSimpan" value="Unggah" class="dialogButtonPositive" onclick="simpan()">&nbsp;
        <input type="button" name="btnTutup" id="btnTutup" value="Tutup" class="dialogButtonNegative" onClick="window.close()"><br>
        <span class='fs-11 fg-maroon'>maksimal <?= $FILESHARE_UPLOAD_MAXSIZE ?> MB</span>    
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
