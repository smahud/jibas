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
function ShowSelectDepartemen()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='departemen' name='departemen' class='inputbox' style='width: 250px' onchange='changeDep()'>";
        $dep = getDepartemen($db, getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k0eas");

    }
    finally
    {
        $db->Close();
    }


}

function ShowSelectTahunBuku()
{
    global $departemen;
    global $idTahunBuku, $tahunBuku;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='tahunbuku' name='tahunbuku' class='inputbox' style='width: 250px'>";
        $sql = "SELECT replid, tahunbuku, aktif 
                  FROM jbsfina.tahunbuku 
                 WHERE departemen = '$departemen' 
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($idTahunBuku == "")
            {
                $idTahunBuku = $row[0];
                $tahunBuku = $row[1];
            }

            $aktif = "";
            if ($row[2] == "1")
                $aktif = " (A)";

            echo "<option value='$row[0]'>$row[1] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k8trm");
    }
    finally
    {
        $db->Close();
    }

}

function ShowSelectTingkat()
{
    global $departemen;
    global $idTingkat, $tingkat;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='tingkat' name='tingkat' class='inputbox' style='width: 250px' onchange='changeTingkat()'>";
        $sql = "SELECT replid, tingkat FROM jbsakad.tingkat WHERE departemen = '$departemen' AND aktif = 1 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            if ($idTingkat == "")
            {
                $idTingkat = $row[0];
                $tingkat = $row[1];
            }
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kax96");
    }
    finally
    {
        $db->Close();
    }


}

function ShowTableKelas()
{
    global $idTingkat, $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT k.replid, k.kelas
                  FROM jbsakad.kelas k, jbsakad.tahunajaran ta
                 WHERE k.idtahunajaran = ta.replid
                   AND k.idtingkat = $idTingkat
                   AND ta.departemen = '$departemen'
                   AND ta.aktif = 1
                   AND k.aktif = 1
                 ORDER BY k.kelas";
        $res = $db->QueryDb($sql);
        $no = 0;

        echo "<table border='0' cellpadding='5' cellspacing='0'>";
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;

            echo "<tr>";
            echo "<td width='30' align='center'>";
            echo "<input type='checkbox' id='chkelas$no' name='chkelas$no'>";
            echo "</td>";
            echo "<td width='450' align='left'>";
            echo $row[1];
            echo "<input type='hidden' id='idkelas$no' name='idkelas$no' value='$row[0]'>";
            echo "<input type='hidden' id='kelas$no' name='kelas$no' value='$row[1]'>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' id='nkelas' name='nkelas' value='$no'>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kjrg1");
    }
    finally
    {
        $db->Close();
    }
}

function ShowTableIuran()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, nama 
                  FROM jbsfina.datapenerimaan 
                 WHERE aktif = 1 
                   AND idkategori = 'JTT' 
                   AND departemen = '$departemen' 
                 ORDER BY nama DESC";
        $res = $db->QueryDb($sql);
        $no = 0;

        echo "<table border='0' cellpadding='5' cellspacing='0' id='tabIuran'>";
        echo "<tr>";
        echo "<td width='30' align='center' class='header'>&nbsp;</td>";
        echo "<td width='250' align='left' class='header'>Iuran</td>";
        echo "<td width='250' align='left' class='header'>Diskon</td>";
        echo "</tr>";
        while($row = mysqli_fetch_row($res))
        {
            $no += 1;

            echo "<tr>";
            echo "<td align='center'>";
            echo "<input type='checkbox' id='chiuran$no' name='chiuran$no' onclick='onCheckIuran($no)'>";
            echo "</td>";
            echo "<td align='left'>";
            echo $row[1];
            echo "<input type='hidden' id='idiuran$no' name='idiuran$no' value='$row[0]'>";
            echo "<input type='hidden' id='iuran$no' name='iuran$no' value='$row[1]'";
            echo "</td>";
            echo "<td align='left'>";
            $elDiskon = "diskon$no";
            echo "<input type='text' id='$elDiskon' name='$elDiskon' value='Rp 0' class='inputbox-money' style='width: 180px ' disabled='disabled' onfocus=\"unformatRupiah('$elDiskon')\" onblur=\"formatRupiah('$elDiskon')\">";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' id='niuran' name='niuran' value='$no'>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "knbuk");
    }
    finally
    {
        $db->Close();
    }


}

function ShowSelectBulan()
{
    echo "<select id='bulan' name='bulan' class='inputbox' style='width: 150px'>";
    for($bln = 1; $bln <= 12; $bln++)
    {
        $sel = $bln == date('n') ? "selected" : "";
        $nama = NamaBulan($bln);
        echo "<option value='$bln' $sel>$nama</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    echo "<select id='tahun' name='tahun' class='inputbox' style='width: 100px'>";

    $currThn = date('Y');
    for($thn = $currThn - 1; $thn <= $currThn + 1; $thn++)
    {
        $sel = $thn == $currThn ? "selected" : "";
        echo "<option value='$thn' $sel>$thn</option>";
    }
    echo "</select>";
}

function CreateInvoice()
{
    global $PG_SCHOOL_ID;

    $db = new Db();
    try
    {
        $db->Open();
        $db->BeginTrans();

        $dept = $_REQUEST["dept"];
        $idTahunBuku = $_REQUEST["idtahunbuku"];
        $tahunBuku = $_REQUEST["tahunbuku"];
        $idTingkat = $_REQUEST["idtingkat"];
        $tingkat = $_REQUEST["tingkat"];
        $stIdKelas = $_REQUEST["idkelas"];
        $stKelas = $_REQUEST["kelas"];
        $stSkipSiswa = $_REQUEST["skiplist"];
        $stIdIuran = $_REQUEST["idiuran"];
        $stIuran = $_REQUEST["iuran"];
        $stDiskon = $_REQUEST["diskon"];
        $bulan = $_REQUEST["bulan"];
        $namaBulan = NamaBulan($bulan);
        $tahun = $_REQUEST["tahun"];
        $keterangan = $_REQUEST["keterangan"];
        $sendNotif = $_REQUEST["sendnotif"];
        $skipAlreadyPaid = $_REQUEST["skipalreadypaid"];

        $bulanTahunTagihan  = str_pad($bulan, 2, '0', STR_PAD_LEFT);
        $bulanTahunTagihan .= substr($tahun, 2, 2);

        // ----- 01 ambil format nomor tagihan
        $awalanNoTagihan = "";
        $sql = "SELECT awalan 
                  FROM jbsfina.formatnomortagihan2
                 WHERE departemen = '$dept'";
        $res = $db->QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $awalanNoTagihan = $row[0];
        }
        else
        {
            // -- belum ada format nomor tagihan, sekalian inisiasi

            $sql = "SELECT replid
                      FROM jbsakad.departemen 
                     WHERE departemen = '$dept'";
            $res = $db->QueryDbEx($sql);
            if ($row = mysqli_fetch_row($res))
            {
                $awalanNoTagihan = $row[0];

                $sql = "INSERT INTO jbsfina.formatnomortagihan2
                           SET awalan = '$awalanNoTagihan', departemen = '$dept', issync = 0";
                $db->QueryDbEx($sql);
            }
        }

        // ---- 02 ambil pesan notifikasi tagihan
        $pesanNotifikasiTagihan = "";
        $sql = "SELECT pesan 
                  FROM jbsfina.formatpesanpg2
                 WHERE departemen = '$dept'
                   AND kelompok = 'TAGIHAN'";
        $res = $db->QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $pesanNotifikasiTagihan = $row[0];
        }
        else
        {
            // -- belum ada format pesan notifikasi, sekalian inisiasi

            $pesanNotifikasiTagihan = "Kami informasikan {NAMA} {NIS} memiliki tagihan sebesar {JUMLAH} untuk {IURAN} bulan {BULAN} {TAHUN}";

            $sql = "INSERT INTO jbsfina.formatpesanpg2
                       SET pesan = '$pesanNotifikasiTagihan', departemen = '$dept', kelompok = 'TAGIHAN', issync = 0";
            $db->QueryDbEx($sql);
        }

        // ----- 03 ambil counter tagihan
        $counterTagihan = 0;
        $sql = "SELECT counter
                  FROM jbsfina.tagihancount2
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $res = $db->QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $counterTagihan = $row[0];
        }
        else
        {
            // -- belum ada counter tagihan, sekalian inisiasi

            $sql = "INSERT INTO jbsfina.tagihancount2
                       SET departemen = '$dept', bulan = $bulan, tahun = $tahun, counter = 0";
            $db->QueryDbEx($sql);
        }

        // ----- 04 ambil counter tagihan set --- 2023-08-02
        $counterTagihanSet = 0;
        $sql = "SELECT counter
                  FROM jbsfina.tagihansetcount2
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $res = $db->QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $counterTagihanSet = $row[0];
        }
        else
        {
            // -- belum ada counter tagihan set, sekalian inisiasi

            $sql = "INSERT INTO jbsfina.tagihansetcount2
                       SET departemen = '$dept', bulan = $bulan, tahun = $tahun, counter = 0";
            $db->QueryDbEx($sql);
        }

        // ---- 05 ambil data siswa
        $lsSkipSiswa = array();
        if (strlen($stSkipSiswa) > 0)
        {
            $lsTemp = explode(",", $stSkipSiswa);
            for($i = 0; $i < count($lsTemp); $i++)
            {
                $temp = trim($lsTemp[$i]);
                if (strlen($temp) == 0) continue;
                $lsSkipSiswa[] = $temp;
            }
        }

        $lsSiswa = array();
        $sql = "SELECT nis, nama 
                  FROM jbsakad.siswa
                 WHERE idkelas IN ($stIdKelas)
                   AND aktif = 1
                 ORDER BY nama";
        $res = $db->QueryDbEx($sql);
        while($row = mysqli_fetch_row($res))
        {
            if (in_array($row[0], $lsSkipSiswa))
                continue;

            $lsSiswa[] = array($row[0], $row[1]);
        }
        $nSiswa = count($lsSiswa);

        if ($nSiswa == 0)
        {
            // ---- tidak ada data siswa
            $db->RollbackTrans();
            return json_encode([0, "Tidak ditemukan data siswa", ""]);
        }

        // ---- 06.a buat tagihan set
        $counterTagihanSet += 1;
        $counterSet = str_pad($counterTagihanSet, 5, '0', STR_PAD_LEFT);
        $noTagihanSet = "TS$PG_SCHOOL_ID.$awalanNoTagihan$bulanTahunTagihan.$counterSet";
        $namaTagihan = "Tagihan $namaBulan $tahun, $dept tingkat $tingkat kelas $stKelas";
        $petugas = getLevel() == 0 ?  "NULL" : "'" . getIdUser() . "'";

        $sql = "INSERT INTO jbsfina.tagihanset2
                   SET nomor = '$noTagihanSet', nama = '$namaTagihan', departemen = '$dept', idtahunbuku = '$idTahunBuku', 
                       idtingkat = $idTingkat, petugas = $petugas, 
                       bulan = $bulan, tahun = $tahun, idkelas = '$stIdKelas',
                       idiuran = '$stIdIuran', stiuran = '$stIuran', 
                       keterangan = '$keterangan', tanggalbuat = NOW(),
                       issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
        $db->QueryDbEx($sql);

        $idTagihanSet = 0;
        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDbEx($sql);
        if ($row = mysqli_fetch_row($res))
            $idTagihanSet = $row[0];

        // ----- 06.b update counterset
        $sql = "UPDATE jbsfina.tagihansetcount2
                   SET counter = $counterTagihanSet
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $db->QueryDbEx($sql);

        // ---- 07.a Ambil jumlah biaya layanan yang aktif
        $sql = "SELECT SUM(biaya)
                  FROM jbsfina.pgservicefee2
                 WHERE departemen = '$dept'
                   AND aktif = 1";
        $res = $db->QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $serviceFee = (int) $row[0];

        // ---- 07.b Ambil daftar biaya layanan yang aktif
        $lsServiceFee = [];
        $sql = "SELECT id, kode, nama, biaya
                  FROM jbsfina.pgservicefee2
                 WHERE departemen = '$dept'
                   AND aktif = 1";
        $res = $db->QueryDbEx($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsServiceFee[] = [$row[0], $row[1], $row[2], $row[3]];
        }
        //$lsServiceFee[] = ["0", "0", "Tanda Transaksi", "$tandaTransaksi"];
        //$jsonServiceFee = json_encode($lsServiceFee);

        // ----- 08 BIKIN INVOICE
        $nInvoiceCreated = 0;
        $lsDiskon = explode(",", $stDiskon);
        $lsIdIuran = explode(",", $stIdIuran);
        $nIdIuran = count($lsIdIuran);
        for($i = 0; $i < $nSiswa; $i++)
        {
            $nis = $lsSiswa[$i][0];
            $nama = $lsSiswa[$i][1];

            // ----- 08.a ambil data penerimaan yg sudah Lunas atau Gratis
            $lsFinished = array();
            $sql = "SELECT b.idpenerimaan
                      FROM jbsfina.besarjtt b 
                     WHERE b.nis = '$nis'
                       AND b.info2 = '$idTahunBuku'
                       AND b.lunas IN (1,2)";
            $res = $db->QueryDbEx($sql);
            while($row = mysqli_fetch_row($res))
            {
                $lsFinished[] = $row[0];
            }

            // ----- 08.b ambil penerimaan yg sudah dibayarkan cicilannya pada bulan tahun terpilih
            $lsPaid = array();
            if ($skipAlreadyPaid == 1)
            {
                $sql = "SELECT DISTINCT b.idpenerimaan
                          FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b 
                         WHERE p.idbesarjtt  = b.replid
                           AND b.nis = '$nis'
                           AND b.lunas = 0
                           AND b.info2 = '$idTahunBuku'
                           AND p.jumlah > 0
                           AND MONTH(p.tanggal) = $bulan
                           AND YEAR(p.tanggal) = $tahun";
                $res = $db->QueryDbEx($sql);
                while($row = mysqli_fetch_row($res))
                {
                    $lsPaid[] = $row[0];
                }
            }

            // ----- 08.c ambil data besar penerimaan s
            $lsBesarSet = array();
            $sql = "SELECT DISTINCT idpenerimaan
                      FROM jbsfina.besarjtt 
                     WHERE nis = '$nis'
                       AND info2 = '$idTahunBuku'";
            $res = $db->QueryDbEx($sql);
            while($row = mysqli_fetch_row($res))
            {
                $lsBesarSet[] = $row[0];
            }

            // ---- 08.d ambil data tagihan yang sudah dibuat
            $lsPrepared = array();
            $sql = "SELECT t.idpenerimaan
                      FROM jbsfina.tagihansiswadata2 t, jbsfina.besarjtt b
                     WHERE t.idbesarjtt = b.replid
                       AND b.info2 = '$idTahunBuku'
                       AND t.nis = '$nis'
                       AND t.bulan = $bulan
                       AND t.tahun = $tahun
                       AND t.status = 0
                       AND t.aktif = 1";
            $res = $db->QueryDbEx($sql);
            while($row = mysqli_fetch_row($res))
            {
                $lsPrepared[] = $row[0];
            }

            // ----- 08.e idinvoice berisi idpenerimaan yg belum lunas, belum gratis dan belum dibayarkan
            $lsIdInvoice = array();
            $lsDiskonInvoice = array();
            for($j = 0; $j < $nIdIuran; $j++)
            {
                $idIuran = $lsIdIuran[$j];

                if (in_array($idIuran, $lsFinished))
                    continue; // iuran sudah lunas atau gratis

                if (in_array($idIuran, $lsPaid))
                    continue; // iuran sudah dibayarkan bulan ini

                if (in_array($idIuran, $lsPrepared))
                    continue; // iuran sudah ada di tagihan yg telah dibuat

                if (in_array($idIuran, $lsBesarSet))
                    $lsIdInvoice[] = $idIuran; // iuran sudah di set besar pembayarannya

                $lsDiskonInvoice[$idIuran] = $lsDiskon[$j];
            }

            $nInvoice = count($lsIdInvoice);
            if ($nInvoice == 0)
                continue; // tidak ada tagihan utk siswa ybs

            // --- 08.f ambil data besar tagihan, besar cicilan
            $stIdInvoice = json_encode($lsIdInvoice);
            $stIdInvoice = str_replace("[", "", $stIdInvoice);
            $stIdInvoice = str_replace("]", "", $stIdInvoice);
            $stIdInvoice = str_replace("\"", "", $stIdInvoice);

            $lsIdBesarJtt = array();
            $sql = "SELECT b.replid, b.besar, b.cicilan, b.idpenerimaan, dp.nama
                      FROM jbsfina.besarjtt b, jbsfina.datapenerimaan dp
                     WHERE b.idpenerimaan = dp.replid
                       AND b.idpenerimaan IN ($stIdInvoice)
                       AND b.nis = '$nis'
                       AND b.info2 = '$idTahunBuku'";  // change on 2023-03-31
            $res = $db->QueryDbEx($sql);
            while($row = mysqli_fetch_row($res))
            {
                $idIuran = $row[3];
                $diskon = $lsDiskonInvoice[$idIuran];

                // replid besarjtt, besar, cicilan, idpenrimaan, nama, diskon
                $lsIdBesarJtt[] = array($row[0], $row[1], $row[2], $row[3], $row[4], $diskon);
            }

            // --- 08.g format nomor tagihan
            // -- T{schoolid}.{awalan}{bulan}{tahun}.{rand4}.{count6}
            $counterTagihan += 1;
            $counter = str_pad($counterTagihan, 6, '0', STR_PAD_LEFT);
            $randNo = rand(1000, 9999);
            $noTagihan = "T$PG_SCHOOL_ID.$awalanNoTagihan$bulanTahunTagihan.$randNo.$counter";

            // 2026-03-17
            $tandaTransaksi = rand(10, 99);

            // ---- 08.h create invoice
            $totalTagihan = 0;
            $stTagihan = "";
            $nIdBesarJtt = count($lsIdBesarJtt);
            for($j = 0; $j < $nIdBesarJtt; $j++)
            {
                $idBesarJtt = $lsIdBesarJtt[$j][0];
                $besarJtt = $lsIdBesarJtt[$j][1];
                $cicilanJtt = $lsIdBesarJtt[$j][2];
                $idPenerimaan = $lsIdBesarJtt[$j][3];
                $namaPenerimaan = $lsIdBesarJtt[$j][4];
                $diskon = $lsIdBesarJtt[$j][5];

                $jumlahBayar = 0;
                $jumlahSisa = 0;

                // 2026-03-17
                $serviceFeeSiswa = $serviceFee + $tandaTransaksi;
                $lsServiceFeeSiswa = $lsServiceFee;
                $lsServiceFeeSiswa[] = ["0", "TT", "Tanda Transaksi", "$tandaTransaksi"];
                $jsonServiceFeeSiswa = json_encode($lsServiceFeeSiswa);

                $sql = "SELECT IFNULL(SUM(jumlah) + SUM(info1), 0)
                          FROM jbsfina.penerimaanjtt
                         WHERE idbesarjtt = $idBesarJtt";
                $res = $db->QueryDbEx($sql);
                if ($row = mysqli_fetch_row($res))
                {
                    $jumlahBayar = $row[0];
                    $jumlahSisa = $besarJtt - $jumlahBayar;
                }

                $totalTagihan += $cicilanJtt - $diskon;
                if ($stTagihan != "") $stTagihan .= ", ";
                $stTagihan .= $namaPenerimaan;

                $sql = "INSERT INTO jbsfina.tagihansiswadata2
                           SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                               idbesarjtt = $idBesarJtt, idpenerimaan = $idPenerimaan, kode = 'JTT', penerimaan = '$namaPenerimaan', jtagihan = $cicilanJtt, 
                               jdiskon = $diskon, jbesar = $besarJtt, jbayar = $jumlahBayar, jsisa = $jumlahSisa, status = 0, aktif = 1,
                               issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
                $db->QueryDbEx($sql);
            }

            // ---- 08.i Biaya layanan
            $sql = "INSERT INTO jbsfina.tagihansiswadata2
                       SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                           idbesarjtt = NULL, idpenerimaan = NULL, kode = 'BL', penerimaan = 'Biaya Layanan', jtagihan = $serviceFeeSiswa, jdiskon = 0, 
                           jbesar = 0, jbayar = 0, jsisa = 0, status = 0, aktif = 1,
                           issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
            $db->QueryDbEx($sql);

            $totalTagihan += $serviceFee + $tandaTransaksi;

            $sql = "INSERT INTO jbsfina.tagihansiswainfo2
                       SET idtagihanset = $idTagihanSet, nis = '$nis', bulan = $bulan, tahun = $tahun, notagihan = '$noTagihan', 
                           info = 'Tagihan bulan $namaBulan $tahun untuk $stTagihan', jumlah = $totalTagihan, status = 0, aktif = 1,
                           jsonfees = '$jsonServiceFeeSiswa', servicefee = $serviceFeeSiswa, issync = 0, token = ROUND((RAND() * (99999 - 10000)) + 10000)";
            $db->QueryDbEx($sql);

            // Kirim Notifikasi
            if ($sendNotif == 1)
            {
                $jumNotif = $totalTagihan; // + $PG_SERVICE_FEE;

                $pesan = str_replace("{NAMA}", $nama, $pesanNotifikasiTagihan);
                $pesan = str_replace("{NIS}", $nis, $pesan);
                $pesan = str_replace("{JUMLAH}", FormatRupiah($jumNotif), $pesan);
                $pesan = str_replace("{IURAN}", $stIuran, $pesan);
                $pesan = str_replace("{BULAN}", NamaBulan($bulan), $pesan);
                $pesan = str_replace("{TAHUN}", $tahun, $pesan);

                CreateSMSTunggakan2($db, "SISPAY", $dept, $nis, $nama, $pesan);
            }

            // -- INCREMENT INVOICE CREATED
            $nInvoiceCreated += 1;
        }

        //$log->Log("DONE");
        //$log->Close();

        if ($nInvoiceCreated == 0)
        {
            $db->RollbackTrans();
            return json_encode([0, "Tidak ada tagihan yang disiapkan, karena tagihannya sudah dibuat atau iurannya sudah dibayarkan / dilunasi", ""]);
        }

        // -- update counter
        $sql = "UPDATE jbsfina.tagihancount2
                   SET counter = $counterTagihan
                 WHERE departemen = '$dept'
                   AND bulan = $bulan
                   AND tahun = $tahun";
        $db->QueryDbEx($sql);

        $db->CommitTrans();
        //$db->RollbackTrans();

        return json_encode([1, "Berhasil menyiapkan $nInvoiceCreated tagihan", ""]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        //$log->Log($ex->getCode() . " " . $ex->getMessage());
        //$log->Close();
        Logger::LogErrorOnce($ex, "k86e4");

        return json_encode([-1, "ERROR: " . $ex->getMessage(), ""]);
    }
    finally
    {
        $db->Close();
    }

}
?>
