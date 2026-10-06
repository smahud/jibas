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
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/rupiah.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');

$sql = "SELECT c.nis, c.nama, c.panggilan, c.tahunmasuk, c.idkelas, c.suku, c.agama, c.status, c.kondisi, c.kelamin,
			   c.tmplahir, DAY(c.tgllahir) AS tanggal, MONTH(c.tgllahir) AS bulan, YEAR(c.tgllahir) AS tahun, c.tgllahir, c.warga,
			   c.anakke, c.jsaudara, c.bahasa, c.berat, c.tinggi, c.darah, c.foto, c.alamatsiswa, c.kodepossiswa, c.telponsiswa,
			   c.hpsiswa, c.emailsiswa, c.kesehatan, c.asalsekolah, c.ketsekolah, c.namaayah, c.namaibu, c.almayah, c.almibu,
			   c.pendidikanayah, c.pendidikanibu, c.pekerjaanayah, c.pekerjaanibu, c.wali, c.penghasilanayah, c.penghasilanibu,
			   c.alamatortu, c.telponortu, c.hportu, c.info1, c.info2, c.emailayah, c.emailibu, c.alamatsurat, c.keterangan, t.departemen, t.tahunajaran,
			   k.kelas, i.tingkat, c.nisn, c.nik,c.noun,c.statusanak,c.jkandung,c.jtiri,c.jarak,
               c.noijasah,c.tglijasah,c.statusayah,c.statusibu,c.tmplahirayah,c.tmplahiribu,c.tgllahirayah,c.tgllahiribu,c.hobi,
               IF(c.foto IS NULL, '', TO_BASE64(c.foto)) AS ffoto
		  FROM jbsakad.siswa c, jbsakad.kelas k, jbsakad.tahunajaran t, jbsakad.tingkat i
		 WHERE c.replid = $replid AND k.replid = c.idkelas AND k.idtahunajaran = t.replid AND k.idtingkat = i.replid";
$res = $db->QueryDb($sql);
$row = mysqli_fetch_array($res);
?>
<div id='dvInfoSiswa' style="padding: 10px; width: 780px;">

<table width="100%" align="left" cellpadding="3">
<tr>
    <th width="26%" align="left" scope="row">
        <strong>Departemen</strong>
    </th>
    <td align="left"><strong>:&nbsp;
            <?=$row['departemen']?>
        </strong>
    </td>
</tr>
<tr>
    <th align="left" scope="row"><strong>Tahun Ajaran</strong></th>
    <td align="left"><strong>:&nbsp;
            <?=$row['tahunajaran']?>
        </strong>
    </td>
</tr>
<tr>
    <th align="left" scope="row"><strong>Kelas</strong></th>
    <td align="left"><strong>:&nbsp;
            <?=$row['tingkat']." - ".$row['kelas']?>
        </strong>
    </td>
</tr>
<tr>
    <td><strong>NIS</strong></td>
    <td><strong>:&nbsp;
        <?=$row['nis']?></strong>
    </td>
    <td width="50%" rowspan="2" align="right" valign="bottom">
        <span style='margin-left: 30px;' class='cur-hand hide-in-report' onclick='cetakInfoSiswa()'>
            <img src='../images/ico/print.png'>&nbsp;cetak
        </span>
    </td>
</tr>
</table>

<br><br>

<table border="0" cellpadding="3" style="border-collapse:collapse" cellspacing="0" width="100%" id="table">
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Pribadi Siswa</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td width="5%" rowspan="19" bgcolor="#FFFFFF"></td>
    <td>1.</td>
    <td>N I S N</td>
    <td>: <?=$row['nisn']?></td>
    <td colspan="2" rowspan="19" bgcolor="#FFFFFF" valign='top'>
<?php   if (!empty($row['ffoto']))
            echo "<img src='data:image/jpeg;base64,". $row['ffoto'] . "' border='0'>";
        else
            echo "<img src='" . NoUserImage() . "' border='0'>";
?>            
    </td>
</tr>
<tr height="20">
    <td>&nbsp;</td>
    <td>N I K</td>
    <td>: <?=$row['nik']?></td>
</tr>
<tr height="20">
    <td>&nbsp;</td>
    <td>No. UN Sebelumnya</td>
    <td>: <?=$row['noun']?></td>
</tr>
<tr height="20">
    <td width="5%">2.</td>
    <td colspan="2">Nama Peserta Didik</td>
    <td rowspan="14" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td width="20%">a. Lengkap</td>
    <td>:
        <?=$row['nama']?></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td>b. Panggilan</td>
    <td>:
        <?=$row['panggilan']?></td>
</tr>
<tr height="20">
    <td >3.</td>
    <td>Jenis Kelamin</td>
    <td >:
        <? 	if ($row['kelamin']=="l")
            echo "Laki-laki";
        if ($row['kelamin']=="p")
            echo "Perempuan";
        ?></td>
</tr>
<tr height="20">
    <td>4.</td>
    <td>Tempat Lahir</td>
    <td>:
        <?=$row['tmplahir']?></td>
</tr>
<tr height="20">
    <td>5.</td>
    <td>Tanggal Lahir</td>
    <td>:
        <?=LongDateFormat($row['tgllahir']) ?></td>
</tr>
<tr height="20">
    <td>6.</td>
    <td >Agama</td>
    <td>:
        <?=$row['agama']?></td>
</tr>
<tr height="20">
    <td>7.</td>
    <td>Kewarganegaraan</td>
    <td>:
        <?=$row['warga']?></td>
</tr>
<tr height="20">
    <td>8.</td>
    <td>Anak ke</td>
    <td>:
        <? if ($row['anakke']!=0) { echo $row['anakke']; }?></td>
</tr>
<tr height="20">
    <td>9.</td>
    <td>Dari</td>
    <td>:
        <? if ($row['jsaudara']!=0) { echo $row['jsaudara']; }?> bersaudara</td>
</tr>
<tr height="20">
    <td>10.</td>
    <td>Status Anak</td>
    <td>:
        <?=$row['statusanak']?></td>
</tr>
<tr height="20">
    <td>11.</td>
    <td>Jumlah Saudara Kandung</td>
    <td>:
        <?=$row['jkandung']?>&nbsp;orang</td>
</tr>
<tr height="20">
    <td>12.</td>
    <td>Jumlah Saudara Tiri</td>
    <td>:
        <?=$row['jtiri']?>&nbsp;orang</td>
</tr>
<tr height="20">
    <td>13.</td>
    <td>Kondisi Siswa</td>
    <td>:
        <?=$row['kondisi']?></td>
</tr>
<tr height="20">
    <td>14.</td>
    <td>Status Siswa</td>
    <td>:
        <?=$row['status']?></td>
</tr>
<tr height="20">
    <td>15.</td>
    <td>Bahasa Sehari-hari</td>
    <td>:
        <?=$row['bahasa']?></td>
</tr>
<tr>
    <td bgcolor="#FFFFFF" colspan="4">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Tempat Tinggal</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="6" bgcolor="#FFFFFF"></td>
    <td>16.</td>
    <td>Alamat</td>
    <td colspan="2">:
        <?=$row['alamatsiswa']?></td>
</tr>
<tr height="20">
    <td>17.</td>
    <td>Kode Pos</td>
    <td colspan="2">:
        <?=$row['kodepossiswa']?>
    </td>
</tr>
<tr height="20">
    <td>18.</td>
    <td>Jarak ke Sekolah</td>
    <td colspan="2">:
        <?=$row['jarak']?>&nbsp;km
    </td>
</tr>
<tr height="20">
    <td>19.</td>
    <td>Telepon</td>
    <td colspan="2">:
        <?=$row['telponsiswa']?></td>
</tr>
<tr height="20">
    <td>20.</td>
    <td>Handphone</td>
    <td colspan="2">:
        <?=$row['hpsiswa']?></td>
</tr>
<tr height="20">
    <td>21.</td>
    <td>Email</td>
    <td colspan="2">:
        <?=$row['emailsiswa']?></td>
</tr>
<tr>
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF"><font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;<font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Kesehatan</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" /></td>
</tr>
<tr height="20">
    <td rowspan="5" bgcolor="#FFFFFF"></td>
    <td>22.</td>
    <td >Berat Badan</td>
    <td colspan="2">:
        <? if ($row['berat']!=0) { echo $row['berat']." Kg"; }?></td>
</tr>
<tr height="20">
    <td>23.</td>
    <td>Tinggi Badan</td>
    <td colspan="2">:
        <? if ($row['tinggi']!=0) { echo $row['tinggi']." cm"; }?></td>
</tr>
<tr height="20">
    <td>24.</td>
    <td >Golongan Darah</td>
    <td colspan="2">:
        <?=$row['darah']?></td>
</tr>
<tr height="20">
    <td>25.</td>
    <td >Riwayat Penyakit</td>
    <td colspan="2">:
        <?=$row['kesehatan']?></td>
</tr>
<tr >
    <td colspan="4" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Pendidikan Sebelumnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" />
    </td>
</tr>
<tr height="20">
    <td rowspan="4" bgcolor="#FFFFFF"></td>
    <td>26.</td>
    <td >Asal Sekolah</td>
    <td colspan="2">:
        <?=$row['asalsekolah']?></td>
</tr>
<tr height="20">
    <td>27.</td>
    <td >No Ijasah</td>
    <td colspan="2">:
        <?=$row['noijasah']?>
    </td>
</tr>
<tr height="20">
    <td>28.</td>
    <td >Tgl Ijasah</td>
    <td colspan="2">:
        <?=$row['tglijasah']?>
    </td>
</tr>
<tr height="20">
    <td>29.</td>
    <td >Keterangan</td>
    <td colspan="2">:
        <?=$row['ketsekolah']?></td>
</tr>
<tr >
    <td colspan="5" bgcolor="#FFFFFF">&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="5" align="left" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Orangtua</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" />
    </td>
</tr>
<tr height="20">
    <td rowspan="15" bgcolor="#FFFFFF"></td>
    <td bgcolor="#FFFFFF">&nbsp;</td>
    <td><strong>Orangtua</strong></td>
    <td width="30%"><div align="center"><strong>Ayah</strong></div></td>
    <td><div align="center"><strong>Ibu</strong></div></td>
</tr>
<tr height="20">
    <td>30.</td>
    <td >Nama</td>
    <td >:
        <?=$row['namaayah']?>
        <?
        if ($row['almayah']==1)
            echo "&nbsp;(alm)";
        ?></td>
    <td><?=$row['namaibu']?>
        <?
        if ($row['almibu']==1)
            echo "&nbsp;(alm)";
        ?></td>
</tr>
<tr height="20">
    <td>31.</td>
    <td>Status</td>
    <td>:&nbsp;<?=$row['statusayah']?></td>
    <td><?=$row['statusibu']?></td>
</tr>
<tr height="20">
    <td>32.</td>
    <td>Tempat Lahir</td>
    <td>:&nbsp;<?=$row['tmplahirayah']?></td>
    <td><?=$row['tmplahiribu']?></td>
</tr>
<tr height="20">
    <td>33.</td>
    <td>Tanggal Lahir</td>
    <td>:&nbsp;<?=$row['tgllahirayah']?></td>
    <td><?=$row['tgllahiribu']?></td>
</tr>
<tr height="20">
    <td>34.</td>
    <td >Pendidikan</td>
    <td >:
        <?=$row['pendidikanayah']?></td>
    <td><?=$row['pendidikanibu']?></td>
</tr>
<tr height="20">
    <td>35.</td>
    <td >Pekerjaan</td>
    <td >:
        <?=$row['pekerjaanayah']?></td>
    <td><?=$row['pekerjaanibu']?></td>
</tr>
<tr height="20">
    <td>36.</td>
    <td >Penghasilan</td>
    <td >:
        <? if ($row['penghasilanayah']!=0){ echo FormatRupiah($row['penghasilanayah']) ; } ?></td>
    <td><? if ($row['penghasilanibu']!=0){ echo FormatRupiah($row['penghasilanibu']) ; } ?></td>
</tr>
<tr height="20">
    <td>37.</td>
    <td >Email Orang Tua</td>
    <td >: <?=$row['emailayah']?></td>
    <td><?=$row['emailibu']?></td>
</tr>
<tr height="20">
    <td>38. </td>
    <td >Nama Wali</td>
    <td colspan="2">:
        <?=$row['wali']?></td>
</tr>
<tr >
    <td>39.</td>
    <td >Alamat</td>
    <td colspan="2">:
        <?=$row['alamatortu']?></td>
</tr>
<tr height="20">
    <td>40.</td>
    <td >Telepon</td>
    <td colspan="2">:
        <?=$row['telponortu']?></td>
</tr>
<tr height="20">
    <td>41.</td>
    <td >Handphone #1</td>
    <td colspan="2">:
        <?=$row['hportu']?></td>
</tr>
<tr height="20">
    <td>42.</td>
    <td >Handphone #2</td>
    <td colspan="2">:
        <?=$row['info1']?></td>
</tr>
<tr height="20">
    <td>43.</td>
    <td >Handphone #3</td>
    <td colspan="2">:
        <?=$row['info2']?></td>
</tr>
<tr height="20">
    <td bgcolor="#FFFFFF" >&nbsp;</td>
</tr>
<tr height="30">
    <td colspan="6" bgcolor="#FFFFFF">
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
        <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Keterangan Lainnya</strong></font>
        <hr width="300" style="line-height:1px; border-style:dashed" align="left" />
    </td>
</tr>
<tr height="20">
    <td rowspan="3" bgcolor="#FFFFFF"></td>
    <td>44.</td>
    <td>Hobi</td>
    <td colspan="2">:
        <?=$row['hobi']?></td>
</tr>
<tr height="20">
    <td>45.</td>
    <td>Alamat Surat</td>
    <td colspan="2">:
        <?=$row['alamatsurat']?></td>
</tr>
<tr height="20">
    <td>46.</td>
    <td >Keterangan</td>
    <td colspan="2">:
        <?=$row['keterangan']?></td>
</tr>
<?php
    $nis = $row['nis'];
    $sql = "SELECT ds.replid, ds.idtambahan, td.kolom, ds.jenis, ds.teks, ds.filename 
              FROM jbsakad.tambahandatasiswa ds, jbsakad.tambahandata td
             WHERE ds.idtambahan = td.replid
               AND ds.nis = '$nis'
             ORDER BY td.urutan   ";
    $res2 = $db->QueryDb($sql);
    $ntambahandata = mysqli_num_rows($res2);

    if ($ntambahandata > 0)
    {
        ?>
        <tr height="20">
            <td bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
        <tr height="30">
            <td colspan="6" bgcolor="#FFFFFF">
                <font size="3" face="Verdana, Arial, Helvetica, sans-serif" style="background-color:#ffcc66">&nbsp;</font>&nbsp;
                <font size="3" face="Verdana, Arial, Helvetica, sans-serif" color="Gray"><strong>Data Tambahan</strong></font>
                <hr width="300" style="line-height:1px; border-style:dashed" align="left"/>
            </td>
        </tr>
<?php
        $no = 46;
        $first = true;
        while($row2 = mysqli_fetch_array($res2))
        {
            $no += 1;
            $replid = $row2['replid'];
            $kolom = $row2['kolom'];
            $jenis = $row2['jenis'];

            if ($jenis == 1 || $jenis == 3)
            {
                $data = $row2['teks'];
            }
            else
            {
                $filename = $row2['filename'];
                $data = "<a href='tambahandata.file.php?replid=$replid'>$filename</a>";
            }

            $rowspan = "";
            if ($first)
            {
                $rowspan = "<td rowspan='$ntambahandata' bgcolor='#FFFFFF'></td>";
                $first = false;
            }
            ?>
            <tr height="20">
                <?=$rowspan?>
                <td><?=$no?>.</td>
                <td><?=$kolom?></td>
                <td colspan="2">:
                    <?=$data?></td>
            </tr>
        <?  }
    }
    ?>
</table>


</div>