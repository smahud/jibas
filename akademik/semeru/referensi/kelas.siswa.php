<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');

$idKelas = RequestData("idkelas", 0);
$kelas = RequestData("kelas", "");

$db = new Db();
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Siswa Kelas <?= $kelas ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js?<?=filemtime('../script/vldr.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js"></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kelas.dialog.js?<?=filemtime('kelas.dialog.js')?>"></script>
    <script>
        $(document).ready(function()
        {
            if ($("#table").length)
                Tables('table', 1, 0);
        });
    </script>
</head>
<body style="padding: 10px;">

<span class="dialogTitle">Siswa Kelas <?= $kelas ?></span><br><br>
<input type="hidden" id="idkelas" value="<?= $idKelas ?>">

<?php
echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse'>";
echo "<tr height='30'>";
echo "<td class='header' width='25' align='center'>No</td>";
echo "<td class='header' width='150' align='center'>NIS</td>";
echo "<td class='header' width='250' align='center'>Nama</td>";
echo "<td class='header' width='100' align='center'>Status</td>";
echo "</tr>";

$sql = "SELECT nis, nama, panggilan, aktif
          FROM jbsakad.siswa
         WHERE idkelas = '$idKelas'
         ORDER BY aktif DESC, nama ASC";
$res = $db->QueryDb($sql);
$no = 0;
while($row = mysqli_fetch_array($res)) 
{
    $no++;
    echo "<tr height='30'>";
    echo "<td align='center' class='bg-table-number-column'>$no</td>";
    echo "<td align='center'>$row[nis]</td>";
    echo "<td>$row[nama]<br><span class='fg-secondary'>$row[panggilan]</span></td>";
    echo "<td align='center'>" . ($row['aktif'] == 1 ? "Aktif" : "Non Aktif") . "</td>";
    echo "</tr>";
}
echo "</table>";
?>

</body>
</html>
