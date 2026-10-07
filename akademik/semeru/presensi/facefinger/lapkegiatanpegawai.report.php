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
require_once('../../library/userinfo.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('lapkegiatanpegawai.report.func.php');

$departemen = RequestData("departemen", "");
$tglAwal = RequestData("tglawal", "");
$tglAkhir = RequestData("tglakhir", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Presensi Kegiatan Pegawai</title>
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
    <script language="javascript" src="lapkegiatanpegawai.report.js?<?=filemtime('lapkegiatanpegawai.report.js')?>"></script>
</head>
<body style="margin: 5px; padding: 10px;"> 

<br>
<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="nip" value="<?=$nip?>">
<input type="hidden" id="nama" value="<?=$nama?>">
<input type="hidden" id="tglawal" value="<?= $tglAwal ?>">
<input type="hidden" id="tglakhir" value="<?= $tglAkhir ?>">

<?php
$userInfo = UserInfo::Pegawai($db, $nip);
if ($userInfo->Exist == false)
{
    echo "<i>Tidak ditemukan data pegawai $nip /khnck</i>";
    exit();
}

echo "<div id='divSectionUser'>";
UserInfo::ShowPegawaiAvatar($userInfo);
echo "</div><br>";
?>

<div id="dvTableContent">
<?php
    ShowRekapKegiatanPegawai($db);
?>
</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>