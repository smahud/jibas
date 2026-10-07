<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/hintinfo.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('kelompok.func.php');

$db = new Db();
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Kelompok Calon Siswa</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/vldr.js"></script>
    <script language="javascript" src="../script/hintinfo.js?<?=filemtime('../script/hintinfo.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="kelompok.js?<?=filemtime('kelompok.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Kelompok Calon Siswa</span><br>
                <a class="pageLink" href="psb.php">Penerimaan Siswa Baru</a>&nbsp;&gt;&nbsp;
                <span class="pageLinkCurrent">Kelompok Calon Siswa</span>

            </td>
        </tr>
        </table>
        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <tr>
            <td align="left" width="70%">
                <b>Departemen: </b>&nbsp;
<?php               
                    $departemen = "";
                    ShowSelectDepartemen($db); 
?>
                <span style="margin-left: 15px;"><b>Proses Penerimaan: </b></span>
                <span id="spProses" style="margin-left: 15px;">
<?php               
                    $idProsesPsb = 0;
                    $prosesPsb = "";
                    ShowActiveProses($db) 
?>
                </span>
            </td>
            <td align="right" width="*">
                <span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>
                    <img src='../images/ico/refresh.png' border='0'/>&nbsp;refresh
                </span>&nbsp;&nbsp;
                <span class='cur-hand fg-secondary' onclick='cetak()'>
                    <img src='../images/ico/print.png' border='0'/>&nbsp;cetak
                </span>&nbsp;&nbsp;
<?php           if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>                
                <span class='cur-hand fg-secondary' onclick='tambah()'>
                    <img src='../images/ico/tambah.png' border='0'/>&nbsp;tambah
                </span>
<?php           } ?>                
            </td>
        </tr>
        </table><br>
<?php
        $nData = CountData($db);
        if ($nData == 0)
        {
            echo "<span id='spCountInfo'>";
            HintInfo::ShowCenter("Belum ada data kelompok calon siswa<br>Silahkan klik ikon tambah untuk membuat kelompok calon siswa baru");
            echo "</span>";
            echo "<input type='hidden' id='ndata' value='0'>";
        }
        else 
        {
            echo "<span id='spCountInfo'></span>";
            echo "<input type='hidden' id='ndata' value='$nData'>";
        }
        
        echo "<div id='dvTableContent'>";
        $page = 1;
        ShowTableKelompok($db);
        echo "</div>";
        echo "<br>";
        
        echo "<div id='dvPageControl'>";
        ShowPageControl();
        echo "</div>";
?>        

    </td>
</tr>
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>


</body>
</html>
