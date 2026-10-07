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

function LoadSemester($db, $replid)
{
    global $departemen, $semester, $keterangan;

    $sql = "SELECT departemen, semester, keterangan
              FROM jbsakad.semester
             WHERE replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $departemen = $row['departemen'];
    $semester = $row['semester'];
    $keterangan = $row['keterangan'];
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $semester = RequestData("semester", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.semester WHERE semester = '$semester' AND departemen = '$departemen'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Semester $semester sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "INSERT INTO jbsakad.semester
                   SET departemen='$departemen', semester='$semester', keterangan='$keterangan', aktif=1";
        $db->QueryDb($sql);

        // make sure only one active per departemen
        $sql = "UPDATE jbsakad.semester SET aktif=0 WHERE departemen = '$departemen' AND replid <> LAST_INSERT_ID()";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "s1");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}

function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $semester = RequestData("semester", "");
        $keterangan = RequestData("keterangan", "");

        $sql = "SELECT COUNT(replid) FROM jbsakad.semester WHERE semester = '$semester' AND departemen = '$departemen' AND replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0)
        {
            $msg = "Semester $semester sudah ada";
            return json_encode([-1,$msg]);
        }

        $sql = "UPDATE jbsakad.semester
                   SET semester='$semester', keterangan='$keterangan'
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "s2");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
