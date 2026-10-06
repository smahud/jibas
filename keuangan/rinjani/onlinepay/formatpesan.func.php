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
function SimpanPesanTagihan()
{
    $db = new Db();
    try
    {
        $db->Open();
        $db->BeginTrans();

        $nDept = $_REQUEST["ndept"];
        for($i = 1; $i <= $nDept; $i++)
        {
            $key = "dept$i";
            $dept = $_REQUEST[$key];

            $key = "pesan$i";
            $pesan = $_REQUEST[$key];

            $sql = "UPDATE jbsfina.formatpesanpg2 
                       SET pesan = '$pesan', issync = 0 
                     WHERE departemen = '$dept'
                       AND kelompok = 'TAGIHAN'";
            $db->QueryDb($sql);
        }
        $db->CommitTrans();

        return json_encode([1, "OK", ""]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();
        
        return json_encode([-1, $ex->getMessage(), ""]);
    }
}

function SimpanPesanPembayaran()
{
    $db = new Db();
    try
    {
        $db->Open();
        $db->BeginTrans();
        
        $nDept = $_REQUEST["ndept"];
        for($i = 1; $i <= $nDept; $i++)
        {
            $key = "dept$i";
            $dept = $_REQUEST[$key];

            $key = "pesan$i";
            $pesan = $_REQUEST[$key];

            $sql = "UPDATE jbsfina.formatpesanpg2 
                       SET pesan = '$pesan', issync = 0 
                     WHERE departemen = '$dept'
                       AND kelompok = 'PEMBAYARAN'";
            $db->QueryDb($sql);
        }
        $db->CommitTrans(); 

        return json_encode([1, "OK", ""]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage(), ""]);
    }
}
?>