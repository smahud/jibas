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

header('Content-Type: application/vnd.ms-excel'); //IE and Opera  
header('Content-Type: application/x-msexcel'); // Other browsers  
header('Content-Disposition: attachment; filename=Data_Calon_Siswa.xls');
header('Expires: 0');  
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

$departemen = RequestData("departemen", "");
$idProses = RequestData("idproses", "");
$proses = RequestData("proses", "");
$idKelompok = RequestData("idkelompok", "");
$kelompok = RequestData("kelompok", "");
$orderBy = RequestData("orderBy", "s.nama");

$db = new Db();
$db->TryOpenExit();

$sql = "SELECT *,s.info1 AS hp2,s.info2 AS hp3
		  FROM jbsakad.calonsiswa s, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p 
		 WHERE s.idproses = '$idProses'
		   AND s.idkelompok = '$idKelompok'
		   AND k.idproses = p.replid 
		   AND s.idproses = p.replid
		   AND s.idkelompok = k.replid
		 ORDER BY $orderBy";
$result = $db->QueryDb($sql);
		
?>
<html>
<head>
<title>
Daftar Calon Siswa
</title>
</head>
<body>
<table width="700" border="0">
<tr>
<td>
    <table width="100%" border="0">
    <tr>
        <td colspan="2"><div align="center">Daftar Calon Siswa</div></td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td width="24%">Departemen:</td>
        <td width="76%"><?=$departemen?></td>
    </tr>
    <tr>
        <td>Proses Penerimaan:</td>
        <td><?=$proses?></td>
    </tr>
    <tr>
        <td>Kelompok Calon Siswa</td>
        <td><?=$kelompok?></td>
    </tr>
    </table>
</td>
</tr>

<tr>
<td>
    <table border="1">
    <tr height="30">
        <td width="3" rowspan="2" valign="middle" bgcolor="#666666"><div align="center">No.</div></td>
        <td width="20" rowspan="2" valign="middle" bgcolor="#666666"><div align="center">No. Pendaftaran</div></td>
        <td width="20" rowspan="2" valign="middle" bgcolor="#666666"><div align="center">NISN</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">NIK</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">No UN Sebelumnya</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Nama</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Panggilan</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Kelamin</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Tahun Masuk</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Asal Sekolah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">No Ijasah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Tgl Ijasah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Tempat Lahir</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Tanggal Lahir</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Alamat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Kode Pos</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Jarak</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Telpon</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">HP</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Email</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Status</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Kondisi</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Kesehatan</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Bahasa</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Suku</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Agama</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Warga</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Berat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Tinggi</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Gol.Darah</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Anak Ke</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Bersaudara</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Status Anak</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Jml Saudara Kandung</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Jml Saudara Tiri</div></td>
        <td colspan="8" valign="middle" bgcolor="#666666"><div align="center">Ayah</div></td>
        <td colspan="8" valign="middle" bgcolor="#666666"><div align="center">Ibu</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Alamat</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Telpon</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">HP #1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">HP #2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">HP #3</div></td>
<?php
        $arrDataTambahan = array();
        $sql = "SELECT replid, jenis, kolom
                  FROM jbsakad.tambahandata 
                 WHERE aktif = 1
                   AND departemen = '$departemen'
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $arrDataTambahan[] = array($row[0], $row[1]);
            $kolom = $row[2];
            echo "<td rowspan=\"2\" valign=\"middle\" bgcolor=\"#666666\"><div align=\"center\">$kolom</div></td>";
        }
            ?>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Sumbangan 1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Sumbangan 2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 1</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 2</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 3</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 4</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 5</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 6</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 7</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 8</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 9</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Ujian 10</div></td>
        <td rowspan="2" valign="middle" bgcolor="#666666"><div align="center">Status Aktif</div></td>
    </tr>
    <tr height="30">
        <td valign="middle" bgcolor="#666666"><div align="center">Nama</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Status</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Kelahiran</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Tgl Lahir</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Email</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Pendidikan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Pekerjaan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Penghasilan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Nama</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Status</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Kelahiran</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Tgl Lahir</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Email</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Pendidikan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Pekerjaan</div></td>
        <td valign="middle" bgcolor="#666666"><div align="center">Penghasilan</div></td>
    </tr>
<?php
    $cnt=1;
    while ($row = mysqli_fetch_array($result))
    {

        $siswa = "";
        if ($row['replidsiswa'] <> 0) 
        {
            $sql3 = "SELECT nis FROM jbsakad.siswa WHERE replid = $row[replidsiswa]";
            $result3 = $db->QueryDb($sql3);
            $row3 = mysqli_fetch_assoc($result3);
            $siswa = "<br>NIS Siswa: <b>".$row3['nis']."</b>";
        }
?>
            <tr height="25">
                <td width="3" align="center"><?=$cnt?></td>
                <td align="left"><?=$row['nopendaftaran']?></td>
                <td align="left"><?=$row['nisn']?></td>
                <td align="left"><?=$row['nik']?></td>
                <td align="left"><?=$row['noun']?></td>
                <td align="left"><?=$row['nama']?></td>
                <td align="left"><?=$row['panggilan']?></td>
                <td align="left"><?=$row['kelamin']?></td>
                <td align="left"><?=$row['tahunmasuk']?></td>
                <td align="left"><?=$row['asalsekolah']?></td>
                <td align="left"><?=$row['noijasah']?></td>
                <td align="left"><?=$row['tglijasah']?></td>
                <td align="left"><?=$row['tmplahir']?></td>
                <td align="left"><?=$row['tgllahir']?></td>
                <td align="left"><?=$row['alamatsiswa']?></td>
                <td align="left"><?=$row['kodepossiswa']?></td>
                <td align="left"><?=$row['jarak']?></td>
                <td align="left"><?=$row['telponsiswa']?></td>
                <td align="left"><?=$row['hpsiswa']?></td>
                <td align="left"><?=$row['emailsiswa']?></td>
                <td align="left"><?=$row['status']?></td>
                <td align="left"><?=$row['kondisi']?></td>
                <td align="left"><?=$row['kesehatan']?></td>
                <td align="left"><?=$row['bahasa']?></td>
                <td align="left"><?=$row['suku']?></td>
                <td align="left"><?=$row['agama']?></td>
                <td align="left"><?=$row['warga']?></td>
                <td align="left"><?=$row['berat']?></td>
                <td align="left"><?=$row['tinggi']?></td>
                <td align="left"><?=$row['darah']?></td>
                <td align="left"><?=$row['anakke']?></td>
                <td align="left"><?=$row['jsaudara']?></td>
                <td align="left"><?=$row['statusanak']?></td>
                <td align="left"><?=$row['jkandung']?></td>
                <td align="left"><?=$row['jtiri']?></td>
                <td align="left"><?=$row['namaayah']?></td>
                <td align="left"><?=$row['statusayah']?></td>
                <td align="left"><?=$row['tmplahirayah']?></td>
                <td align="left"><?=$row['tgllahirayah']?></td>
                <td align="left"><?=$row['emailayah']?></td>
                <td align="left"><?=$row['pendidikanayah']?></td>
                <td align="left"><?=$row['pekerjaanayah']?></td>
                <td align="left"><?=$row['penghasilanayah']?></td>
                <td align="left"><?=$row['namaibu']?></td>
                <td align="left"><?=$row['statusibu']?></td>
                <td align="left"><?=$row['tmplahiribu']?></td>
                <td align="left"><?=$row['tgllahiribu']?></td>
                <td align="left"><?=$row['emailibu']?></td>
                <td align="left"><?=$row['pendidikanibu']?></td>
                <td align="left"><?=$row['pekerjaanibu']?></td>
                <td align="left"><?=$row['penghasilanibu']?></td>
                <td align="left"><?=$row['alamatortu']?></td>
                <td align="left"><?=$row['telponortu']?></td>
                <td align="left"><?=$row['hportu']?></td>
                <td align="left"><?=$row['hp2']?></td>
                <td align="left"><?=$row['hp3']?></td>
<?php
                $no = $row['nopendaftaran'];
                for($i = 0; $i < count($arrDataTambahan); $i++)
                {
                    $idtambahan = $arrDataTambahan[$i][0];
                    $jenis = $arrDataTambahan[$i][1];

                    if ($jenis == 1 || $jenis == 3)
                        $sql = "SELECT teks FROM jbsakad.tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '$idtambahan'";
                    else
                        $sql = "SELECT filename FROM jbsakad.tambahandatacalon WHERE nopendaftaran = '$no' AND idtambahan = '$idtambahan'";

                    $data = "";
                    $res2 = $db->QueryDb($sql);
                    if ($row2 = mysqli_fetch_row($res2))
                        $data = $row2[0];

                    echo "<td align=\"left\">$data</td>";
                }
?>
                <td align="left"><?=$row['sum1']?></td>
                <td align="left"><?=$row['sum2']?></td>
                <td align="left"><?=$row['ujian1']?></td>
                <td align="left"><?=$row['ujian2']?></td>
                <td align="left"><?=$row['ujian3']?></td>
                <td align="left"><?=$row['ujian4']?></td>
                <td align="left"><?=$row['ujian5']?></td>
                <td align="left"><?=$row['ujian6']?></td>
                <td align="left"><?=$row['ujian7']?></td>
                <td align="left"><?=$row['ujian8']?></td>
                <td align="left"><?=$row['ujian9']?></td>
                <td align="left"><?=$row['ujian10']?></td>
                <td align="center">
<?php
                    if ($row['aktif']==1)
                        echo "Aktif".$siswa;
                    if ($row['aktif']==0)
                        echo "Tidak aktif".$siswa;
?>
                </td>
            </tr>
<?php
            $cnt++;
        } ?>
    </table>

</td>
</tr>
</table>
</body>
</html>