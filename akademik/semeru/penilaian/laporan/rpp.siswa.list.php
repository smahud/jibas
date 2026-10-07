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
require_once('../../library/hintinfo.php');
require_once('../../library/qsbuilder.php');
require_once('../../util/peek.php');
require_once('../../include/errorhandler.php');

$departemen = RequestData("departemen", "");
$idTahunAjaran = RequestData("idtahunajaran", 0);
$tahunAjaran = RequestData("tahunajaran", "");
$idSemester = RequestData("idsemester", 0);
$semester = RequestData("semester", "");
$idTingkat = RequestData("idtingkat", 0);
$tingkat = RequestData("tingkat", "");
$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");
$idPelajaran = RequestData("idpelajaran", 0);
$pelajaran = RequestData("pelajaran", "");
$idJenisPengujian = RequestData("idjenispengujian", 0);
$jenisPengujian = RequestData("jenispengujian", "");

$db = new Db;
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Daftar RPP</title>
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
    <script language="javascript" src="rpp.siswa.list.js?r=<?=filemtime('rpp.siswa.list.js')?>"></script>
</head>
<body style="padding: 5px; background-color: #efefef;">

<input type="hidden" id="departemen" value="<?=$departemen?>">
<input type="hidden" id="idtahunajaran" value="<?=$idTahunAjaran?>">
<input type="hidden" id="tahunajaran" value="<?=$tahunAjaran?>">
<input type="hidden" id="idsemester" value="<?=$idSemester?>">
<input type="hidden" id="semester" value="<?=$semester?>">
<input type="hidden" id="idtingkat" value="<?=$idTingkat?>">
<input type="hidden" id="tingkat" value="<?=$tingkat?>">
<input type="hidden" id="idkelas" value="<?=$idKelas?>">
<input type="hidden" id="kelas" value="<?=$kelas?>">
<input type="hidden" id="idpelajaran" value="<?=$idPelajaran?>">
<input type="hidden" id="pelajaran" value="<?=$pelajaran?>">
<input type="hidden" id="idjenispengujian" value="<?=$idJenisPengujian?>">
<input type="hidden" id="jenispengujian" value="<?=$jenisPengujian?>">

<?php
$sql = "SELECT r.replid, r.koderpp, r.rpp, s.semester
          FROM jbsakad.rpp r, jbsakad.semester s
         WHERE r.idsemester = s.replid
           AND r.idtingkat = '$idTingkat' 
           AND r.idpelajaran = '$idPelajaran' 
           AND r.aktif = 1 
         ORDER BY s.aktif DESC, r.urutan, r.koderpp";		
$res = $db->QueryDb($sql);

echo "<span class='fs-14'>RPP $pelajaran</span><br><br>";

if (mysqli_num_rows($res) == 0)
{
    HintInfo::ShowLeft("Belum ada data RPP");
    echo "</body></html>";
    exit();
}

echo "<table class='tab tabShadow' id='tableRpp' border='1' width='100%' align='left' cellpadding='3'>";
echo "<tr height='25' align='left'>";
echo "<td width='10%' class='bg-table-header' align='center'>No</td>";
echo "<td colspan='2' class='bg-table-header' width='*'>RPP</td>";
echo "</tr>";

$no = 0;
while ($row = mysqli_fetch_assoc($res))
{
    $no++;
    $idRpp = $row['replid'];
    $kodeRpp = $row['koderpp'];
    $rpp = $row['rpp'];
    $semesterRpp = $row['semester'];

    $sql = "SELECT COUNT(DISTINCT s.nis) 
              FROM jbsakad.nilaiujian n, jbsakad.siswa s, jbsakad.ujian u, jbsakad.jenisujian j 
             WHERE n.idujian = u.replid 
               AND u.idsemester = '$idSemester' 
               AND u.idkelas = '$idKelas' 
               AND u.idrpp = '$idRpp' 
               AND u.idpelajaran = '$idPelajaran' 
               AND s.nis = n.nis 
               AND u.idjenis = j.replid 
               AND s.idkelas = '$idKelas' 
               AND s.aktif = 1";
    if ($idJenisPengujian != 0)            
        $sql .= " AND u.idjenis = '$idJenisPengujian'";
    $res2 = $db->QueryDb($sql);
    $row2 = mysqli_fetch_row($res2);
    $nData = $row2[0];
    
    echo "<tr>";
    echo "<td width='10%' class='bg-table-number-column' align='center'>$no</td>";
    echo "<td width='*' class='cur-hand' onclick='showRerateRpp(\"$idRpp\", \"$kodeRpp\", \"$rpp\")'>";
    echo "$rpp<br><span class='fg-secondary'>$kodeRpp | $semesterRpp</span>";
    echo "</td>";
    echo "<td width='10%' class='bg-light-purple fg-secondary fst-bold' align='center'>$nData</td>";
    echo "</tr>";
}
echo "</table>";

?>

</body>
</html>