<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 23.0 (November 12, 2020)
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
require_once('../library/common.func.php');

$op = RequestData("op", "");

if ($op == "set_exim")
{
    $_SESSION["SHOW_EXIM"] = "1";
    echo json_encode([1, "OK"]);
}
else if ($op == "unset_exim")
{
    unset($_SESSION["SHOW_EXIM"]);
    echo json_encode([1, "OK"]);
}
else if ($op == "set_cbe")
{
    $_SESSION["SHOW_CBE"] = "1";
    echo json_encode([1, "OK"]);
}
else if ($op == "unset_cbe")
{
    unset($_SESSION["SHOW_CBE"]);
    echo json_encode([1, "OK"]);
}
else if ($op == "set_sptfgr")
{
    $_SESSION["SHOW_SPTFGR"] = "1";
    echo json_encode([1, "OK"]);
}
else if ($op == "unset_sptfgr")
{
    unset($_SESSION["SHOW_SPTFGR"]);
    echo json_encode([1, "OK"]);
}
?>
