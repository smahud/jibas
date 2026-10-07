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
require_once('jadwalguru.content.func.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKategori = RequestData("idkategori", 0);
$kategori = RequestData("kategori", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");

$db = new Db();
$db->TryOpenExit();

$jamData = LoadJam($db);
$jam     = $jamData['jam'];
$maxJam  = $jamData['maxJam'];
$jadwal  = LoadJadwal($db);
$mask = array_fill(1, 7, 0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Jadwal Guru</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css?<?=filemtime('../style/toast.css')?>">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="jadwalkelas.content.css?<?=filemtime('jadwalkelas.content.css')?>">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="jadwalguru.content.js?<?=filemtime('jadwalguru.content.js')?>"></script>
</head>
<body>

<input type="hidden" id="mode" value="list">
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkategori" value="<?=$idKategori?>">
<input type="hidden" id="kategori" value="<?=$kategori?>">
<input type="hidden" id="nip" value="<?=$nip?>">
<input type="hidden" id="nama" value="<?=$nama?>">
<input type="hidden" id="maxjam" value="<?=$maxJam?>">

<table border="0" width="90%" align="center">
<tr>
    <td>
        <table border='0' cellpadding='0' cellspacing='0' width='100%' align='center'>
        <tr>
            <td width='50%' align='left'>
                &nbsp;
            </td>
            <td width='*' valign='bottom' align='right'>
                <span class='cur-hand fg-secondary' onclick='onDataChanged()'>
                    <img src='../images/ico/refresh.png' border='0' title='refresh' />&nbsp;refresh
                </span>&nbsp;&nbsp;
                <span class='cur-hand fg-secondary' onclick='cetak()'>
                    <img src='../images/ico/print.png' border='0' title='cetak'/>&nbsp;cetak
                </span>&nbsp;&nbsp;
<?php           if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
	                <span class='cur-hand fg-secondary' onclick='hapusGuru()'>
                        <img src='../images/ico/hapus.png' border='0' title='hapus'/>&nbsp;hapus jadwal guru ini
                    </span>
<?php           } ?>  
            </td>
        </tr>
        </table>
            
    </td>
</tr>
<tr>
    <td align="left" valign="top">

    <div id='dvTableContent'>

    <table border="1" width="100%" id="table" class="tab" align="center" style="border-collapse:collapse">
        <tr height="30">
            <td width="110px" class="header" align="center">Jam</td>
            <td width="110px" class="header" align="center">Senin</td>
            <td width="110px" class="header" align="center">Selasa</td>
            <td width="110px" class="header" align="center">Rabu</td>
            <td width="110px" class="header" align="center">Kamis</td>
            <td width="110px" class="header" align="center">Jumat</td>
            <td width="110px" class="header" align="center">Sabtu</td>
            <td width="110px" class="header" align="center">Minggu</td>
        </tr>
        <?php
        $j = 0;
        foreach ($jam as $k => $v): ?>
        <tr>
            <td class="jam" width="110px">
                <?= ++$j ?>.
                <span class='jamStart'><?= $v['jam1'] ?></span> - 
                <span class='jamEnd'><?= $v['jam2'] ?></span>
<?php           if ($v['keterangan'] != "") { ?>
                    <br><br><span class='jamKeterangan'><?= $v['keterangan'] ?></span>
<?php           } ?>                
            </td>
<?php       for ($i = 1; $i <= 7; $i++): ?>
<?=             GetCell($k, $i, $mask, $jadwal) ?>
<?php       endfor; ?>
        </tr>
        <?php endforeach; ?>
    </table>

    </div>
    
    </td>
</tr>
<!-- END TABLE CENTER -->     
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>

