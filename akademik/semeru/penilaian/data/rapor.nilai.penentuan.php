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

$data64 = RequestData("data", "");
$lsData = json_decode(base64_decode($data64), true);

$idPelajaran = $lsData[0];
$pelajaran = $lsData[1];
$dasarPenilaian = $lsData[2];
$judulPenilaian = $lsData[3];
$departemen = $lsData[4];
$idTahunAjaran = $lsData[5];
$tahunAjaran = $lsData[6];
$idSemester = $lsData[7];
$semester = $lsData[8];
$idTingkat = $lsData[9];
$tingkat = $lsData[10];
$idKelas = $lsData[11];
$kelas = $lsData[12];
$nip = $lsData[13];
$nama = $lsData[14];

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Penentuan Nilai Rapor</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css?<?=filemtime('../../style/toast.css')?>">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/toast.js?r=<?=filemtime('../../script/toast.js')?>"></script>
    <script language="javascript" src="../../script/tools.js?r=<?=filemtime('../../script/tools.js')?>"></script>
    <script language="javascript" src="../../script/stringutil.js?r=<?=filemtime('../../script/stringutil.js')?>"></script>
    <script language="javascript" src="../../script/dateutil.js?r=<?=filemtime('../../script/dateutil.js')?>"></script>
    <script language="javascript" src="../../script/vldr.js?r=<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="rapor.nilai.penentuan.js?r=<?=filemtime('rapor.nilai.penentuan.js')?>"></script>
</head>
<body style="padding: 10px; margin: 0px;">
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
<input type="hidden" id="idpelajaran" value="<?= $idPelajaran ?>">
<input type="hidden" id="pelajaran" value="<?= $pelajaran ?>">
<input type="hidden" id="dasarpenilaian" value="<?= $dasarPenilaian ?>">
<input type="hidden" id="judulpenilaian" value="<?= $judulPenilaian ?>">

<?php
echo "<div style='position: relativve; width: 100%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='250'>";
echo "<span class='fg-secondary'>Pelajaran</span><br>";
echo "<span class='fs-16 fs-bold'>" . $pelajaran . "</span>";
echo "</td>";
echo "<td width='180'>";
echo "<span class='fg-secondary'>Aspek Penilaian</span><br>";
echo "<span class='fs-16 fs-bold'>" . $judulPenilaian . "</span>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<div style='position: absolute; top: 30px; right: 10px;'>";
echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
echo "</span>&nbsp;&nbsp;";
echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
echo "</span>";
echo "</div>";

echo "</div>";

//cek keberadaan nap dan idinfo
$idInfo = 0;
$napExist = 0;
$nilaiMin = "";
$sql = "SELECT replid, nilaimin 
          FROM jbsakad.infonap 
         WHERE idpelajaran = '$idPelajaran' 
           AND idsemester = '$idSemester' 
           AND idkelas = '$idKelas'";
$res = $db->QueryDb($sql);
if (mysqli_num_rows($res) > 0)
{
	$row = mysqli_fetch_row($res);
	$idInfo = $row[0];
	$nilaiMin = $row[1];

	$sql = "SELECT COUNT(n.replid)
			  FROM jbsakad.aturannhb a, jbsakad.kelas k, jbsakad.nap n
			 WHERE n.idaturan = a.replid 
               AND a.nipguru = '$nip' 
               AND a.idtingkat = k.idtingkat 
               AND k.replid = '$idKelas'
			   AND a.idpelajaran = '$idPelajaran' 
               AND a.dasarpenilaian = '$dasarPenilaian'
			   AND n.idinfo = '$idInfo' ";
	$res = $db->QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$napExist = $row[0];
}

// get jumlah pengujian
$lsAturanNhb = [];
$sql = "SELECT j.jenisujian as jenisujian, a.bobot as bobot, a.replid, a.idjenisujian 
		  FROM jbsakad.aturannhb a, jbsakad.jenisujian j, jbsakad.kelas k 
		 WHERE a.idtingkat = k.idtingkat 
           AND k.replid = '$idKelas' 
           AND a.nipguru = '$nip' 
		   AND a.idpelajaran = '$idPelajaran' 
           AND a.dasarpenilaian = '$dasarPenilaian' 
		   AND a.idjenisujian = j.replid 
           AND a.aktif = 1 
	  ORDER BY j.urutan, j.jenisujian";  
$res = $db->QueryDb($sql);
$jumNhb = mysqli_num_rows($res);
while ($row = mysqli_fetch_assoc($res))
{
	$lsItem = [$row['replid'], $row['bobot'], $row['idjenisujian'], $row['jenisujian']];
	$lsAturanNhb[] = $lsItem;
}

//Ambil nilai grading
$lsGrading = [];
$sql = "SELECT grade, nmin, nmax 
		  FROM jbsakad.aturangrading a, jbsakad.kelas k 
		 WHERE a.idpelajaran = '$idPelajaran' 
           AND a.idtingkat = k.idtingkat 
           AND k.replid = '$idKelas' 
		   AND a.dasarpenilaian = '$dasarPenilaian' 
           AND a.nipguru = '$nip'
	  ORDER BY nmin DESC";
$res = $db->QueryDb($sql);
while ($row = mysqli_fetch_array($res)) 
{
	$lsGrading[] = [ $row['grade'], $row['nmin'], $row['nmax'] ];
}
$nGrading = count($lsGrading);

echo "<br>";
echo "<span class='fs-13 fg-blue'>Nilai Kriteria Ketuntasan Minimal (KKM):&nbsp;&nbsp;</span>";
echo "<input type='text' id='nilaikkm' class='inputbox fs-18' style='width: 80px' maxlength='5' value='$nilaiMin'>";
echo "<input type='hidden' id='idinfo' value='$idInfo'>";
echo "<br><br>";

echo "<div id='dvTableDaftar'>";
echo "<table class='tab tabShadow' id='tableDaftar' width='100%' align='center'>";
echo "<tr>";
echo "<td class='bg-table-header' align='center' width='4%' rowspan='2'>No</td>";
echo "<td class='bg-table-header' align='center' width='25%' rowspan='2'>Siswa</td>";
echo "<td class='bg-table-header' align='center' width='*' colspan='$jumNhb'>Nilai Akhir</td>";
echo "<td class='bg-table-header' align='center' width='18%' colspan='2'>Nilai Rapor</td>";
echo "</tr>";
echo "<tr>";

$tw = 100 - 4 - 25 - 18;
$wcol = $tw / $jumNhb;
for($i = 0; $i < $jumNhb; $i++ )
{
    $idAturan = $lsAturanNhb[$i][0];
    $bobot = $lsAturanNhb[$i][1];
    $idJenisUjian = $lsAturanNhb[$i][2];
    $jenisUjian = $lsAturanNhb[$i][3];
    
    echo "<td class='bg-table-header' align='center' width='$wcol%'>";
    echo "<span class='fg-yellow'>$jenisUjian</span> <span class='fg-cyan'>(<b>$bobot</b>)</span>";
    echo "</td>";
}
echo "<td class='bg-table-header' align='center' width='9%'><b>Angka</b></td>";
echo "<td class='bg-table-header' align='center' width='9%'><b>Huruf</b></td>";
echo "</tr>";

$lsRerataKelas = [];
for($i = 0; $i < $jumNhb; $i++)
{
    $lsRerataKelas[] = 0;
}

$sql = "SELECT replid, nis, nama 
          FROM jbsakad.siswa 
         WHERE idkelas = '$idKelas' 
           AND aktif = 1 
         ORDER BY nama";
$res = $db->QueryDb($sql);
$cnt = 0;
$nSiswa = mysqli_num_rows($res);
while ($row = mysqli_fetch_assoc($res))
{
    $cnt += 1;

    $replid = $row["replid"];
    $nis = $row["nis"];
    $nama = $row["nama"];

    echo "<tr>";
    echo "<td align='center' class='bg-table-number-column'>$cnt</td>";
    echo "<td style='position: relative;'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "<input type='hidden' id='replid$cnt' value='$replid'>";
    echo "<input type='hidden' id='nis$cnt' value='$nis'>";
    echo "<input type='hidden' id='nama$cnt' value='$nama'>";

    echo "<span style='position: absolute; right: 5px; top: 5px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa($replid)'>&nbsp;&nbsp;";
    echo "</span>";
    
    echo "</td>";

    $totalNau = 0;
    $totalBobot = 0;
    for($i = 0; $i < $jumNhb; $i++ )
    {
        $idAturan = $lsAturanNhb[$i][0];
        $bobot = $lsAturanNhb[$i][1];
        $idJenisUjian = $lsAturanNhb[$i][2];
        $jenisUjian = $lsAturanNhb[$i][3];
        $totalBobot += $bobot;

        $sql = "SELECT n.nilaiAU as nilaiujian 
                FROM jbsakad.nau n, jbsakad.aturannhb a 
                WHERE n.idpelajaran = '$idPelajaran' 
                  AND n.idkelas = '$idKelas' 
                  AND n.nis = '$nis' 
                  AND n.idsemester = '$idSemester' 
                  AND n.idjenis = '$idJenisUjian' 
                  AND n.idaturan = a.replid 
                  AND a.replid = '$idAturan'";
		$res2 = $db->QueryDb($sql);
		if ($row2 = mysqli_fetch_assoc($res2))
		{
			$nilaiUjian = $row2["nilaiujian"];

            $totalNau += $nilaiUjian * $bobot;
            $lsRerataKelas[$i] += $nilaiUjian;
		}
		else
		{
			$nilaiUjian = "";
		}

		echo "<td align='center'><span class='fs-14 ff-courier fst-bold'>$nilaiUjian</span></td>";
    }

    $sql = "SELECT n.nilaihuruf, n.nilaiangka
              FROM jbsakad.nap n, jbsakad.aturannhb a, jbsakad.infonap i 
   			 WHERE n.idinfo = i.replid 
               AND n.idaturan = a.replid   
			   AND n.nis = '$nis' 
			   AND i.idpelajaran = '$idPelajaran' 
			   AND i.idsemester = '$idSemester'
			   AND i.idkelas = '$idKelas'
               AND a.nipguru = '$nip'
			   AND a.dasarpenilaian = '$dasarPenilaian'";
    if ($idInfo != "")
        $sql .= " AND i.replid = '$idInfo'";
    $res3 = $db->QueryDb($sql);

    $dataRaporExist = false;
    $nilaiHuruf = "";
    $nilaiAngka = "";
    if ($row3 = mysqli_fetch_assoc($res3))
    {
        $dataRaporExist = true;
        $nilaiHuruf = $row3["nilaihuruf"];
        $nilaiAngka = $row3["nilaiangka"];
    }
    
    if (!$dataRaporExist)
    {
        if ($totalBobot == 0)
        {
            $nilaiAngka = 0;
            $nilaiHuruf = $lsGrading[$nGrading - 1][0];
        }
        else
        {
            $nilaiAngka = round($totalNau / $totalBobot, 2);
            for ($i = 0; $i < $nGrading; $i++)
            {
                $nmin = (float) $lsGrading[$i][1];
                $nmax = (float) $lsGrading[$i][2];
                if ($nilaiAngka >= $nmin && $nilaiAngka < $nmax)
                {
                    $nilaiHuruf = $lsGrading[$i][0];
                    break;
                }
            }       
        }   
    }
    
    $warna = "fg-black";
    $title = "";
    if ($nilaiAngka < $nilaiMin)
    {
        $warna = "fg-red";
        $title = "Nilai lebih kecil daripada nilai KKM";
    }

    echo "<td align='center'>";
    echo "<input type='text' id='nilaiangka$cnt' class='inputbox fs-14 $warna' style='width: 80px' title='$title' maxlength='5' value='$nilaiAngka'>";
    echo "</td>";
    echo "<td align='center'>";
    echo "<select id='nilaihuruf$cnt' class='inputbox fs-14' style='width: 80px'>";
    for($i = 0; $i < $nGrading; $i++)
    {
        $grade = $lsGrading[$i][0];
        $sel = $nilaiHuruf == $grade ? "selected" : "";
        echo "<option value='$grade' $sel>$grade</option>";
    }
    echo "</select>";
    echo "</td>";
    echo "</tr>";   
}
echo "<tr style='height: 40px'>";
echo "<td colspan='2' class='bg-gray-100' align='right'><b>Rerata Kelas</b></td>";
for($i = 0; $i < $jumNhb; $i++)
{
    $rerata = 0;
    if ($nSiswa > 0)
        $rerata = $lsRerataKelas[$i] / $nSiswa;
    $rerata = round($rerata, 2);

    $warna = "";
    $title = "";
    if ($rerata < $nilaiMin)
    {
        $warna = "fg-red";
        $title = "Nilai lebih kecil daripada nilai KKM";
    }

    echo "<td align='center' class='bg-gray-100'><span class='$warna fs-14 ff-courier fst-bold'>$rerata</span></td>";
}
echo "<td colspan='2' class='bg-gray-100'></td>";
echo "</tr>";
echo "</table>";
echo "<input type='hidden' id='nsiswa' value='$nSiswa'>";

echo "<br><br>";
echo "<div style='width: 99%; position: relative;'>";

if ($napExist)
{
    echo "<input type='button' id='btRecount' value=' Hitung Ulang &amp; Simpan Nilai Rapor ' class='dialogButtonGreen' onclick='recountNilaiRapor()'>&nbsp;&nbsp;";
    echo "<input type='button' id='btHapus' value=' Hapus Nilai &amp; Komentar Rapor ' class='dialogButtonNegative' onclick='deleteNilaiRapor()'>";
}

echo "<span style='position: absolute; top: 0px; right: 5px;'>";
echo "<input type='button' id='btSimpan' value='  Simpan Nilai Rapor  ' class='dialogButtonPositive' onclick='simpanNilaiRapor()'>";
echo "</span>";

echo "</div>";

echo "</div>";
?>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>

<?php
require_once("../../library/toast.viewer.php");
?>