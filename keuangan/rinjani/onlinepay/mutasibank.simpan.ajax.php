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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../library/common.func.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('../library/msg.php');
require_once('onlinepay.util.func.php');
require_once('onlinepay.jurnal.php');

$db = new Db();
try
{
    $db->Open();

    $departemen = $_REQUEST["departemen"];
    $bankNo = $_REQUEST["bankno"];
    $tglMutasi = $_REQUEST["tglmutasi"];
    $buktiValid = $_REQUEST["buktivalid"];
    $nomorTransfer = $_REQUEST["nomortransfer"];
    $buktiTransfer64 = "";
    $adaBukti = 0;
    if ($buktiValid == 1)
    {
        $adaBukti = 1;
        $buktiTransfer64 = base64_encode(file_get_contents($_FILES["buktitransfer"]["tmp_name"]));
    }
    $keterangan = SafeInput($_REQUEST["keterangan"]);

    
    $idTahunBuku = 0;
    $awalan = "";
    $cacah = 0;
    $sql = "SELECT replid, awalan, cacah
              FROM jbsfina.tahunbuku
             WHERE departemen = '$departemen'
               AND aktif = 1";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idTahunBuku = $row[0];
        $awalan = $row[1];
        $cacah = $row[2];
    }
    else
    {
        echo "[\"-1\",\"ERROR\",\"Tidak ditemukan data tahun buku\"]";
        return;
    }

    $rekKas = "";
    $rekPendapatan = "";
    $sql = "SELECT rekkas, rekpendapatan 
              FROM jbsfina.bank2
             WHERE bankno = '$bankNo'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $rekKas = $row[0];
        $rekPendapatan = $row[1];
    }
    else
    {
        echo "[\"-1\",\"ERROR\",\"Tidak ditemukan data rekening bank\"]";
        return;
    }

    $idPetugas = getLevel() == 0 ? "NULL" : "'" . getIdUser() . "'";
    $petugas = getUserName();

    $db->BeginTrans();

    $sql = "INSERT INTO jbsfina.bankmutasi2
               SET departemen = '$departemen', bankno = '$bankNo', jenis = 1, tanggal = '$tglMutasi', 
                   waktu = NOW(), keterangan = '$keterangan', petugas = $idPetugas, berkas = '$buktiTransfer64',
                   adaberkas = $adaBukti, nomormutasi = '$nomorTransfer'";
    $db->QueryDb($sql);

    $sql = "SELECT LAST_INSERT_ID()";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $idMutasi = $row[0];

    $nData = $_REQUEST["ndata"];
    for($i = 1; $i <= $nData; $i++)
    {
        $el = "iddeposit-$i";
        $idDeposit = $_REQUEST[$el];

        $el = "deposit-$i";
        $deposit = SafeInput($_REQUEST[$el]);

        $el = "jum-$i";
        $jumlah = $_REQUEST[$el];

        $el = "ket-$i";
        $keterangan = SafeInput($_REQUEST[$el]);

        $cacah += 1; // Increment cacah
        $noKas = $awalan . rpad($cacah, "0", 6); // Form nomor kas

        $transaksi = "Mutasi simpan $deposit";
        $idJurnal = OnlinePay_SimpanJurnal($db, $idTahunBuku, $tglMutasi, $transaksi, $noKas, $keterangan, $idPetugas, $petugas, "mutasisimpan");
        OnlinePay_SimpanDetailJurnal($db, $idJurnal, "K", $rekKas, $jumlah);
        OnlinePay_SimpanDetailJurnal($db, $idJurnal, "D", $rekPendapatan, $jumlah);

        $sql = "INSERT INTO jbsfina.bankmutasidata2
                   SET kategori = 'DPST', idmutasi = $idMutasi, idpenerimaan = 0, idtabungan = 0, idtabunganp = 0,
                       iddeposit = $idDeposit, jumlah = $jumlah, keterangan = '$keterangan', nokas = '$noKas'";
        $db->QueryDb($sql);

        $sql = "INSERT INTO jbsfina.banksaldo2 (departemen, bankno, kategori, idpenerimaan, idtabungan, idtabunganp, iddeposit, kelompok, saldo, lasttime) 
                VALUES ('$departemen','$bankNo','DPST', 0, 0, 0, $idDeposit, 1, $jumlah, NOW())
                    ON DUPLICATE KEY 
                UPDATE saldo = saldo + $jumlah, lasttime = NOW()";
        $db->QueryDb($sql);
    }

    $sql = "UPDATE jbsfina.tahunbuku 
               SET cacah = $cacah 
             WHERE replid = $idTahunBuku";
    $db->QueryDb($sql);

    $db->CommitTrans();

    echo "[\"1\",\"OK\"]";
}
catch (Exception $ex)
{
    $db->RollbackTrans();

    $msg = $ex->getMessage();
    echo "[\"-1\",\"ERROR\",\"$msg\"]";
}
finally
{
    $db->Close();
}

?>