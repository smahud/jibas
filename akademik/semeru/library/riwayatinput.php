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
class RiwayatInput
{
    static function Save($db, $departemen, $kategori, $subKategori, $idData, $deskripsi)
    {
        $deptValue = $departemen == "" ? "NULL" : "'$departemen'";
        $userIdValue = SI_USER_LEVEL() == 0 ? "NULL" : "'" . SI_USER_ID() . "'";
        
        $sql = "INSERT INTO jbsjs.riwayatinput
                   SET departemen = $deptValue, tanggal = CURDATE(), waktu = NOW(), kategori = '$kategori', 
                       subkategori = '$subKategori', userid = $userIdValue, 
                       iddata = '$idData', deskripsi = '$deskripsi', app = 'AKAD'";
        $db->QueryDb($sql);
    }

    static function Delete($db, $kategori, $subKategori, $idData)
    {
        $sql = "DELETE FROM jbsjs.riwayatinput
                 WHERE kategori = '$kategori'
                   AND subkategori = '$subKategori'
                   AND iddata = '$idData'";
        $db->QueryDb($sql);
    }
}
?>