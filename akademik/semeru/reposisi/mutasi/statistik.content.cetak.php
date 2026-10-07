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
require_once('../../include/sessionchecker.php');
require_once('../../include/sessioninfo.php');
require_once('../../include/config.php');
require_once('../../library/common.func.php');
require_once('../../include/db.onfunc.php');
require_once('../../include/getheader2.php');
require_once('../../include/errorhandler.php');

$db = new Db();
$db->TryOpenExit();

$departemen = RequestData("departemen", "yayasan");
$tahunMutasi = RequestData("tahunmutasi", "");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="stylesheet" type="text/css" href="../../style/style.css">
    <link rel="stylesheet" type="text/css" href="../../style/colors.css">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="../images/jibas.ico" rel="shortcut icon" />
    <title>Statistik Mutasi Siswa</title>
    <script language="javascript" src="../../script/jquery-3.7.1.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function ()
        {
            var chart = "";
            var table = "";
            if (window.opener && typeof window.opener.getPageContent === "function")
            {
                chart = window.opener.getPageContent("chart");
                table = window.opener.getPageContent("table");
            }

            $("#spReportChart").html(chart);
            $("#spReportTable").html(table);
            $("#spReportTable").find('.hide-in-report').remove();

            var table = $('#table');
            table.find('tr').each(function() {
                $(this).find('td.hide-in-report').remove();
            });

            window.print();
        });

        document.addEventListener('keydown', function(event) 
        {
            if (event.key === 'Escape') 
                window.close(); 
        });
    </script>
</head>

<body>
<table border="0" cellpadding="10" cellpadding="5" width="780" align="left">
<tr>
    <td align="left" valign="top">

<?=     getHeader2($db, $departemen) ?>

        <center><font size="4"><strong>STATISTIK MUTASI SISWA</strong></font><br /> </center><br>

        <table border="0" cellspacing="2" cellpadding="2" width="100%">
        <tr>
            <td align="left" style="width: 80px;">Departemen:</td>
            <td><b><?= $departemen ?></b></td>
        </tr>
        <tr>
            <td align="left" style="width: 80px;">Tahun Mutasi:</td>
            <td><b><?= $tahunMutasi ?></b></td>
        </tr>
        </table>

        <div id="spReportChart">
        </div>
        <br>

        <div id="spReportTable">
        </div>

    </td>
</tr>
</table>
</body>
</html>