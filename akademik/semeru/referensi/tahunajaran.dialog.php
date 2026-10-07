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
require_once('../library/departemen.php');
require_once('tahunajaran.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Tahun Ajaran" : "Ubah Tahun Ajaran";

$departemen = RequestData("departemen", "");
$tahunajaran = "";
$tglmulai = "";
$tglakhir = "";
$keterangan = "";

$db = new Db();
$db->TryOpenExit(true);

if ($replid > 0)
    LoadTahunAjaran($db, $replid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= $title ?></title>
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
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="../script/calendar.js"></script>
    <script language="javascript" src="../script/lang/calendar-en.js"></script>
    <script language="javascript" src="../script/calendar-setup.js"></script>
    <script language="javascript" src="tahunajaran.dialog.js?<?=filemtime('tahunajaran.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="<?= $replid ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td>Departemen<?=$tag_mandatory?></td>
    <td>
        <input id="departemen" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?= $departemen ?>" readonly>
    </td>
</tr>
<tr>
    <td>Tahun Ajaran<?=$tag_mandatory?></td>
    <td>
        <input id="tahunajaran" type="text" class="inputbox" style="width: 250px" maxlength="50" value="<?= $tahunajaran ?>">
    </td>
</tr>
<tr>
    <td>Tgl Mulai<?=$tag_mandatory?></td>
    <td>
        <input id="tglmulai" type="text" class="inputbox_readonly" style="width: 200px" readonly value="<?= LongDateFormat($tglmulai) ?>"  onclick="showPilihTglMulai()">
        <input type="hidden" id="tglmulai_value" value="<?= $tglmulai ?>">
        <img src="../images/ico/calendar.png" id="btntglmulai" style="cursor:pointer" title="pilih tanggal" onclick="showPilihTglMulai()">
    </td>
</tr>
<tr>
    <td>Tgl Akhir<?=$tag_mandatory?></td>
    <td>
        <input id="tglakhir" type="text" class="inputbox_readonly" style="width: 200px" readonly value="<?= LongDateFormat($tglakhir) ?>" onclick="showPilihTglAkhir()">
        <input type="hidden" id="tglakhir_value" value="<?= $tglakhir ?>">
        <img src="../images/ico/calendar.png" id="btntglakhir" style="cursor:pointer" title="pilih tanggal" onclick="showPilihTglAkhir()">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea rows="3" cols="40" class="inputbox" id="keterangan"><?= $keterangan ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanTahunAjaran()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>
