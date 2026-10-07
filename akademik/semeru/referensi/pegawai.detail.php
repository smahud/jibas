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
require_once('../include/getheader2.php');

$replid = RequestData("replid", "0");

$db = new Db();
$db->TryOpenExit(true);

$sql = "SELECT * FROM jbssdm.pegawai WHERE replid='$replid'";
$res = $db->QueryDb($sql);
$row_pegawai = mysqli_fetch_array($res);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pegawai</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/random.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
</head>
<body>

<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
	<td align="left" valign="top" colspan="2">

    <?= getHeader2($db, 'yayasan') ?>
    
    <center><font size="4"><strong>DATA PEGAWAI</strong></font><br /></center><br><br><br>
    
    <strong>Bagian : <?=$row_pegawai['bagian']?></strong><br><br>

    <table width="100%">
    <tr>    	
    	<td align="center" width="150" valign="top">
        <img src="../library/gambar.php?replid=<?=$row_pegawai['replid']?>&table=jbssdm.pegawai" border="0"/>
        <div align="center"><br /><br />Tanda Tangan<br /><br /><br /><br /><br /><br /><br />
        <strong>(<?=$row_pegawai['nama']?><?php if ($row_pegawai['gelar'] <> "")
			  		echo ", ".$row_pegawai['gelar'];
			  	?>)</strong></div>
        </td>
        <td>
    
    <table border="1" class="tab" id="table" width="100%" cellpadding="0" style="border-collapse:collapse" cellspacing="0" >
    <tr>
    	<td valign="top">
        <table border="0" cellpadding="0" style="border-collapse:collapse" cellspacing="0" width="100%">
          <tr class="header" height="30">
            <td align="center"><strong>A. </strong></td>
            <td colspan="4"><strong>DATA PRIBADI PEGAWAI</strong></td>
          </tr>
          <tr height="20">
          	<td rowspan="12"></td>
            <td width="5%">1.</td>
            <td>NIP</td>
            <td>: 
				<?=$row_pegawai['nip']?></td>
          </tr>
          
          <tr height="20">
            <td>2.</td>
            <td colspan="2">Nama Pegawai</td>           
            <td rowspan="11">&nbsp;</td>
            
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td width="20%">a. Lengkap</td>
            <td>:
              <?=$row_pegawai['nama']?>
              <?php if ($row_pegawai['gelar'] <> "")
			  		echo ", ".$row_pegawai['gelar'];
			  ?></td>
          </tr>
          <tr height="20">
            <td>&nbsp;</td>
            <td>b. Panggilan</td>
            <td>:
              <?=$row_pegawai['panggilan']?></td>
          </tr>
          <tr height="20">
            <td >3.</td>
            <td>Jenis Kelamin</td>
            <td >:
              <?php 	if ($row_pegawai['kelamin']=="l")
				echo "Laki-laki"; 
			if ($row_pegawai['kelamin']=="p")
				echo "Perempuan"; 
		?></td>
          </tr>
          <tr height="20">
            <td>4.</td>
            <td>Tempat Lahir</td>
            <td>:
              <?=$row_pegawai['tmplahir']?></td>
          </tr>
          <tr height="20">
            <td>5.</td>
            <td>Tanggal Lahir</td>
            <td>:
              <?=LongDateFormat($row_pegawai['tgllahir']) ?></td>
          </tr>
          <tr height="20">
            <td>6.</td>
            <td >Agama</td>
            <td>:
              <?=$row_pegawai['agama']?></td>
          </tr>
          <tr height="20">
            <td>7.</td>
            <td>Suku</td>
            <td>:
              <?=$row_pegawai['suku']?></td>
            
          </tr>
          <tr height="20">
            <td>8.</td>
            <td>Nomor Identitas</td>
            <td>:
              <?=$row_pegawai['noid']?></td>
            
          </tr>
          <tr height="20">
            <td>9.</td>
            <td>Status</td>
            <td>:
              <?php	if($row_pegawai['nikah']=="menikah")
					echo "Menikah";
				if($row_pegawai['nikah']=="belum")
					echo "Belum Menikah";
				if($row_pegawai['nikah']=="tak_ada")
					echo "";?></td>
           
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr class="header" height="30">
            <td width="5%" align="center"><strong>B. </strong></td>
            <td colspan="5"><strong>KETERANGAN TEMPAT TINGGAL</strong></td>
          </tr>
          <tr height="20">
            <td rowspan="5"></td>
            <td>10.</td>
            <td>Alamat</td>
            <td colspan="2">:
              <?=$row_pegawai['alamat']?></td>
           
          </tr>
          <tr height="20">
            <td>11.</td>
            <td>Telepon</td>
            <td colspan="2">:
              <?=$row_pegawai['telpon']?></td>
            
          </tr>
          <tr height="20">
            <td>12.</td>
            <td>Handphone</td>
            <td colspan="2">:
              <?=$row_pegawai['handphone']?></td>
            
          </tr>
          <tr height="20">
            <td>13.</td>
            <td>Email</td>
            <td colspan="2">:
              <?=$row_pegawai['email']?></td>
           
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr height="30" class="header">
            <td align="center"><strong>C.</strong></td>
            <td colspan="5"><strong>KETERANGAN LAINNYA</strong></td>
          </tr>
          <tr height="20">
          	<td></td>
            <td>14.</td>
            <td>Keterangan</td>
            <td colspan="2">: <?=$row_pegawai['keterangan']?></td>
          </tr>        
        </table></td>
  	</tr>
	</table>
    </td>
    </tr>
    </table>
  	</td>
</tr>
</table>


</body>
</html>