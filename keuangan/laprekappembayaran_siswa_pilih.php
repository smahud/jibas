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

// ACCESS CONTROL
$access = getAccess();
if ($access != "ALL") {
    echo '<script>alert("Anda tidak memiliki hak akses ke menu ini."); parent.location.href="penerimaan.php";</script>';
    exit();
}

$departemen = "";
if (isset($_REQUEST['departemen']))
    $departemen = $_REQUEST['departemen'];

if ($departemen == "") {
    OpenDb();
    $dep = getDepartemen(getAccess());
    if (count($dep) > 0)
        $departemen = $dep[0];
    CloseDb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="style/style.css">
<link href="script/SpryTabbedPanels.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pilih Siswa</title>
<script src="script/SpryTabbedPanels.js" type="text/javascript"></script>
<script src="script/ajax.js" type="text/javascript"></script>
<script language="javascript">
function show_panel0(x) {
    document.getElementById("panel0").innerHTML = x;
}

function change_departemen() {
    var departemen = document.getElementById('depart').value;
    sendRequestText("library/cari_siswa_rekappembayaran.php", show_panel0, "departemen="+departemen);
    parent.right.location.href="laprekappembayaran_siswa_blank.php"; 
}
</script>
</head>

<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="background-color:#FFFFFF">
<input type="hidden" id="depart" value="<?=$departemen ?>" />
<table border="0" width="100%" align="center" cellspacing="2" cellpadding="2">
<tr><td align="left">
    <table border="0" cellpadding="2" bgcolor="#FFFFFF" cellspacing="0" width="100%" >
    <tr height="500">
        <td width="100%" valign="top" bgcolor="#FFFFFF">
            <div id="TabbedPanels1" class="TabbedPanels">
                <ul class="TabbedPanelsTabGroup">
                    <li class="TabbedPanelsTab" tabindex="0"><font size="1">Cari Siswa</font></li>
                </ul>
                <div class="TabbedPanelsContentGroup">
                    <div class="TabbedPanelsContent" id="panel0"></div>
                </div>
            </div>
        </td>
    </tr>
    </table>
</td></tr>
</table>
<script type="text/javascript">
var TabbedPanels1 = new Spry.Widget.TabbedPanels("TabbedPanels1");
sendRequestText("library/cari_siswa_rekap.php", show_panel0, "departemen=<?=$departemen?>");
</script>
</body>
</html>