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
function SetBiayaLayananAktif()
{
    $db = new Db();
    try
    {
        $db->Open();

        $id = $_REQUEST["id"];
        $newAktif = $_REQUEST["newaktif"];
        $dept = $_REQUEST["dept"];

        $sql = "UPDATE jbsfina.pgservicefee2 SET aktif = $newAktif WHERE id = $id";
        $db->QueryDb($sql);

        $sql = "SELECT SUM(biaya)
                  FROM jbsfina.pgservicefee2
                 WHERE departemen = '$dept'
                   AND aktif = 1";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $totalRp = "<b>" . FormatRupiah($row[0]) . "</b>";

        return json_encode([1, $totalRp]);
    }
    catch (Exception $ex)
    {
        return json_encode([-99, $ex->getMessage()]);
    }
}

function HapusBiayaLayanan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $id = $_REQUEST["id"];

        $sql = "DELETE FROM jbsfina.pgservicefee2 WHERE id = '$id'";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        return json_encode([-99, $ex->getMessage()]);
    }
}

function UpdateServiceFeeOnInvoices()
{
    $db = new Db();
    try
    {
        $db->Open();

        $dept = $_REQUEST["dept"];

        $serviceFee = 0;
        $lsServiceFee = array();
        $sql = "SELECT id, kode, nama, biaya
                  FROM jbsfina.pgservicefee2
                 WHERE departemen = '$dept'";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $serviceFee += (int) $row[3];
            $lsServiceFee[] = [$row[0], $row[1], $row[2], $row[3]];
        }

        $stNoTagihan = "";
        $sql = "SELECT IFNULL(GROUP_CONCAT(ti.notagihan SEPARATOR ','), '')
                  FROM jbsfina.tagihanset2 ts, jbsfina.tahunbuku tb, jbsfina.tagihansiswainfo2 ti
                 WHERE ts.idtahunbuku = tb.replid
                   AND ts.replid = ti.idtagihanset
                   AND tb.departemen = '$dept'
                   AND tb.aktif = 1
                   AND ti.status = 0";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $stNoTagihan = $row[0];

        if (strlen($stNoTagihan) == 0)
            return json_encode([-1, "Tidak ada data tagihan yang akan di update biaya layanannya"]);

        $db->BeginTrans();

        $lsNoTagihan = explode( ",", $stNoTagihan);
        $nTagihan = count($lsNoTagihan);
        for($i = 0; $i < $nTagihan; $i++)
        {
            $noTagihan = $lsNoTagihan[$i];

            $tandaTransaksi = rand(10, 99);
            $serviceFeeStudent = $serviceFee +  $tandaTransaksi;

            $lsServiceFeeStudent = $lsServiceFee;
            $lsServiceFeeStudent[] = ["0", "TT", "Tanda Transaksi", "$tandaTransaksi"];
            $jsonServiceFeeStudent = json_encode($lsServiceFeeStudent);

            $sql = "UPDATE jbsfina.tagihansiswainfo2
                       SET servicefee = ?, 
                           jsonfees = ?
                     WHERE notagihan = '$noTagihan'";
            $stmt = $db->PrepareStatement($sql);
            $stmt->bind_param("is", $serviceFeeStudent, $jsonServiceFeeStudent);
            $stmt->execute();

            $sql = "UPDATE jbsfina.tagihansiswadata2 
                       SET jtagihan = $serviceFeeStudent
                     WHERE kode = 'BL'
                       AND notagihan = '$noTagihan'";
            $db->QueryDb($sql);

            $sql = "UPDATE jbsfina.tagihansiswainfo2
                       SET jumlah = (
                           SELECT SUM(jtagihan - jdiskon)
                             FROM jbsfina.tagihansiswadata2
                            WHERE notagihan = '$noTagihan')
                     WHERE notagihan = '$noTagihan'";
            $db->QueryDb($sql);
        }
        
        $db->CommitTrans();

        return json_encode([1, "Berhasil update biaya layanan untuk tagihan"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-99, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>