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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/hintinfo.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('rpp.content.func.php');

$op = RequestData("op", "");
if ($op == "page")
{
    $page = RequestData("page", 1);
    $status = RequestData("status", 1);
    $departemen = RequestData("departemen", 0);
    $idSemester = RequestData("idsemester", 0);
    $idTingkat = RequestData("idtingkat", 0);
    $idPelajaran = RequestData("idpelajaran", 0);

    ShowListRpp();
}
else if ($op == "pagecontrol")
{
    $page = RequestData("page", 1);
    $status = RequestData("status", 1);
    $departemen = RequestData("departemen", 0);
    $idSemester = RequestData("idsemester", 0);
    $idTingkat = RequestData("idtingkat", 0);
    $idPelajaran = RequestData("idpelajaran", 0);

    ShowPageControl();
}
else if ($op == "setaktif")
{
    echo SetAktifRpp();        
}
else if ($op == "hapus")
{
    echo HapusRpp();
}
else if ($op == "search")
{
    $cari = RequestData("cari", "");
    $status = RequestData("status", 1);
    $departemen = RequestData("departemen", 0);
    $idSemester = RequestData("idsemester", 0);
    $idTingkat = RequestData("idtingkat", 0);
    $idPelajaran = RequestData("idpelajaran", 0);

    ShowSearchRpp();  
}
?>