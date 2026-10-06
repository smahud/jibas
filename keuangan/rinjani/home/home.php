<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 33.0 (Jan 05, 2026)
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
require_once('../library/common.func.php');
require_once('../library/qsbuilder.php');
require_once('../library/userinfo.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/rupiah.php');
require_once('../library/departemen.php');
require_once('../util/peek.php');
require_once('home.func.php');

$db = new Db();
$db->TryOpenExit();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Beranda</title>
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/stringutil.js"></script>
    <script language="javascript" src="../script/dateutil.js"></script>
    <script language="javascript" src="../script/toast.js?<?=filemtime('../script/toast.js')?>"></script>
    <script language="javascript" src="../script/tools.js?r=<?=filemtime('../script/tools.js')?>"></script>
    <script language="javascript" src="../script/qsbuilder.js?r=<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="home.js?r=<?= filemtime('home.js') ?>"></script>
</head>
<body>

<div id='dvUserInfo' style='position: relative; width: 90%; margin: 0 auto;'>

<?php
    ShowUserInfo($db)        
?>        

<div style='position: absolute; right: 0; top: 30%;'>
    <input type="button" class='dialogButtonPositive' 
           style='width: 120px; height: 50px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); ' value='File Sharing'
           onclick='document.location.href="fileshare.php"'>
</div>

</div>

<br><br>

<div id='dvRekap' style='position: relative; width: 90%; margin: 0 auto;'>
    <span class="pageTitle">Rekapitulasi Transaksi</span><br>

    <div>
    &nbsp;&nbsp;Tanggal: 
<?php
    $tglRekap = date('Y-m-d');
?>
    <input type="text" id="txtglrekap" readonly size="15"
           value="<?= LongDateFormat($tglRekap) ?>"
           class="inputbox" style="background-color:#ddd; width: 150px;">&nbsp;
    <input type="hidden" id="tglrekap" value="<?= $tglRekap ?>">
    <a href="#" onclick="showPilihTanggalRekap()">
        <img src="../images/ico/calendar.png" border="0" id="btrekap"/>
    </a>
    <span class='cur-hand' style="margin-left: 10px;" onclick='refreshRekap()'>
        <img src='../images/ico/refresh.png'>&nbsp;muat ulang
    </span>
    </div>

    <br>
    <div id='dvRekapTransContainer' style="position: relative; width: 100%; margin: 0 auto; overflow: auto; padding: 10px; ">

    <div id='dvRekapTransContent' style="display: flex; width: max-content; padding-bottom: 20px;">
<?php
        ShowRekapTransaksi($db);
?>    
    </div>

    </div>
</span>
</div>

<br><br>

<div id='dvNota' style='position: relative; width: 90%; margin: 0 auto;'>
<span class="pageTitle">Nota</span><br>

<span style="position: absolute; right: 0; top: 10px">
    <span style='margin-left: 30px;' class='cur-hand' onclick='tambahNota()'>
        <img src='../images/ico/tambah.png'>&nbsp;nota baru
    </span>
    
</span>

<table border='0' cellpadding='3' cellspacing='0'>
<tr>    
    <td style="width: 90px;">
        Departemen: 
    </td>
    <td>
<?php
        ShowSelectDepartemen($db);
?>
    </td>
</tr>
<tr>    
    <td>
    Bagian:
    </td>
    <td>
        <span id='spBagianNota'>
<?php
            ShowSelectBagianNota($db);
?>
        </span>
<?php
        if (SI_USER_LEVEL() == $SI_USER_LANDLORD) 
        { 
            echo "<input type='button' class='dialogButtonGray' style='width: 30px; min-height: 24px;' title='Kelola Bagian Nota' value=' ... ' onclick='kelolaBagianNota()'>";
        }           
?>
        <input type="button" id="btLihat1" class="dialogButtonGray" value="Lihat" onclick="showDaftarNota()">
        <span style="margin-left: 20px;" class='fg-secondary cur-hand' onclick="onFilterClick()">filter</span>       
    </td>
</tr>
<tr class='trFilter' style='display: none'>
    <td>
    <input type='checkbox' id='chBulanTahun' onchange='onChangeChBulanTahun()'>&nbsp;Bulan: 
    </td>
    <td>
<?php
        ShowSelectBulan();
        ShowSelectTahun();
?>
    </td>
</tr>
<tr class='trFilter' style='display: none'>
    <td>
    <input type='checkbox' id='chKelompok' onchange='onChangeChKelompok()'>&nbsp;Kelompok: 
    </td>
    <td>
        <select id='kelompok' onchange='onChangeCbKelompok()' class='inputbox' disabled style="width: 120px;">
            <option value='siswa'>Siswa</option>
            <option value='calonsiswa'>Calon Siswa</option>
            <option value='pegawai'>Pegawai</option>
        </select>
        <input type="hidden" id='userid' value=''>
        <input type="text" id='username' class="inputbox" style="width: 250px; background-color: #ccc;">
        <input type='button' id='btCariPerson' class='dialogButtonGray' disabled style='width: 30px; min-height: 24px;' title='Cari' value=' ... ' onclick='cariPerson()'>
    </td>
</tr>
<tr class='trFilter' style='display: none'>
    <td>
    <input type='checkbox' id='chPenulis' onchange='onChangeChPenulis()'>&nbsp;Penulis: 
    </td>
    <td>
<?php
        ShowSelectPenulis($db);
?>
    </td>
</tr>
<tr class='trFilter' style='display: none'>
    <td>
    <input type='checkbox' id='chKeyword' onchange='onChangeChKeyword()'>&nbsp;Kata Kunci: 
    </td>
    <td>
    <input type='text' id='keyword' class="inputbox" style="width: 250px; background-color: #ccc;">
    </td>
</tr>
<tr class='trFilter' style='display: none'>
    <td>
        &nbsp;
    </td>
    <td>
        <input type="button" id="btLihat2" class="dialogButtonGray" value="Lihat" onclick="showDaftarNota()">
    </td>
</tr>
</table>

<br>
<input type="hidden" id="jsonidnota" value="">
<input type="hidden" id="nnota" value="0">
<div id='dvTableContentNota'>
</div>

<div id='dvPageControl'>
</div>

</div>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>