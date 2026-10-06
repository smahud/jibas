<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
function SimpanInfoBayar()
{
    $db = new Db();
    try
    {
        $db->Open();

        $dept = SafeValueHtml($_REQUEST["dept"]);
        $id = SafeValueHtml($_REQUEST["id"]);
        $info = SafeValueSingleQuote($_REQUEST["info"]);
        $bagian = SafeValueHtml($_REQUEST["bagian"]);

        if ($id == 0)
        {
            $sql = "INSERT INTO jbsfina.infobayar2 SET departemen = '$dept', bagian = '$bagian', info = '$info'";
            $db->QueryDb($sql);

            $sql = "SELECT LAST_INSERT_ID()";
            $res = $db->QueryDb($sql);
            $row = mysqli_fetch_row($res);
            $id = $row[0];
        }
        else
        {
            $sql = "UPDATE jbsfina.infobayar2 SET info = '$info' WHERE replid = $id";
            $db->QueryDb($sql);
        }

        return json_encode([1, "OK", $id]);
    }
    catch (Exception $ex)
    {
        return json_encode([-1, $ex->getMessage(), ""]);
    }
}
?>
