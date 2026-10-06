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
function LoadBankValue($db)
{
    global $idBank, $useInTrans;
    global $bank, $bankLoc, $bankName, $bankNo, $vaNo, $qris, $qrisName, $qrisId, $urutan, $lsBankDept;
    global $keterangan, $rekKas, $namaRekKas, $rekPendapatan, $namaRekPendapatan;

    $sql = "SELECT b.bank, b.bankname, b.bankno, b.bankloc, b.bankvano, b.qris, b.keterangan, b.urutan,
                   b.rekkas, r1.nama as namarekkas, b.rekpendapatan, r2.nama as namarekpendapatan, b.aktif,
                   b.qrisname, b.qrisid
              FROM jbsfina.bank2 b, jbsfina.rekakun r1, jbsfina.rekakun r2
             WHERE b.rekkas = r1.kode
               AND b.rekpendapatan = r2.kode
               AND b.replid = $idBank";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $bank = $row["bank"];
        $bankLoc = $row["bankloc"];
        $bankName = $row["bankname"];
        $bankNo = $row["bankno"];
        $vaNo = $row["bankvano"];
        $qris = $row["qris"];
        $qrisName = $row["qrisname"];
        $qrisId = $row["qrisid"];
        $urutan = $row["urutan"];
        $keterangan = $row["keterangan"];
        $rekKas = $row["rekkas"];
        $namaRekKas = $row["namarekkas"];
        $rekPendapatan = $row["rekpendapatan"];
        $namaRekPendapatan = $row["namarekpendapatan"];
    }

    // TODO: Check bank already use in trans
    $useInTrans = false;
}

function SimpanBank()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idBank = RequestData('idbank', 0);
        $bank = RequestData('bank', '');
        $departemen = RequestData('departemen', '');
        $bankLoc = RequestData('bankloc', '');
        $bankName = RequestData('bankname', '');
        $bankNo = RequestData('bankno', '');
        $vaNo = RequestData('vano', '');
        $urutan = RequestData('urutan', 0);
        $keterangan = RequestData('keterangan', '');
        $rekKas = RequestData('rekkas', '');
        $rekPendapatan = RequestData('rekpendapatan', '');
        $qrisName = RequestData("qrisname", "");
        $qrisId = RequestData("qrisid", "");

        $db->BeginTrans();

        if ($idBank == 0)
        {
            $qrisExist = 0;
            $qrisMime = "";
            $qris = "";
            if (isset($_FILES['qris']) && $_FILES['qris']['error'] == UPLOAD_ERR_OK) 
            {
                $qrisExist = 1;

                $tmpFile = $_FILES['qris']['tmp_name'];
                $qrisMime = mime_content_type($tmpFile);

                $qris = base64_encode(file_get_contents($_FILES['qris']['tmp_name']));
            } 
            
            // Insert new bank record
            $sql = "INSERT INTO jbsfina.bank2 
                           (bank, departemen, bankno, bankname, bankloc, bankvano, 
                            qrisexist, qris, qrismime, qrisname, qrisid, 
                            urutan, keterangan, rekkas, rekpendapatan, aktif)
                    VALUES ('$bank', '$departemen', '$bankNo', '$bankName', '$bankLoc', '$vaNo', 
                            '$qrisExist', '$qris', '$qrisMime', '$qrisName', '$qrisId',
                            $urutan, '$keterangan', '$rekKas', '$rekPendapatan', 1)";
            $db->QueryDb($sql);
        }
        else
        {
            $qrisExist = 0;
            $qrisMime = "";
            $qris = "";
            if (isset($_FILES['qris']) && $_FILES['qris']['error'] == UPLOAD_ERR_OK) 
            {
                $qrisExist = 1;

                $tmpFile = $_FILES['qris']['tmp_name'];
                $qrisMime = mime_content_type($tmpFile);

                $qris = base64_encode(file_get_contents($_FILES['qris']['tmp_name']));
            } 

            // Update existing bank record
            if ($qrisExist == 1)
            {
                $sql = "UPDATE jbsfina.bank2 
                           SET bank='$bank', bankno='$bankNo', bankname='$bankName', bankloc='$bankLoc', bankvano='$vaNo', 
                               qrisexist = '$qrisExist', qris='$qris', qrismime = '$qrisMime', qrisname = '$qrisName', qrisid = '$qrisId',
                               urutan=$urutan, keterangan='$keterangan', rekkas='$rekKas', rekpendapatan='$rekPendapatan' 
                         WHERE replid = $idBank";    
            }
            else 
            {
                $sql = "UPDATE jbsfina.bank2 
                           SET bank='$bank', bankno='$bankNo', bankname='$bankName', bankloc='$bankLoc', bankvano='$vaNo', 
                               urutan=$urutan, keterangan='$keterangan', rekkas='$rekKas', rekpendapatan='$rekPendapatan' 
                         WHERE replid = $idBank";    
            }
            
            $db->QueryDb($sql);
        }
        
        $db->CommitTrans();

        return json_encode([1,"OK"]);
    }
    catch(Exception $ex)
    {
        $db->RollbackTrans();

        $msg = Msg::InfoError($ex->getMessage(), "");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}
?>