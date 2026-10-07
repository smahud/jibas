<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('kelas.dialog.func.php');

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Kelas" : "Ubah Kelas";

$departemen = RequestData("departemen", "");
$idtahunajaran = RequestData("tahunajaran", "");
$idtingkat = RequestData("tingkat", "");
$tahunajaran = "";
$tingkat = "";
$kelas = "";
$kapasitas = "";
$nipwali = "";
$namawali = "";
$keterangan = "";

$db = new Db();
$db->TryOpenExit(true);

LoadKelas($db, $replid);
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
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kelas.dialog.js?<?=filemtime('kelas.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idtahunajaran ?>">
<input type="hidden" id="idtingkat" value="<?= $idtingkat ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td>Departemen</td>
    <td>
        <input id="departemen" type="text" class="inputbox_readonly" style="width: 250px" maxlength="100" value="<?= $departemen ?>" readonly>
    </td>
</tr>
<tr>
    <td>Tahun Ajaran</td>
    <td>
        <input id="tahunajaran" type="text" class="inputbox_readonly" style="width: 250px" maxlength="100" value="<?= $tahunajaran ?>" readonly>
    </td>
</tr>
<tr>
    <td>Tingkat</td>
    <td>
        <input id="tingkat" type="text" class="inputbox_readonly" style="width: 250px" maxlength="100" value="<?= $tingkat ?>" readonly>
    </td>
</tr>
<tr>
    <td>Kelas</td>
    <td>
        <input id="kelas" type="text" class="inputbox" style="width: 250px" maxlength="50" value="<?= $kelas ?>">
    </td>
</tr>
<tr>
    <td>Wali Kelas</td>
    <td>
        <input id="nipwali" type="text" class="inputbox_readonly"  onclick="cariWali()" style="width: 100px" readonly value="<?= $nipwali ?>">
        <input id="namawali" type="text" class="inputbox_readonly" onclick="cariWali()" style="width: 180px" readonly value="<?= $namawali ?>">
        <input type="button" class="dialogButtonGray" value="pilih" onclick="cariWali()">
    </td>
</tr>
<tr>
    <td>Kapasitas</td>
    <td>
        <input id="kapasitas" type="text" class="inputbox" style="width: 60px" maxlength="4" value="<?= $kapasitas ?>">
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea id="keterangan" class="inputbox" rows="3" cols="50"><?= $keterangan ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanKelas()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>
