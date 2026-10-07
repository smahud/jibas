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
require_once('../../library/logger.php');
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$nip = RequestData("nip", "");
$nama = RequestData("nama", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pilih Pelajaran</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js?r=<?=filemtime('../../script/tools.js')?>"></script>
    <script language="javascript" src="../../script/stringutil.js?r=<?=filemtime('../../script/stringutil.js')?>"></script>
    <script language="javascript" src="../../script/dateutil.js?r=<?=filemtime('../../script/dateutil.js')?>"></script>
    <script language="javascript" src="../../script/vldr.js?r=<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rapor.nilai.pelajaran.js?<?=filemtime('rapor.nilai.pelajaran.js')?>"></script>
</head>
<body style="padding: 10px; margin: 0px; background-color: #efefef;">
<input type="hidden" id="departemen" value="<?= $departemen ?>">
<input type="hidden" id="idtahunajaran" value="<?= $idTahunAjaran ?>">
<input type="hidden" id="tahunajaran" value="<?= $tahunAjaran ?>">
<input type="hidden" id="idsemester" value="<?= $idSemester ?>">
<input type="hidden" id="semester" value="<?= $semester ?>">
<input type="hidden" id="idtingkat" value="<?= $idTingkat ?>">
<input type="hidden" id="tingkat" value="<?= $tingkat ?>">
<input type="hidden" id="idkelas" value="<?= $idKelas ?>">
<input type="hidden" id="kelas" value="<?= $kelas ?>">
<input type="hidden" id="nip" value="<?= $nip ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">

<?php
$sql = "SELECT DISTINCT aturannhb.idpelajaran, pelajaran.nama 
 	      FROM jbsakad.aturannhb aturannhb, jbsakad.pelajaran pelajaran 
	     WHERE aturannhb.nipguru = '$nip' 
           AND idpelajaran=pelajaran.replid 
		   AND pelajaran.departemen = '$departemen' 
		   AND aturannhb.idtingkat = '$idTingkat' 
		   AND aturannhb.aktif = 1 
		   ORDER BY pelajaran.nama";
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) == 0)
{
    echo "<span class='fg-secondary fs-13'>Tidak ditemukan data pelajaran<br><br>";
    echo "Tambah <b>Aturan Perhitungan Rapor</b> dan <b>Grading</b> untuk pelajaran yang diajar oleh guru <b>$nama</b> melalui menu <b>Guru & Pelajaran</b></span>";    
    echo "</body></html>";
    exit();
}

echo "<b>Pilih Pelajaran &amp; Aspek Penilaian</b><br>";   
$no = 0;
while($row = mysqli_fetch_row($res))
{
    $idPelajaran = $row[0];
    $pelajaran = $row[1];

    echo "<br><span class='fs-13'>$pelajaran</span><br>";

    $sql = "SELECT DISTINCT a.dasarpenilaian, dp.keterangan
              FROM jbsakad.aturannhb a, jbsakad.dasarpenilaian dp
             WHERE a.dasarpenilaian = dp.dasarpenilaian 
               AND dp.aktif = 1
               AND a.nipguru = '$nip' 
               AND a.idpelajaran = '$idPelajaran'
               AND a.idtingkat = '$idTingkat' 
               AND a.aktif = 1
             ORDER BY keterangan";	
    $res2 = $db->QueryDb($sql); 
    while ($row2 = mysqli_fetch_row($res2))
    {
        $no += 1;
        $dasarPenilaian = $row2[0];
        $judulPenilaian = $row2[1];

        $lsData = [];
        $lsData[] = $idPelajaran;      // 0
        $lsData[] = $pelajaran;        // 1
        $lsData[] = $dasarPenilaian;   // 2
        $lsData[] = $judulPenilaian;   // 3
        $lsData[] = $departemen;       // 4
        $lsData[] = $idTahunAjaran;    // 5
        $lsData[] = $tahunAjaran;      // 6
        $lsData[] = $idSemester;       // 7
        $lsData[] = $semester;         // 8
        $lsData[] = $idTingkat;        // 9
        $lsData[] = $tingkat;          // 10
        $lsData[] = $idKelas;          // 11
        $lsData[] = $kelas;            // 12
        $lsData[] = $nip;              // 13
        $lsData[] = $nama;             // 14
        $data64 = base64_encode(json_encode($lsData));

        echo "&nbsp;&nbsp;&bull;&nbsp;";
        echo "<a class='ablue' style='line-height: 20px;' onclick='showPenentuan($no)' title='penentuan nilai rapor'>$judulPenilaian</a><br>";
        echo "<input type='hidden' id='data$no' value='$data64'>";
    }
}
?>
</body>
</html>