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
if (isset($_SESSION["showtoast"])) 
{
    $location = isset($_SESSION["toastlocation"]) ? $_SESSION["toastlocation"] : "top";
    $type = isset($_SESSION["toasttype"]) ? $_SESSION["toasttype"] : "success";
    $message = isset($_SESSION["toastmessage"]) ? $_SESSION["toastmessage"] : "";

    echo "<script>";
    echo "$(document).ready(function() {";
    echo "showToast('$message', 2500, '$type', '$location');";
    echo "});";
    echo "</script>";
    
    unset($_SESSION["showtoast"]);
    unset($_SESSION["toasttype"]);
    unset($_SESSION["toastmessage"]);
    unset($_SESSION["toastlocation"]);   
}
?>