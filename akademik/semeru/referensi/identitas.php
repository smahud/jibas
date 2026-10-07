<?php
require_once("../include/sessioninfo.php");
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db.onfunc.php");
require_once("../library/msg.php");
require_once("../library/hintinfo.php");
require_once("../library/common.func.php");
require_once("../util/peek.php");
require_once("../library/departemen.php");
require_once('identitas.func.php');

$db = new Db;
$db->TryOpenExit(true);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Identitas Sekolah</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script language="javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/toast.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>"></script>
    <script language="javascript" src="identitas.js?<?=filemtime('identitas.js')?>"></script>
</head>
<body>

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Identitas Sekolah</span><br>
                <a class="pageLink" href="referensi.php">Referensi</a>&nbsp&gt;&nbsp
                <span class="pageLinkCurrent">Identitas Sekolah</span>

            </td>
        </tr>
        </table>
        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <tr>
            <td align="right" width="35%">
                <b>Departemen</b>
<?php
                $departemen = "";
                ShowSelectDepartemen($db);
?>
            </td>
            <td align="right" width="*">
                
            </td>
        </tr>
        </table><br>

        <div id="dvTableContent">
<?php
            ShowTableIdentitas($db);
?>
        </div>

    </td>
</tr>
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>
