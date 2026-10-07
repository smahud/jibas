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
function CheckStatusKeuangan($db, $nis) // true -> aman, false -> ada tunggakan
{
    $sql = "SELECT 1
              FROM jbsfina.besarjtt
             WHERE lunas = 0
               AND nis = '$nis'
             LIMIT 1";
    $res = $db->QueryDb($sql);

    return mysqli_num_rows($res) == 0; // 0 tidak ada iuran yg belum lunas
}
?>