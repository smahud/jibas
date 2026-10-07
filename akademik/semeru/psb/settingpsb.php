<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('settingpsb.func.php');

$db = new Db();
$db->TryOpenExit(true);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Setting Penerimaan Siswa Baru</title>
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
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="settingpsb.js?<?=filemtime('settingpsb.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Informasi Sumbangan dan Nilai</span><br>
                <a class="pageLink" href="psb.php">Penerimaan Siswa Baru</a>&nbsp&gt;&nbsp
                <span class="pageLinkCurrent">Informasi Sumbangan dan Nilai</span>

            </td>
        </tr>
        </table>
        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <tr>
            <td align="left" width="70%">
                <strong>Departemen:</strong>&nbsp;
<?php           $departemen = "";    
                ShowSelectDepartemen($db); ?>
                
                <span style="margin-left: 30px;"><strong>Proses Penerimaan:</strong></span>&nbsp;
                <span id="spProsesPsb">
<?php           $idProsesPsb = 0;
                ShowSelectProsesPenerimaan($db); ?>
                </span>
            </td>
            <td align="right" width="*">
                <span class='cur-hand fg-secondary' onclick='refresh()' title='refresh'>
                    <img src='../images/ico/refresh.png' border='0'/>&nbsp;refresh
                </span>
            </td>
        </tr>
        </table><br>

        <div id="dvTableContent">
<?php
        ShowTableSettingPsb($db);
?>
        </div>
    </td>
</tr>
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>
<div id="dvLoading" class="loading-box">
    memuat .. 
</div>

</body>
</html>
