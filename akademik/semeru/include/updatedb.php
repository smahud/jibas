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
require_once(__DIR__ . "/../library/common.func.php");
require_once(__DIR__ . "/updatedb.func.php");

if (!isset($_SESSION["updatedb"]))
{
    //echo "<br><br>";
    //echo "<center>checking for update ..</center>";

    $db = new Db();
    $db->TryOpenExit();

    require_once("updatedb.001.php");
}

$_SESSION["updatedb"] = 1;
?>
