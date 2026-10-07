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
require_once('pendataan.daftar.func.php');
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
    <title>Daftar Nilai</title>
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
    <script language="javascript" src="pendataan.daftar.js?<?=filemtime('pendataan.daftar.js')?>"></script>
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

echo "<div style='position: relativve; width: 100%'>";
echo "<table border='0'>";
echo "<tr>";
echo "<td width='250'>";
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

if ($nUjian > 0)
{
    echo "<div style='position: absolute; top: 30px; right: 10px;'>";
    echo "<span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>";
    echo "<img src='../../images/ico/refresh.png' border='0'>&nbsp;refresh";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='cetak()'>";
    echo "<img src='../../images/ico/print.png' border='0'>&nbsp;cetak";
    echo "</span>&nbsp;&nbsp;";
    echo "<span class='cur-hand fg-secondary' onclick='tambah()'>";
    echo "<img src='../../images/ico/tambah.png' border='0'>&nbsp;tambah";
    echo "</span>&nbsp;&nbsp;";
    echo "</div>";
}
echo "</div><br>";

if ($nUjian == 0)
{
    echo "<table width='100%' border='0' align='center'>";
    echo "<tr>";
    echo "<td align='center' valign='middle' height='200'>";
    echo "<font size = '2' color ='red'><b>Tidak ditemukan adanya data nilai ujian.";
    echo "<br />Klik &nbsp;<a href='JavaScript:tambah()' ><font size = '2' color ='green'>di sini</font></a>&nbsp;untuk mengisi data baru.";
    echo "</b></font>";
    echo "";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "</body>";
    echo "</html>";
    exit();
}

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

echo "<div id='dvTableDaftar'>";
echo "<table class='tab tabShadow' id='tableDaftar' width='100%' align='center'>";
echo "<tr>";
echo "<td height='30' class='bg-table-header' align='center' width='4%'>No</td>";
echo "<td height='30' class='bg-table-header' align='center' width='14%'>NIS</td>";
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
    echo "<img src='../../images/ico/ubah.png' class='cur-hand hide-in-report' title='ubah' onclick='ubah_info($idUjian)'>";
    if (SI_USER_LEVEL() != $SI_USER_STAFF) 
        echo "&nbsp;<img src='../../images/ico/hapus.png' class='cur-hand hide-in-report' title='hapus' onclick='hapus_nilai_ujian($idUjian, \"$judul\")'>";
    echo "</td>";
}
echo "<td height='30' class='bg-table-header' align='center' width='60'>Rerata Siswa</td>";
echo "<td height='30' class='bg-table-header' align='center' width='75'>";
echo "Nilai Akhir<br>$jenisUjian";

$sql = "SELECT nilaiAU, keterangan 
            FROM jbsakad.nau 
            WHERE idkelas = '$idKelas' 
            AND idsemester = '$idSemester' 
            AND idaturan = '$idAturanNhb'";
$resNau = $db->QueryDb($sql);
if (mysqli_num_rows($resNau) > 0)
{
    if (SI_USER_LEVEL() != $SI_USER_STAFF) 
        echo "<br><img src='../../images/ico/hapus.png' class='cur-hand hide-in-report' title='hapus nilai akhir' onclick='hapus_nau()'>";
}

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
    echo "<b>$nama</b>";
    echo "<span style='position: absolute; right: 5px; top: 5px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa($replidSiswa)'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa($replidSiswa)'>";
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
            $keterangan = trim($rowNilai['keterangan']);
            $dataNilai64 = base64_encode(json_encode([$idUjian, $idNilaiUjian, $nilaiUjian, $keterangan, $nis, $nama]));

            echo "<td align='center'>";
            echo "<a onclick='ubah_nilai(\"$dataNilai64\")' class='cur-hand ff-courier fs-14' title='ubah nilai ujian'>$nilaiUjian</a>";
            if (strlen($keterangan) > 0) 
                echo "<span class='fs-10 fg-blue' title='ada keterangan'>&nbsp;*)</span>";
            echo "</td>";
        }
        else 
        {
            echo "<td align='center'>";
            echo "<img src='../../images/ico/tambah.png' class='cur-hand hide-in-report' title='tambah nilai' onclick='tambah_nilai($idUjian, \"$nis\", \"$nama\")'>";
            echo "</td>";
        }
    }
    echo "<td align='center'>";
    $nilaiRataSiswa = GetRataSiswa2($db, $idPelajaran, $idJenisUjian, $idKelas, $idSemester, $idAturanNhb, $nis);
    $totalNilaiRata += $nilaiRataSiswa;
    echo "<span class='fg-secondary fst-bold ff-courier fs-14'>$nilaiRataSiswa</span>";
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
        $keteranganNau = trim($rowNau['keterangan']);
        $infoNau = $rowNau['info1'];

        $totalNau += $nilaiNau;

        if ($nilaiNau <> 0)
        {
            $dataNau64 = base64_encode(json_encode([$idUjian, $idNilaiNau, $nilaiNau, $keteranganNau, $nis, $nama]));
            echo "<a onclick='ubah_nau(\"$dataNau64\")' class='cur-hand ff-courier fs-14' title='ubah nilai akhir ujian'>$nilaiNau</a>";
        }
        else 
        {
            //echo "<img src='../../images/ico/tambah.png' class='cur-hand' title='tambah nilai' onclick='tambah_nau($idNilaiNau)'>";
            echo "&nbsp;";
        }

        if ($keteranganNau <> "")
            echo "&nbsp;<font color='#067900'>)*</font>";

        if ($infoNau == '1')
            echo "&nbsp;<font color='blue'>)*</font>";
    }

    echo "</td>";
}

echo "<tr height='30'>";
echo "<td class='bg-gray-100' colspan='3' align='right'><b>Rerata Kelas</b></td>";
for($i = 0; $i < $nUjian; $i++)
{
    $idUjian = $lsUjian[$i][0];

    $rata = GetRataKelas($db, $idKelas, $idSemester, $idUjian);
    echo "<td align='center' class='bg-gray-100 fs-bold'>";
    echo "<span class='fg-secondary fst-bold ff-courier fs-14'>$rata</span>";
    echo "</td>";
}

if ($jumSiswa <> 0) 
    $rata = round($totalNilaiRata / $jumSiswa, 2);
else 
    $rata = 0;
echo "<td align='center' class='bg-gray-100 fs-bold'>";
echo "<span class='fg-secondary fst-bold ff-courier fs-14'>$rata</span>";
echo "</td>";

if ($jumSiswa <> 0) 
    $rata = round($totalNau / $jumSiswa, 2);
else 
    $rata = 0;
echo "<td align='center' class='bg-gray-100 fs-bold'>";
echo "<span class='fg-secondary fst-bold ff-courier fs-14'>$rata</span>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</div>";
echo "<br>";

echo "<div style='width: 100%; position: relative'>";
echo "<span class='fst-italic fg-blue'>)* keterangan nilai</span>&nbsp;&nbsp;";
echo "<span class='fst-italic' style='color: #067900'>)* nilai dihitung manual</span>";
echo "<input type='button' id='btRecountRerata' class='dialogButtonGray' style='position: absolute; right: 10px; top: 0px' value='Hitung Ulang Rerata Siswa & Kelas' onclick='hitungUlangRata()'>";
echo "</div>";
echo "<br><br><br><br>";

echo "<fieldset style='width: 97%; border: 1px solid #ccc; border-radius: 5px; padding: 10px; background-color: #e6f9ffff;' class='tabShadow'>";
echo "<legend class='fs-14 fg-secondary'>Hitung <b>Nilai Akhir $jenisUjian</b></legend>";

echo "<table border='0' width='100%'>";
echo "<tr>";
echo "<td width='60%' valign='top'>";
echo "<span class='fg-blue fst-bold fs-13'>Hitung Otomatis</span><br>";
echo "<span class='fg-secondary fs-11'>Hitung nilai akhir $jenisUjian berdasarkan bobot dan ujian terpilih. Pilih ujian dan masukkan bobotnya</span><br><br>";

echo "<table style='width: 550px' id='tableBobot' class='tab tabShadow'>";
echo "<tr>";
echo "<td width='*' colspan='2' class='bg-table-header'>$jenisUjian</td>";
echo "<td width='60' class='bg-table-header' align='center'>Bobot</td>";
echo "</tr>";
$no = 0;
$nBobot = count($lsUjian);
for($i = 0; $i < $nBobot; $i++)
{
    $no += 1;
    $idUjian = $lsUjian[$i][0];
    $tanggal = $lsUjian[$i][1];
    $materi = $lsUjian[$i][2];
    $rpp = $lsUjian[$i][4];
    $kode = $lsUjian[$i][5];

    $idBobot = 0;
    $bobot = "";
    $checked = "";
    $disabled = "disabled";
    $bgColor = "#ccc";
    $sql = "SELECT b.replid, b.bobot 
              FROM jbsakad.bobotnau b 
             WHERE b.idujian = '$idUjian'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $idBobot= $row['replid'];
        $bobot = $row['bobot'];
        $checked = "checked";
        $disabled = "";
        $bgColor = "#fff";
    }
    
    echo "<tr>";
    echo "<td width='30' align='center' valign='top'>";
    echo "<input type='checkbox' id='ckbobot$no' class='inputbox' value='$idUjian' onclick='ck_bobot_onclick(\"$no\")' $checked> ";
    echo "<input type='hidden' id='idujian$no' value='$idUjian'>";
    echo "<input type='hidden' id='idbobot$no' value='$idBobot'>";
    echo "</td>";
    echo "<td width='*'  style='position: relative'>";
    echo "<b>$jenisUjian-$no</b><br>";
    echo "<span class='fg-secondary' style='position: absolute; right: 10px; top: 5px;'>";
    echo LongDateFormat($tanggal);
    echo "</span>";
    echo "<span class='fg-secondary'>";
    echo "RPP: $rpp<br>";
    echo "Materi: $materi<br>";
    echo "</span>";
    echo "</td>";
    echo "<td width='60' align='center' valign='top'>";
    echo "<input type='textbox' id='bobot$no' class='inputbox' value='$bobot'
                 style='width: 30px; background-color: $bgColor' maxlength='2' $disabled>";
    echo "</td>";
    echo "</tr>";
}
echo "</table><br>";
echo "<input type='hidden' id='nbobot' value='$nBobot'>";
echo "<input type='button' id='btHitungOtomatis' class='dialogButtonGray' 
             value='Hitung &amp; Simpan Nilai Akhir $jenisUjian' onclick='hitungNauOtomatis()'>";

echo "</td>";
echo "<td width='40%' valign='top'>";
echo "<span class='fg-blue fs-13 fst-bold'>Hitung Manual</span><br>";
echo "<span class='fg-secondary fs-11'>Menentukan nilai akhir $jenisUjian secara manual</span><br><br>";
echo "<input type='button' id='btHitungManual' class='dialogButtonGray' 
             value='Hitung Manual Nilai Akhir $jenisUjian' onclick='hitungNauManual()'>";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</fieldset>";

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
