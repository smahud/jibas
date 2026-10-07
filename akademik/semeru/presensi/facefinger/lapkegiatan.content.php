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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/hintinfo.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('../../library/class/jpgraph.php');
require_once('../../library/class/jpgraph_bar.php');
require_once('../../library/class/jpgraph_line.php');
require_once('lapkegiatan.content.func.php');

$page = 1;
$departemen = RequestData("departemen", "");
$idKegiatan = RequestData("idkegiatan", "");
$kegiatan = RequestData("kegiatan", "");
$statusData = RequestData("statusdata", "");
$tahunAwal = RequestData("tahunawal", "");
$bulanAwal = RequestData("bulanawal", "");
$tanggalAwal = RequestData("tanggalawal", "");
$tahunAkhir = RequestData("tahunakhir", "");
$bulanAkhir = RequestData("bulanakhir", "");
$tanggalAkhir = RequestData("tanggalakhir", "");

$tglAwal = "$tahunAwal-$bulanAwal-$tanggalAwal";
$tglAkhir = "$tahunAkhir-$bulanAkhir-$tanggalAkhir";

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi Kegiatan</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/dialogbox.js"></script>
    <script language="javascript" src="../../script/util.js?<?=filemtime('../../script/util.js')?>"></script>
    <script language="javascript" src="../../script/toast.js?<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="lapkegiatan.content.js?<?=filemtime('lapkegiatan.content.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idkegiatan" value="<?=$idKegiatan?>">
<input type="hidden" id="kegiatan" value="<?=$kegiatan?>">
<input type="hidden" id="statusdata" value="<?=$statusData?>">
<input type="hidden" id="tahunawal" value="<?=$tahunAwal?>">
<input type="hidden" id="bulanawal" value="<?=$bulanAwal?>">
<input type="hidden" id="tanggalawal" value="<?=$tanggalAwal?>">
<input type="hidden" id="tahunakhir" value="<?=$tahunAkhir?>">
<input type="hidden" id="bulanakhir" value="<?=$bulanAkhir?>">
<input type="hidden" id="tanggalakhir" value="<?=$tanggalAkhir?>">
<input type="hidden" id="tglawal" value="<?= $tglAwal ?>">
<input type="hidden" id="tglakhir" value="<?= $tglAkhir ?>">



<div style="display: flex; width: 100%; height: 90vh; gap: 10px;">
    <div style="flex: 1; display: flex; flex-direction: column;">
        <div id='dvTableTanggal' style="flex: 1; overflow-y: scroll; ">
<?php
            $selTanggal = "";
            ShowTanggalKegiatan($db)
?>                        
        </div>
    </div>
    <div style="flex: 3; display: flex; flex-direction: column;">
        <div id='dvTableContent' style="flex: 1; overflow-y: scroll; ">
<?php
            if ($statusData == "hadir")
                ShowPresennsiKegiatanHadir($db);            
            else 
                ShowPresennsiKegiatanBelum($db);            
?>
        </div>
    </div>
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>