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
require_once('rpp.dialog.func.php');

$replid = RequestData("replid", 0);
$departemen = RequestData("departemen", "");
$semester = RequestData("semester", "");
$tingkat = RequestData("tingkat", "");
$pelajaran = RequestData("pelajaran", "");
$kodeRpp = "";
$urutan = "";
$materi = "";
$deskripsi = "";

LoadRpp();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Lihat RPP</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
</head>
<body style="padding: 10px;">

<span class="fs-14 fg-black"><?= $pelajaran ?></span><br>
<span class="fs-12 fg-secondary"><?= "$departemen | $tingkat | $semester" ?></span>
<br><br>
<span class="fs-18 fg-black"><span class="fg-maroon"><?= $kodeRpp ?></span> <?= $materi ?></span>
<br><br>

<div style="overflow: auto; width: 500; height: 500px">
<?= $deskripsi ?>
</div>

</body>
</html>