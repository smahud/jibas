<?
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 35.5 (August 10, 2026)
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
<?
require_once('include/sessionchecker.php');
require_once('include/common.php');
require_once('include/config.php');
require_once('include/db_functions.php');
require_once('include/sessioninfo.php');
require_once('library/departemen.php');

// ACCESS CONTROL: Only users with "ALL" access (Manajer Keuangan) can access
$access = getAccess();
if ($access != "ALL") {
    echo '<script>alert("Anda tidak memiliki hak akses ke menu ini. Hanya Manajer Keuangan dengan akses Semua Departemen yang dapat mengakses."); parent.location.href="penerimaan.php";</script>';
    exit();
}

$departemen = "";
if (isset($_REQUEST['departemen']))
    $departemen = $_REQUEST['departemen'];

// If no departemen selected, use first accessible
OpenDb();
if ($departemen == "") {
    $dep = getDepartemen(getAccess());
    if (count($dep) > 0)
        $departemen = $dep[0];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="style/tooltips.css">
<title>Rekap Pembayaran Siswa</title>
<script language="javascript" src="script/tooltips.js"></script>
<script language="javascript" src="script/ajax.js"></script>
<script language="javascript" src="script/validasi.js"></script>
<script language="javascript">
function change_dep() {
    var departemen = document.getElementById('departemen').value;
    document.location.href = "laprekappembayaran_siswa_header.php?departemen="+departemen;
    parent.left.location.href = "laprekappembayaran_siswa_pilih.php?departemen="+departemen;
    parent.right.location.href = "laprekappembayaran_siswa_blank.php";
}

function focusNext(elemName, evt) {
    evt = (evt) ? evt : event;
    var charCode = (evt.charCode) ? evt.charCode : ((evt.which) ? evt.which : evt.keyCode);
    if (charCode == 13) {
        document.getElementById(elemName).focus();
        return false;
    }
    return true;
}
</script>
</head>

<body topmargin="0" leftmargin="0" onload="document.getElementById('departemen').focus()">
<table border="0" cellpadding="0" cellspacing="0" width="100%" align="center">
<tr>
    <td width="60%">
    <table border="0" width="100%">
    <tr>
        <td width="15%"><strong>Departemen </strong></td>
        <td>
        <select name="departemen" id="departemen" style="width:150px" onchange="change_dep()" onKeyPress="return focusNext('keyword',event)">
<?      foreach($dep as $value) { ?>
            <option value="<?=$value ?>" <?=StringIsSelected($value, $departemen) ?>><?=$value ?></option>
        <? } ?>    
        </select>&nbsp;
        </td>
    </tr>
    </table>
    </td>
    <td width="*" rowspan="2" valign="middle" align="center">
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="4" face="Verdana, Arial, Helvetica, sans-serif" color="Gray">Rekap Pembayaran Siswa</font><br />
        <a href="penerimaan.php" target="_parent">
        <font size="1" color="#000000"><b>Penerimaan</b></font></a>&nbsp>&nbsp
        <font size="1" color="#000000"><b>Rekap Pembayaran Siswa</b></font>
    </td>
</tr>
</table>
<?
CloseDb();
?>
</body>
</html>