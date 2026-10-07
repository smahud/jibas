<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('kelompok.dialog.func.php');

$replid = RequestData('replid', 0);
$departemen = RequestData('departemen', '');
$idProsesPsb = RequestData('idprosespsb', '');
$prosesPsb = RequestData('prosespsb', '');

$title = ($replid == 0) ? 'Tambah Kelompok Calon Siswa' : 'Ubah Kelompok Calon Siswa';

$db = new Db();
$db->TryOpenExit(true);

LoadKelompok($db, $replid);
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
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kelompok.dialog.js?<?=filemtime('kelompok.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idprosespsb" value="<?= $idProsesPsb ?>">
<input type="hidden" id="prosespsb" value="<?= $prosesPsb ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td style="width: 90px">Departemen</td>
    <td>
        <input id="departemenText" type="text" class="inputbox_readonly" style="width: 250px" value="<?= $departemen ?>" readonly>
    </td>
</tr>
<tr>
    <td>Penerimaan</td>
    <td>
        <input id="prosesText" type="text" class="inputbox_readonly" style="width: 250px" value="<?= $prosesPsb ?>" readonly>
    </td>
</tr>
<tr>
    <td>Kelompok<?= $tag_mandatory ?></td>
    <td>
        <input id="kelompok" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?= $kelompok ?>">
    </td>
</tr>
<tr>
    <td>Kapasitas<?= $tag_mandatory ?></td>
    <td>
        <input id="kapasitas" type="text" class="inputbox" style="width: 80px" maxlength="4" value="<?= $kapasitas ?>">
        <span class="fg-secondary">orang</span>
    </td>
</tr>
<tr>
    <td>Keterangan</td>
    <td>
        <textarea id="keterangan" class="inputbox" rows="3" cols="45"><?= $keterangan ?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanKelompok()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

</body>
</html>
