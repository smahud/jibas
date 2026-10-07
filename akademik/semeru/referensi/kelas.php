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
require_once("kelas.func.php");

$db = new Db;
$db->TryOpenExit(true);
?>
<html>
<head>
    <title>Kelas</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="../style/style.css?<?=filemtime('../style/style.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/colors.css?<?=filemtime('../style/colors.css')?>">
    <link rel="stylesheet" type="text/css" href="../style/toast.css">
    <link rel="stylesheet" type="text/css" href="../script/jquery-ui-1.14.1/jquery-ui.min.css">
    <script language="javascript" src="../script/jquery-3.7.1.min.js"></script>
    <script language="javascript" src="../script/jquery-ui-1.14.1/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../script/common.js"></script>
    <script type="text/javascript" src="../script/tables.js"></script>
    <script language="javascript" src="../script/tools.js"></script>
    <script language="javascript" src="../script/vldr.js"></script>
    <script language="javascript" src="../script/dialogbox.js" ></script>
    <script language="javascript" src="../script/qsbuilder.js?<?=filemtime('../script/qsbuilder.js')?>" ></script>
    <script language="javascript" src="kelas.js?<?=filemtime('kelas.js')?>"></script>
</head>
<body style="margin:0; padding:0;">

<table border="0" width="100%">
<tr>
    <td align="center" valign="top">

        <table border="0" width="95%" align="center">
        <tr>
            <td align="right" valign="top">

                <img class="help-icon-1" src="../images/help32.png" title="bantuan" onclick="showHelp()">
                <span class="pageTitle">Kelas</span><br>
                <a class="pageLink" href="referensi.php">Referensi</a>&nbsp&gt;&nbsp
                <span class="pageLinkCurrent">Kelas</span>

            </td>
        </tr>
        </table>
        <br>

        <table border="0" cellpadding="0" cellspacing="0" width="95%" align="center">
        <tr>
            <td align="right" width="5%">

            </td>
            <td align="left" width="*">

                <div id="content" style="padding: 12px;">
                    <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        <div>
                            <label for="departemen"><strong>Departemen</strong></label><br>
<?php                       $departemen = "";
                            ShowSelectDepartemen($db); ?>
                        </div>

                        <div>
                            <label for="tahunajaran"><strong>Tahun Ajaran</strong></label><br>
                            <div id="dvTahunAjaran">
<?php                       $tahunajaran = 0;
                            ShowSelectTahunAjaran($db); ?>
                            </div>
                        </div>

                        <div>
                            <label for="tingkat"><strong>Tingkat</strong></label><br>
                            <div id="dvTingkat">
<?php                       $tingkat = 0;
                            ShowSelectTingkat($db); ?>
                            </div>
                        </div>

                        <div style="display:flex; gap:12px; align-items:flex-end;">
                            <input type="button" value="lihat" class="dialogButtonGray" onclick="onChangeFilter();" />

                            <span class='cur-hand fg-secondary' onclick='refresh()' title='refresh' style='margin-left: 20px;'>
                                <img src='../images/ico/refresh.png' border='0'/>&nbsp;refresh
                            </span>
                            <span class='cur-hand fg-secondary' onclick='cetak()'>
                                <img src='../images/ico/print.png' border='0'/>&nbsp;cetak
                            </span>
<?php                       if (SI_USER_LEVEL() != $SI_USER_STAFF) { ?>
                                <span class='cur-hand fg-secondary' onclick='tambah()'>
                                    <img src='../images/ico/tambah.png' border='0'/>&nbsp;tambah
                                </span>
<?php                       } ?>
                        </div>
                    </div>

                    <div id="dvTableContent">
<?php                   $urut = "kelas";    
                        ShowTableKelas($db); ?>                        
                    </div>
                </div>

            </td>
        </tr>
        </table>

    </td>
</tr>   
</table>

<div id="divDialog"></div>
<div id="toast-container"></div>
<div id="divHelpDialog" class="help-dialog"></div>

</body>
</html>
