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
require_once('pegawai.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Pegawai" : "Ubah Pegawai";

$db = new Db();
$db->TryOpenExit(true);

$kelamin = "L";
$menikah = "tak_ada";

if ($replid > 0)
	LoadPegawai($db, $replid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pegawai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
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
	<script language="javascript" src="pegawai.dialog.js?<?=filemtime('pegawai.dialog.js')?>" ></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?=$title?></span><br><br>
<input type="hidden" id="replid" value="<?=$replid?>">
<table cellpadding="5" cellspacing="0">
<tr>
    <td width="120">Bagian<?= $tag_mandatory ?></td>
    <td width="500">
<?php 
		ShowSelectBagianPegawai($db); 
?>		
	</td>
</tr>
<tr>
    <td>NIP<?=$tag_mandatory?></td>
    <td>
        <input id="nip" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?=$nip?>">
    </td>
</tr>
<tr>
    <td>Nama<?=$tag_mandatory?></td>
    <td>
        <input id="nama" type="text" class="inputbox" style="width: 250px" maxlength="255" value="<?=$nama?>">
    </td>
</tr>
<tr>
    <td>Panggilan</td>
    <td>
        <input id="panggilan" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?=$panggilan?>">
    </td>
</tr>
<tr>
    <td>Gender</td>
    <td>
		<select id="kelamin" class="inputbox" style="width: 150px">
			<option value="L" <?=($kelamin == "L") ? "selected" : ""?>>Laki-laki</option>
			<option value="P" <?=($kelamin == "P") ? "selected" : ""?>>Perempuan</option>
		</select>
    </td>
</tr>
<tr>
    <td>Menikah</td>
    <td>
		<select id="menikah" class="inputbox" style="width: 150px">
			<option value="tak_ada" <?=($menikah == "tak_ada") ? "selected" : ""?>>Tak Ada Data</option>
			<option value="menikah" <?=($menikah == "menikah") ? "selected" : ""?>>Menikah</option>
			<option value="belum" <?=($menikah == "belum") ? "selected" : ""?>>Belum</option>
		</select>
    </td>
</tr>
<tr>
    <td>HP</td>
    <td>
        <input id="hp" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?=$hp?>">
    </td>
</tr>
<tr>
    <td>Email</td>
    <td>
        <input id="email" type="text" class="inputbox" style="width: 250px" maxlength="255" value="<?=$email?>">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea rows="3" cols="40" class="inputbox" id="keterangan"><?=$keterangan?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanPegawai()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>