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
require_once('identitas.dialog.func.php');

$replid = RequestData("replid", 0);
$departemen = RequestData("departemen", "");
$title = ($replid == 0) ? "Tambah Identitas" : "Ubah Identitas";

$db = new Db();
$db->TryOpenExit(true);

if ($replid != 0)
    LoadIdentitas($db);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
	<script language="javascript" src="identitas.dialog.js?<?=filemtime('identitas.dialog.js')?>" ></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle">Identitas Sekolah</span><br><br>
<input type="hidden" id="replid" value="<?=$replid?>">
<input type="hidden" id="departemen" value="<?=$departemen?>">

<table border="0" width="95%" cellpadding="5" cellspacing="0" align="center">
<tr>
	<td width="120"><strong>Nama</strong></td>
    <td>
        <input type="text" class="inputbox" id="nama" size="80" maxlength="250" value="<?= $nama ?>">
    </td>
</tr>
<tr>
	<td width="120"><strong>Logo</strong></td>
    <td>
        <input type="file" class="inputbox" id="logo" style="width: 500px" maxlength="250" value="<?= $logo ?>">
    </td>
</tr>
<tr>
	<td colspan="2">

    <table border="0" width="100%" align="center">
    <tr><td width="50%">

        <fieldset style="border: 1px solid #ccc; border-radius: 3px;">
        <legend><b>Lokasi 1</b></legend>

        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
        <tr>	
            <td valign="top">Alamat</td>
            <td><textarea class="inputbox" id="alamat1" rows="3" style="width:190px"><?=$alamat1 ?></textarea></td>
        </tr>    
        <tr>
            <td>No Telp1</td>
            <td><input type="text" class="inputbox" id="tlp1" size="24" maxlength="50" value="<?=$tlp1 ?>">
            </td>
        </tr>
        <tr>
            <td>No Telp2</td>
            <td><input type="text" class="inputbox" id="tlp2" size="24" maxlength="50" value="<?=$tlp2 ?>"></td>
        </tr>
        <tr>
            <td>No Fax</td>
            <td><input type="text" class="inputbox" id="fax1" size="24" maxlength="50" value="<?=$fax1 ?>"></td>
        </tr>
        </table>
        </fieldset>

	</td><td>

        <fieldset style="border: 1px solid #ccc; border-radius: 3px;">
        <legend><b>Lokasi 2</b></legend>

        <table border="0" width="100%" cellpadding="5" cellspacing="0" align="center">
        <tr>	
            <td style="width: 100px" valign="top">Alamat</td>
            <td><textarea class="inputbox" id="alamat2" rows="3" style="width:190px"><?=$alamat2 ?></textarea></td>
        </tr>    
        <tr>
            <td>No Telp1</td>
            <td><input type="text" class="inputbox" id="tlp3" size="24" maxlength="50" value="<?=$tlp3 ?>"></td>
        </tr>
        <tr>
            <td>No Telp2</td>
            <td><input type="text" class="inputbox" id="tlp4" size="24" maxlength="50" value="<?=$tlp4 ?>"></td>
        </tr>
        <tr>
            <td>No Fax</td>
            <td><input type="text" class="inputbox" id="fax2" size="24" maxlength="50" value="<?=$fax2 ?>"></td>
        </tr>
   	    </table>

	    </fieldset>

    </td></tr>    
    </table>

    </td>
</tr>
<tr>
	<td>Website</td>
	<td><input type="text" class="inputbox" id="situs" size="80" maxlength="100" value="<?=$situs ?>"></td>
</tr>
<tr>
	<td><strong>Email</strong></td>
	<td><input type="text" class="inputbox" id="email" size="80" maxlength="100" value="<?=$email?>"></td>
</tr>
<tr>
	<td colspan="2" align="center">
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanIdentitas()" />&nbsp;    
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()" />
    </td>
</tr>
</table>

</body>
</html>