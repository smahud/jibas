<?php
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
<?php
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../library/common.func.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/departemen.php');
require_once('../../library/msg.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');

$departemen = RequestData("departemen", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$jumlah = RequestData("jumlah", 0);
$jenis = RequestData("jenis", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar Pelajaran Siswa</title>
    <link rel="stylesheet" type="text/css" href="../../style/style.css?<?=filemtime('../../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <link rel="stylesheet" type="text/css" href="../../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../../script/tables.js"></script>
    <script language="javascript" src="../../script/tools.js"></script>
    <script language="javascript" src="../../script/toast.js"></script>
    <script language="javascript" src="../../script/vldr.js"></script>
    <script language="javascript" src="../../script/qsbuilder.js"></script>
    <script language="javascript" src="nilaipel.list.js?r=<?=filemtime('nilaipel.list.js')?>"></script>
</head>
<body style="padding: 5px; background-color: #efefef;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="jumlah" value="<?=$jumlah?>">
<input type="hidden" id="jenis" value="<?=$jenis?>">

<?php
$sql = "SELECT u.id AS idujian, IFNULL(u.idremedujian, 0) AS idremedujian,
               u.judul AS ujian, DATE_FORMAT(u.tanggal, '%Y-%m-%d') AS ftanggal, 
               u.skalanilai, u.kkm,
               p.id AS idpengujian, p.nama AS pengujian, p.status
          FROM jbscbe.ujian u, jbscbe.pengujian p
         WHERE u.idpengujian = p.id
           AND p.idpelajaran = $idPelajaran
           AND p.status IN ($jenis)
         ORDER BY u.tanggal DESC
         LIMIT $jumlah";
$res = $db->QueryDb($sql);
$nData = mysqli_num_rows($res);
if ($nData == 0)
{
    echo "Belum ada data ujian";
    exit();
}

echo "<table class='tab tabShadow' id='tableUjian' border='1' width='100%' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='10%' class='bg-table-header' align='center'>No</td>";
echo "<td colspan='2' class='bg-table-header' width='*'>Ujian</td>";
echo "</tr>";

while($row = mysqli_fetch_array($res))
{
    $no++;

    $idRemedUjian = $row['idremedujian'];
    $idUjian = $row['idujian'];
    $idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

    $sql = "SELECT COUNT(s.nis)
              FROM jbscbe.ujianserta u, jbsakad.siswa s 
             WHERE u.nis = s.nis
               AND u.idujian = TBD_IDUJIAN
               AND u.status IN (1,2)";
    if ($idRemedUjian != 0)
    {
        $sql .= " AND u.idujianremed = TBD_IDREMEDUJIAN";
        $sql = str_replace("TBD_IDUJIAN", $idUjianInUjianSerta, $sql);
        $sql = str_replace("TBD_IDREMEDUJIAN", $idRemedUjian, $sql);
    }
    else
    {
        $sql .= " AND u.lastdata = 1";
        $sql = str_replace("TBD_IDUJIAN", $idUjian, $sql);
    }

    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_row($res2);
    $nSiswa = $row2[0];

    $tanggal = LongDateFormat($row['ftanggal']);
    $data64 = base64_encode(json_encode([$row['idujian'], $row['idremedujian'], $row['ujian'], $row['ftanggal'], $row['skalanilai'], $row['kkm'], $row['idpengujian'], $row['pengujian'], $row['status'], $nSiswa]));
    
    echo "<tr height='25' align='left'>";
    echo "<td width='10%' class='bg-table-number-column' align='center'>$no</td>";
    echo "<td align='left' class='cur-hand' onclick='showHasilUjian($no)'>";
    echo "<b>$row[ujian]</b><br>";
    echo "<span class='fg-secondary fst-italic'>$tanggal</span>";
    echo "<input type='hidden' id='data$no' value='$data64'>";
    echo "</td>";
    echo "<td width='10%' class='bg-light-purple fg-secondary fst-bold' align='center'>$nSiswa</td>";
    echo "</tr>";   
}
?>

</body>
</html>