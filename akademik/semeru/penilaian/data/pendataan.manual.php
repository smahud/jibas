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
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('pendataan.manual.func.php');
require_once('penilaian.rerata.func.php');

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
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$idJenisUjian = RequestData("idjenisujian", "");
$jenisUjian = RequestData("jenisujian", "");
$idAturanNhb = RequestData("idaturannhb", "");
$aspek = RequestData("aspek", "");
$namaAspek = RequestData("namaaspek", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Pendataan Nilai Manual</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css?<?=filemtime('../../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/tooltips.css">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/tooltips.js"></script>
    <script language="javascript" src="../../script/tools.js?r=<?=filemtime('../../script/tools.js')?>"></script>
    <script language="javascript" src="../../script/stringutil.js?r=<?=filemtime('../../script/stringutil.js')?>"></script>
    <script language="javascript" src="../../script/dateutil.js?r=<?=filemtime('../../script/dateutil.js')?>"></script>
    <script language="javascript" src="../../script/vldr.js?r=<?=filemtime('../../script/vldr.js')?>"></script>
    <script language="javascript" src="../../script/qsbuilder.js?r=<?=filemtime('../../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="pendataan.manual.js?<?=filemtime('pendataan.manual.js')?>"></script>
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
<input type="hidden" id="idpelajaran" value="<?= $idPelajaran ?>">
<input type="hidden" id="pelajaran" value="<?= $pelajaran ?>">
<input type="hidden" id="nip" value="<?= $nip ?>">
<input type="hidden" id="nama" value="<?= $nama ?>">
<input type="hidden" id="idjenisujian" value="<?= $idJenisUjian ?>">
<input type="hidden" id="jenisujian" value="<?= $jenisUjian ?>">
<input type="hidden" id="idaturannhb" value="<?= $idAturanNhb ?>">
<input type="hidden" id="aspek" value="<?= $aspek ?>">
<input type="hidden" id="namaaspek" value="<?= $namaAspek ?>">


<?php
$sql = "SELECT u.replid, u.tanggal, u.deskripsi, u.kode, 
               IFNULL(u.idrpp, 0) AS fidrpp, IFNULL(r.rpp, 'Tanpa RPP') AS frpp
          FROM jbsakad.ujian u 
          LEFT JOIN jbsakad.rpp r ON r.replid = u.idrpp
         WHERE u.idaturan='$idAturanNhb' 
           AND u.idkelas='$idKelas' 
           AND u.idsemester='$idSemester' 
         ORDER by u.tanggal ASC";
$res = $db->QueryDb($sql);
$nUjian = mysqli_num_rows($res);

echo "<div style='position: relative; width: 100%'>";

echo "<table border='0'>";
echo "<tr>";
echo "<td width='150'>";
echo "<span class='fg-secondary'>Pelajaran</span><br>";
echo "<span class='fs-16 fs-bold'>" . $pelajaran . "</span>";
echo "</td>";
echo "<td width='150'>";
echo "<span class='fg-secondary'>Aspek Penilaian</span><br>";
echo "<span class='fs-16 fs-bold'>" . $namaAspek . "</span>";
echo "</td>";
echo "<td width='150'>";
echo "<span class='fg-secondary'>Jenis Ujian</span><br>";
echo "<span class='fs-16 fs-bold'>" . $jenisUjian . "</span>";
echo "</td>";
echo "</tr>";
echo "</table>";

echo "<input type='button' style='position: absolute; top: 0px; right: 20px;' 
             class='dialogButtonGray' value=' < kembali  ' onclick='window.history.back()'>";

echo "</div>";

$lsUjian = [];
while($row = mysqli_fetch_array($res))
{
    $lsUjian[] = array(
        $row["replid"],
        $row["tanggal"],
        $row["deskripsi"],
        $row["fidrpp"],
        $row["frpp"],
        $row["kode"]
    );
}

echo "<table class='tab tabShadow' id='tableDaftar' width='100%' align='center'>";
echo "<tr>";
echo "<td height='30' class='bg-table-header' align='center' width='4%'>No</td>";
echo "<td height='30' class='bg-table-header' align='center' width='10%'>NIS</td>";
echo "<td height='30' class='bg-table-header' align='center' width='*'>Nama</td>";

for($i = 0; $i < $nUjian; $i++)
{
    $idUjian = $lsUjian[$i][0];
    $rpp = $lsUjian[$i][4];
    $materi = $lsUjian[$i][2];
    $kode = $lsUjian[$i][5];

    $no = $i + 1;;
    $temp = $lsUjian[$i][1];
    $ls = explode("-", $temp);
    $tgl = $ls[2] . "/" . $ls[1] . "/" . substr($ls[0], 2);
    $tgl = "<span class='fs-11'>$tgl<span>";

    $hint = "Kode: $kode<br>RPP: $rpp<br>Materi: $materi";
    $judul = "$jenisUjian-$no";
    
    echo "<td class='bg-table-header' width='60' align='center' onmouseover='showhint(\"$hint\", this, event, \"200px\")'>";
    echo "<b>$judul</b><br>$tgl<br>";
    echo "</td>";
}
echo "<td height='30' class='bg-table-header' align='center' width='60'>Rerata Siswa</td>";
echo "<td height='30' class='bg-table-header' align='center' width='75'>";
echo "Nilai Akhir<br>$jenisUjian";
echo "</td>";
echo "</tr>";

$sql  = "SELECT replid, nis, nama 
           FROM jbsakad.siswa 
          WHERE idkelas = '$idKelas' 
            AND aktif = 1 
          ORDER BY nama ASC";
$resSiswa = $db->QueryDb($sql);
$cnt = 0;
$jumSiswa = mysqli_num_rows($resSiswa);
$totalNilaiRata = 0;
$totalNau = 0;
while ($rowSiswa = mysqli_fetch_array($resSiswa))
{
    $cnt += 1;
    $nilai = 0;

    $replidSiswa = $rowSiswa['replid'];
    $nis = $rowSiswa['nis'];
    $nama = $rowSiswa['nama'];

    echo "<tr height='25'>";
    echo "<td align='center' class='bg-table-number-column'>" . $cnt. "</td>";
    echo "<td align='center'>$nis</td>";
    echo "<td align='left' style='position: relative;'>";
    echo "<input type='hidden' id='nis$cnt' value='$nis'>";
    echo "<input type='hidden' id='nama$cnt' value='$nama'>";
    echo "<b>$nama</b>";
    echo "<span style='position: absolute; right: 5px; top: 5px;'>";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replidSiswa)'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/lihat.png' title='lihat' class='cur-hand' onclick='detailSiswa($replidSiswa)'>&nbsp;&nbsp;";
    echo "</span>";
    echo "</td>";

    for($i = 0; $i < $nUjian; $i++)
    {
        $idUjian = $lsUjian[$i][0];

        $sql = "SELECT replid, nilaiujian, keterangan
                  FROM jbsakad.nilaiujian 
                 WHERE idujian = '$idUjian' 
                   AND nis = '$nis'";
        $resNilai = $db->QueryDb($sql);
        if (mysqli_num_rows($resNilai) > 0)
        {
            $rowNilai = mysqli_fetch_array($resNilai);
            $idNilaiUjian = $rowNilai["replid"];
            $nilaiUjian = $rowNilai['nilaiujian'];

            echo "<td align='center'>";
            echo $nilaiUjian;
            echo "</td>";
        }
        else
        {
            echo "<td align='center'>-</td>";
        }
    }
    echo "<td align='center'>";
    $nilaiRataSiswa = GetRataSiswa2($db, $idPelajaran, $idJenisUjian, $idKelas, $idSemester, $idAturanNhb, $nis);
    $totalNilaiRata += $nilaiRataSiswa;
    echo $nilaiRataSiswa;
    echo "</td>";

    echo "<td align='center'>";
    $sql = "SELECT nilaiAU, replid, keterangan, info1 
              FROM jbsakad.nau 
             WHERE nis = '$nis' 
               AND idkelas = '$idKelas' 
               AND idsemester = '$idSemester' 
               AND idaturan = '$idAturanNhb'";
    $resNau = $db->QueryDb($sql);
    if ($rowNau = mysqli_fetch_array($resNau))
    {
        $nilaiNau = $rowNau['nilaiAU'];
        $idNilaiNau = $rowNau['replid'];
        $totalNau += $nilaiNau;

        echo "<input type='text' id='nilainau$cnt' class='inputbox' style='width: 50px' maxlength='5' value='$nilaiNau'>";
        echo "<input type='hidden' id='idnilainau$cnt' value='$idNilaiNau'>";
    }
    else 
    {
        echo "<input type='text' id='nilainau$cnt' class='inputbox' style='width: 50px' maxlength='5' value=''>";
        echo "<input type='hidden' id='idnilainau$cnt' value='0'>";
    }

    echo "</td>";
}

echo "<tr height='30'>";
echo "<td class='bg-gray-100' colspan='3' align='right'><b>Rerata Kelas</b></td>";
for($i = 0; $i < $nUjian; $i++)
{
    $idUjian = $lsUjian[$i][0];

    echo "<td align='center' class='bg-gray-100 fs-bold'>";
    echo GetRataKelas($db, $idKelas, $idSemester, $idUjian);
    echo "</td>";
}
$rata = round($totalNilaiRata / $jumSiswa, 2);
echo "<td align='center' class='bg-gray-100 fs-bold'>";
echo $rata;
echo "</td>";
$rata = round($totalNau / $jumSiswa, 2);
echo "<td align='center' class='bg-gray-100 fs-bold'>";
echo $rata;
echo "</td>";
echo "</tr>";
echo "</table>";
echo "<input type='hidden' id='nsiswa' value='$jumSiswa'>";
echo "<br>";

echo "<div style='position: relative; width: 100%;'>";
echo "<input type='button' value=' Simpan Nilai Akhir $jenisUjian ' 
             style='position: absolute; top: 0px; right: 10px;' 
             class='dialogButtonPositive' onclick='simpanNilaiAkhir()'>";
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