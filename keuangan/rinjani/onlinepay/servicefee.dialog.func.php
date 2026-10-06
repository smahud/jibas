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
function LoadServiceFeeValue($db)
{
    global $idServiceFee;
    global $dept, $kode, $nama, $biaya, $keterangan;
    global $rekKas, $rekPendapatan, $namaKas, $namaPendapatan, $useInTrans;
    global $lsFeeDept;

    if ($idServiceFee == 0)
        return;

    $sql = "SELECT s.kode, s.nama, s.biaya, s.keterangan, s.rekkas, s.rekpendapatan,
                   a1.nama AS namakas, a2.nama AS namapendapatan, s.departemen
              FROM jbsfina.pgservicefee2 s, jbsfina.rekakun a1, jbsfina.rekakun a2
             WHERE s.rekkas = a1.kode
               AND s.rekpendapatan = a2.kode
               AND s.id = $idServiceFee";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $kode = $row["kode"];
        $nama = $row["nama"];
        $biaya = FormatRupiah($row["biaya"]);
        $keterangan = $row["keterangan"];
        $rekKas = $row["rekkas"];
        $rekPendapatan = $row["rekpendapatan"];
        $namaKas = $row["namakas"];
        $namaPendapatan = $row["namapendapatan"];
        $dept = $row["departemen"];
    }

    /*
    $sql = "SELECT departemen 
              FROM jbsfina.pgservicefeedept2
             WHERE idservicefee = '$idServiceFee'";
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsFeeDept[] = $row[0];
    }
        */

    // TODO: cek pemakaian di transaksi
    $useInTrans = false;
}

function ShowCheckBoxDepartemen($db)
{
    global $lsFeeDept;

    $lsDept = getDepartemen($db, getAccess());

    $nDept = 0;
    foreach($lsDept as $dept)
    {
        $checked = in_array($dept, $lsFeeDept) ? 'checked' : '';
        echo "<input type='checkbox' style='margin-bottom: 10px;' id='dept$nDept' value='$dept' $checked> $dept<br>";
        $nDept += 1;
    }
    echo "<input type='hidden' id='ndept' value='$nDept'>";
}       


function SimpanBiayaLayanan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idServiceFee = $_REQUEST["idservicefee"];
        $dept = $_REQUEST["dept"];
        $kode = SafeValueHtml($_REQUEST["kode"]);
        $nama = SafeValueHtml($_REQUEST["nama"]);
        $biaya = SafeValueHtml($_REQUEST["biaya"]);
        $keterangan = SafeValueHtml($_REQUEST["keterangan"]);
        $rekKas = $_REQUEST["rekkas"];
        $rekPendapatan = $_REQUEST["rekpendapatan"];

        if ($idServiceFee == 0)
        {
            $sql = "SELECT COUNT(id)
                      FROM jbsfina.pgservicefee2 
                     WHERE kode = '$kode'
                       AND departemen = '$dept' ";
            $nData = $db->FetchSingle($sql, 0);
            if ($nData > 0)
                return json_encode([-1, "Kode biaya layanan $kode sudah terdata!"]);

            $sql = "INSERT INTO jbsfina.pgservicefee2 
                       SET departemen = '$dept', kode = '$kode', nama = '$nama', biaya = '$biaya', 
                           keterangan = '$keterangan', rekkas = '$rekKas', rekpendapatan = '$rekPendapatan',
                           aktif = 1, issync = 0";
            $db->QueryDb($sql);

            return json_encode([1, "OK"]);
        }

        $sql = "SELECT COUNT(id)
                  FROM jbsfina.pgservicefee2 
                 WHERE kode = '$kode'
                   AND departemen = '$dept' 
                   AND id <> $idServiceFee";
        $nData = $db->FetchSingle($sql, 0);
        if ($nData > 0)
            return json_encode([-1, "Kode biaya layanan $kode sudah terdata!"]);

        $sql = "UPDATE jbsfina.pgservicefee2
                   SET kode = '$kode', nama = '$nama', biaya = '$biaya',  
                       rekkas = '$rekKas', rekpendapatan = '$rekPendapatan',
                       keterangan = '$keterangan', issync = 0 
                 WHERE id = $idServiceFee ";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>