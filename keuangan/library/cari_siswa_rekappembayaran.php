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
require_once('../include/common.php');
require_once('../include/config.php');
require_once('../include/db_functions.php');
require_once('../include/sessioninfo.php');
require_once('departemen.php');

$keyword = "";
if (isset($_REQUEST['keyword']))
	$keyword = $_REQUEST['keyword'];

$departemen = "";
if (isset($_REQUEST['departemen']))
	$departemen = $_REQUEST['departemen'];

$submit = 0;
if (isset($_REQUEST['submit']))
	$submit = (int)$_REQUEST['submit'];

OpenDb();
?>
<table border="0" width="100%" align="center">
<tr>
    <td width="20%"><font color="#000000"><strong>Departemen</strong></font></td>
    <td>
    <input type="text" name="departemen" id="departemen" value="<?=$_REQUEST['departemen']?>" readonly="readonly" style="background-color:#CCCC99;" size="23">
    <input type="hidden" name="depart1" id="depart1" value="<?=$_REQUEST['departemen']?>" />
    </td>
</tr>
<tr>
    <td><font color="#000000"><strong>NIS / Nama Siswa</strong></font></td>	
    <td><input type="text" name="keyword" id="keyword" value="<?=$keyword ?>" size="23" onKeyPress="return focusNext('submit', event)"/>     
    <span style="font-size:10px; color:#666;">(min. 3 karakter untuk nama)</span>
    </td>
</tr>	
<tr>
    <td colspan="2" width="15%" align="center">
    <input type="button" class="but" name="submit" id="submit" value="Cari" onclick="carilah();" style="width:80px"/>
    </td>
</tr>
<tr>
    <td align="center" colspan="2">
    <div id="caritabel">
    
<? 
if ($submit == 1) { 
    OpenDb();    

    $filter = "";
    if ($departemen <> "" && $departemen <> "-1") 
        $filter = "AND d.departemen = '$departemen'";

    // Search by NIS (exact) or Name (partial, min 3 chars)
    $where = "";
    if (strlen($keyword) >= 3) {
        // Check if it looks like NIS (all digits) or name
        if (ctype_digit($keyword)) {
            $where = "AND (s.nis = '$keyword' OR s.nama LIKE '%$keyword%')";
        } else {
            $where = "AND s.nama LIKE '%$keyword%'";
        }
    } else if (strlen($keyword) > 0 && strlen($keyword) < 3) {
        echo '<br><table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
        <tr height="30" align="center"><td><br /><font size="2" color="red"><b>Minimal 3 karakter untuk pencarian nama!</b></font><br /><br /></td></tr></table>';
        CloseDb();
        exit();
    }

    $sql = "SELECT s.nis, s.nama, k.kelas, d.departemen, t.tingkat 
            FROM jbsakad.siswa s
            JOIN jbsakad.kelas k ON k.replid = s.idkelas
            JOIN jbsakad.tingkat t ON t.replid = k.idtingkat
            JOIN jbsakad.departemen d ON d.replid = t.iddepartemen
            WHERE s.aktif = 1 $filter $where
            GROUP BY s.nis
            ORDER BY d.departemen, t.tingkat, k.kelas, s.nama";
    $result = QueryDb($sql);
    
    if (@mysqli_num_rows($result) > 0) {
?>   
    <br>
    <table width="100%" id="table1" class="tab" align="center" border="1" bordercolor="#000000">
    <tr height="30" class="header" align="center">
        <td width="7%">No</td>
        <td width="12%" style="cursor:pointer;" onClick="change_urut('s.nis','cari')">N I S</td>
        <td width="*" style="cursor:pointer;" onClick="change_urut('s.nama','cari')">Nama Siswa</td>       
        <td width="25%" style="cursor:pointer;" onClick="change_urut('d.departemen','cari')">Departemen / Kelas</td>       
    </tr>
<?
    $cnt = 0;
    while($row = mysqli_fetch_row($result)) {
        $cnt++;
?>    
    <tr height="25" onClick="pilih_siswa('<?=$row[0]?>')" style="cursor:pointer" id="siswacari<?=$cnt?>">
        <td align="center"><?=$cnt ?></td>
        <td align="center"><?=$row[0]?></td>
        <td align="left"><?=$row[1] ?></td>       
        <td align="center"><?=$row[3].' - '.$row[4].' - '.$row[2]?></td>
    </tr>
<? 
    } 
    CloseDb(); 
?> 
    </table>
<? 
    } else { ?>    		
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
    <tr height="30" align="center">
        <td>   
    <br />
    <font size="2" color="red"><b>Tidak ditemukan adanya data</b></font>	
    <br /><br />
        </td>
    </tr>
    </table>
<? 
    } 
} else { ?>
    <table width="100%" align="center" cellpadding="2" cellspacing="0" border="0" id="table1">
    <tr height="30" align="center">
        <td>   
    <br /><br />	
    <font size="2" color="#757575"><b>Masukkan NIS atau Nama Siswa (min. 3 huruf)<br />kemudian klik tombol "Cari" untuk melihat data siswa</b></font>	
    <br /><br />
        </td>
    </tr>
    </table>
<? 
} 
?>	
    </div>
     </td>    
</tr>
</table>

<script language="javascript">
function carilah() {
    var keyword = document.getElementById('keyword').value;
    var departemen = document.getElementById('depart1').value;
    
    if (keyword == "") {
        alert ('NIS atau Nama Siswa tidak boleh kosong!');
        document.getElementById("keyword").focus();	
        return false;
    }
    
    if (!isNaN(keyword) && keyword.length < 3) {
        // NIS can be shorter
    } else if (isNaN(keyword) && keyword.length < 3) {
        alert ('Nama minimal 3 karakter!');
        document.getElementById("keyword").focus();	
        return false;
    }
    
    sendRequestText("../library/cari_siswa_rekappembayaran.php", show_panel2, "submit=1&keyword="+keyword+"&departemen="+departemen);
    parent.right.location.href="laprekappembayaran_siswa_blank.php";
}

function pilih_siswa(nis) {
    parent.right.location.href = "laprekappembayaran_siswa_content.php?nis="+nis;
}

function change_urut(urut, tipe) {
    // Not implemented for this simple search
}
</script>