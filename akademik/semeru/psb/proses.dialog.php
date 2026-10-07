<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('proses.dialog.func.php');

$replid = RequestData('replid', 0);
$departemen = RequestData('departemen', '');

$title = ($replid == 0) ? 'Tambah Proses Penerimaan Siswa Baru' : 'Ubah Proses Penerimaan Siswa Baru';

$proses = '';
$kodeawalan = '';
$keterangan = '';

$db = new Db();
$db->TryOpenExit(true);

LoadProses($db, $replid);
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
    <link rel="stylesheet" type="text/css" href="../style/tooltips.rinjani.css?<?=filemtime('tooltips.rinjani.css')?>">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="../script/tooltips.rinjani.js?r=<?=filemtime('../script/tooltips.rinjani.js')?>"></script>
    <script language="javascript" src="proses.dialog.js?<?=filemtime('proses.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle"><?= $title ?></span><br><br>
<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">

<table cellpadding="5" cellspacing="0">
<tr>
    <td>Departemen<?= $tag_mandatory ?></td>
    <td>
        <input id="departemenText" type="text" class="inputbox_readonly" style="width: 250px" maxlength="100" value="<?= $departemen ?>" readonly>
    </td>
</tr>
<tr>
    <td>Nama Proses<?= $tag_mandatory ?></td>
    <td>
        <input id="proses" type="text" class="inputbox" style="width: 250px" maxlength="100" value="<?= $proses ?>">
    </td>
</tr>
<tr>
    <td>Kode Awalan<?= $tag_mandatory ?></td>
    <td>
        <input id="kodeawalan" type="text" class="inputbox" style="width: 120px" maxlength="5" value="<?= $kodeawalan ?>">
        <img src="../images/help32.png" class="tooltip-icon" title="help"
                     onclick="showTooltip(this, '../help/psb_tt_kodeawalan.html', 'auto', 500)"  >
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
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpanProses()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
</table>

<div id="tooltip" class="tooltip hidden" aria-hidden="true">
    <button class="tooltip-close">&times;</button>
    <div class="tooltip-arrow"></div>
    <div class="tooltip-content"></div>
</div>

</body>
</html>
