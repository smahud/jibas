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

function LoadGuru($db, $replid)
{
    global $departemen, $pelajaran, $idpelajaran, $nipguru, $namaguru, $status, $keterangan;

    $sql = "SELECT g.nip, g.statusguru, g.keterangan, l.departemen, p.nama AS nama_pegawai, 
                   l.nama AS pelajaran, g.idpelajaran
              FROM jbsakad.guru g, jbsakad.pelajaran l, jbssdm.pegawai p
             WHERE g.idpelajaran = l.replid
               AND g.nip = p.nip
               AND g.replid = $replid";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_array($res);

    $nipguru = $row['nip'];
    $status = $row['statusguru'];
    $keterangan = $row['keterangan'];
    $departemen = $row['departemen'];
    $namaguru = $row['nama_pegawai'];
    $pelajaran = $row['pelajaran'];
    $idpelajaran = $row['idpelajaran'];
}

function ShowSelectStatusGuru($db)
{
    global $status;

    $sql = "SELECT status 
              FROM jbsakad.statusguru 
             ORDER BY urutan ASC";
    $result = $db->QueryDb($sql);

    echo "<select id='status' class='inputbox' style='width: 250px'>";
    while ($row = mysqli_fetch_array($result)) 
    {
        $selected = ($status == $row['status']) ? ' selected' : '';
        echo "<option value=\"{$row['status']}\"$selected>{$row['status']}</option>";
    }
    echo "</select>";
}


function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nipguru = RequestData("nipguru", "");
        $namaguru = RequestData("namaguru", "");
        $idpelajaran = (int) RequestData("idpelajaran", 0);
        $status = RequestData("status", "");
        $keterangan = RequestData("keterangan", "");
        $departemen = RequestData("departemen", "");

        $sql = "SELECT COUNT(g.replid) 
                  FROM jbsakad.guru g
                 WHERE g.nip = '$nipguru' 
                   AND g.idpelajaran = '$idpelajaran'";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData > 0) 
            return json_encode([-1, "Nama guru $namaguru sudah digunakan!"]);

        $sql = "INSERT INTO jbsakad.guru 
                   SET nip='$nipguru', idpelajaran=$idpelajaran, statusguru='$status', keterangan='$keterangan'";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "kdjv6");
        return json_encode([-1, $msg]);
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
        $nipguru = RequestData("nipguru", "");
        $namaguru = RequestData("namaguru", "");
        $status = RequestData("status", "");
        $keterangan = RequestData("keterangan", "");
        $departemen = RequestData("departemen", "");
        $idpelajaran = (int) RequestData("idpelajaran", 0);

        $sql = "SELECT COUNT(g.replid) 
                  FROM jbsakad.guru g
                 WHERE g.nip = '$nipguru' 
                   AND g.idpelajaran = '$idpelajaran' 
                   AND g.replid <> $replid";
        $nData = $db->ExecuteScalar($sql, 0);

        if ($nData) 
            return json_encode([-1, "Nama guru $namaguru sudah digunakan!"]);

        $sql = "UPDATE jbsakad.guru 
                   SET nip='$nipguru', idpelajaran=$idpelajaran, statusguru='$status', keterangan='$keterangan' 
                 WHERE replid=$replid";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $msg = Msg::InfoError($e->getMessage(), "k9607");
        return json_encode([-1, $msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>