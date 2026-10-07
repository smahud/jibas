<?php
require_once(dirname(__FILE__) . "/../include/sessioninfo.php");
require_once(dirname(__FILE__) . "/../include/sessionchecker.php");
require_once(dirname(__FILE__) . "/../include/config.php");
require_once(dirname(__FILE__) . "/../include/db.onfunc.php");
require_once(dirname(__FILE__) . "/../library/msg.php");
require_once(dirname(__FILE__) . "/../library/hintinfo.php");
require_once(dirname(__FILE__) . "/../library/common.func.php");
require_once(dirname(__FILE__) . "/../util/peek.php");
require_once(dirname(__FILE__) . "/../library/departemen.php");
require_once('proses.func.php');

$db = new Db();
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Proses Penerimaan Siswa Baru</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/hintinfo.js?<?=filemtime('../script/hintinfo.js')?>"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="proses.js?<?=filemtime('proses.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Proses Penerimaan Siswa Baru</span><br>
                <a class="pageLink" href="psb.php">Penerimaan Siswa Baru</a>&nbsp&gt;&nbsp
                <span class="pageLinkCurrent">Proses Penerimaan</span>

            </td>
        </tr>
        </table>
        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <tr>
            <td align="right" width="35%">
                <strong>Departemen</strong>
<?php           $departemen = "";
                $page = 1;
                ShowSelectDepartemen($db); ?>
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
            HintInfo::ShowCenter("Belum ada data proses penerimaan siswa baru<br>Silahkan klik ikon tambah untuk membuat proses penerimaan siswa baru");
            echo "</span>";
            echo "<input type='hidden' id='ndata' value='0'>";
        }
        else 
        {
            echo "<span id='spCountInfo'></span>";
            echo "<input type='hidden' id='ndata' value='$nData'>";
        }
        
        echo "<div id='dvTableContent'>";
        ShowTableProses($db);
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
