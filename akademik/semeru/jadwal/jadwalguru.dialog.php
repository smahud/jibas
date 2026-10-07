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
require_once('../library/logger.php');
require_once('../util/peek.php');
require_once('jadwalguru.dialog.func.php');

$db = new Db();
$db->TryOpenExit();

$replid = RequestData("replid", 0);
$title = ($replid == 0) ? "Tambah Jadwal Guru" : "Ubah Jadwal Guru";

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idKategori = RequestData("idkategori", 0);
$kategori = RequestData("kategori", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");
$jam = RequestData("jam", 0);
$hari = RequestData("hari", 0);
$maxJam = RequestData("maxjam", 0);
$idTingkat = 0;
$idKelas = 0;
$idPelajaran = 0;
$status = 0;
$keterangan = "";
$jamorig1 = 0;
$jamorig2 = 0;

if ($replid > 0) 
    LoadJadwalKelas($db);
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
    <link rel="stylesheet" type="text/css" href="../style/toast.css?<?=filemtime('../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>">"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="jadwalguru.dialog.js?<?=filemtime('jadwalguru.dialog.js')?>"></script>
</head>
<body style="padding: 10px;">


<input type="hidden" id="replid" value="<?= $replid ?>">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idTahunAjaran ?>">
<input type="hidden" id="idkategori" value="<?= $idKategori ?>">
<input type="hidden" id="hari" value="<?= $hari ?>">
<input type="hidden" id="maxjam" value="<?= $maxJam ?>">
<input type="hidden" id="nip" value="<?= $nip ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">

<span class="dialogTitle"><?= $title ?></span><br><br>

<div style="display: flex; gap: 20px; width:100%;">
    <div style="flex: 1;">
        <span class='fg-secondary'>Departemen</span><br>
        <span class='fs-12 fst-bold'><?= $departemen ?></span>
    </div>
    <div style="flex: 1;">
        <span class='fg-secondary'>Tahun Ajaran</span><br>
        <span class='fs-12 fst-bold'><?= $tahunAjaran ?></span>
    </div>
    <div style="flex: 1;">
        <span class='fg-secondary'>Kategori</span><br>
        <span class='fs-12 fst-bold'><?= $kategori ?></span>
    </div>
</div>

<br>
<table cellpadding="5" cellspacing="0">
<tr>
    <td style="width:80px">Guru<?= $tag_mandatory ?></td>
    <td>
        <span class='fs-12 fst-bold'><?= $nama ?></span><br>
        <span class='fs-11 fg-secondary'><?= $nip ?></span>
    </td>
</tr>
<tr>
    <td style="width:80px">Kelas<?= $tag_mandatory ?></td>
    <td>
        <span>
            <span id='spTingkat'>
<?php       ShowSelectTingkat($db) ?>            
            </span>
            <span id='spKelas'>
<?php       ShowSelectKelas($db) ?>            
            </span>
        </span>        
    </td>
</tr>
<tr>
    <td style="width:80px">Pelajaran<?= $tag_mandatory ?></td>
    <td>
<?php
        ShowSelectPelajaran($db);
?>        
    </td>
</tr>
<tr>
    <td style="width:80px">Hari<?= $tag_mandatory ?></td>
    <td>
        <input type="text" id="namahari" class="inputbox inputbox-readonly" style="width: 120px;" readonly value="<?= NamaHari($hari) ?>">
    </td>
</tr>
<tr>
    <td style="width:80px">Jam<?= $tag_mandatory ?></td>
    <td>
        <input type="text" id="jam1" size="5" readonly value = "<?= $jam ?>" class="inputbox inputbox-readonly" />
        <input type="hidden" id="jam" value="<?=$jam ?>"/> s/d 
    	<input type="text" id="jam2" size="5" value="<?=$jam2?>" class="inputbox" />
        <input type="hidden" id="jamorig1" value="<?=$jamorig1?>"/>
        <input type="hidden" id="jamorig2" value="<?=$jamorig2?>"/>
    </td>
</tr>
<tr>
    <td style="width:80px">Status<?= $tag_mandatory ?></td>
    <td>
<?php
        ShowSelectStatus();
?>        
    </td>
</tr>
<tr>
    <td colspan="2">
        Keterangan<br>
        <textarea id="keterangan" class="inputbox" rows="3" cols="55"><?= $keterangan ?></textarea><br>
    </td>
</tr>
<tr>
    <td colspan="2" align="center">
        <br>
        <input type="button" id="btnSimpan" class="dialogButtonPositive w80 h30" value="Simpan" onclick="simpan()">
        <input type="button" id="btnTutup" class="dialogButtonNegative w80 h30" value="Tutup" onclick="window.close()">
    </td>
</tr>
<tr>
    <td colspan="2" style="padding-top: 10px">
        <span id="spInfo" class="fg-red"></span>
    </td>
</tr>
</table>

<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>