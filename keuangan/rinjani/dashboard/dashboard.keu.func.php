<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
function ShowSelectTahunBuku($db)
{
    global $departemen, $idTahunBuku;

    $sql = "SELECT replid, tahunbuku, aktif
              FROM jbsfina.tahunbuku
             WHERE departemen = '$departemen'
             ORDER BY aktif DESC, replid DESC";
    $res = $db->QueryDb($sql);
        
    echo "<select id='tahunbuku' onchange='onChangeTahunBuku()' class='inputbox' style='width:150px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        if ($idTahunBuku == 0)
            $idTahunBuku = $row['replid'];

        $aktif = "";
        if ($row['aktif'] == 1)
            $aktif = " (Aktif)";

        $sel = $idTahunBuku == $row['replid'] ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[tahunbuku] $aktif</option>";
    }
    echo "</select>";
}

function GetPaymentInfoJtt($db, $idpenerimaan, $idtahunbuku, $nis)
{
    $obj = new stdClass();

    $sql = "SELECT nama, rekkas, rekpendapatan, rekpiutang, info1 AS rekdiskon, info2 AS sendnotif
              FROM jbsfina.datapenerimaan 
             WHERE replid = '$idpenerimaan'";
    $result = $db->QueryDb($sql);
    if (mysqli_num_rows($result) == 0)
    {
        $obj->Exist = false;
        return $obj;
    }

    $row = mysqli_fetch_row($result);
    $obj->Exist = true;
    $obj->IdPenerimaan = $idpenerimaan;
    $obj->Penerimaan = $row[0];
    $obj->IdTahunBuku = $idtahunbuku;
    $obj->RekKas = $row[1];
    $obj->RekPendapatan = $row[2];
    $obj->RekPiutang = $row[3];
    $obj->RekDiskon = $row[4];
    $obj->SendNotif = $row[5];

    $sql = "SELECT b.replid AS id, b.besar, b.keterangan, b.lunas, b.info1 AS idjurnal, cicilan
	          FROM jbsfina.besarjtt b
		     WHERE b.nis = '$nis' 
		       AND b.idpenerimaan = '$idpenerimaan' 
		       AND b.info2 = '$idtahunbuku'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        $obj->IdBesarJtt = 0;
    }
    else
    {
        $row = mysqli_fetch_row($res);
        $obj->IdBesarJtt = $row[0];
        $obj->Besar = $row[1];
        $obj->Keterangan = $row[2];
        $obj->Lunas = $row[3];
        $obj->IdJurnal = $row[4];
        $obj->Cicilan = $row[5];
    }

    return $obj;
}

function ShowLaporanPembayaranSiswa($db)
{
    global $nis, $idTahunBuku;

    $sql = "SELECT 1 
              FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p 
             WHERE p.idbesarjtt = b.replid 
               AND b.nis='$nis' 
               AND b.info2 = '$idTahunBuku'
             LIMIT 1  ";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $nwajib = $row[0];

    $sql = "SELECT 1
              FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j
             WHERE p.idjurnal = j.replid 
               AND j.idtahunbuku = '$idTahunBuku' 
               AND p.nis = '$nis' 
               AND p.tanggal
             LIMIT 1";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $niuran = $row[0];  

    $sql = "SELECT 1
              FROM jbsfina.tabungan t, jbsfina.datatabungan dt
             WHERE t.idtabungan = dt.replid
               AND t.nis = '$nis'
             LIMIT 1";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $ntabungan = $row[0];  

    if ($nwajib + $niuran + $ntabungan == 0)
    {
        echo "<span style='color: maroon; font-size: 13px;'>";
        echo "Belum ada pembayaran yang dilakukan siswa";
        echo "</span>";

        return;
    }

    $sql = "SELECT DISTINCT b.replid AS id, b.besar, b.lunas, b.keterangan, d.nama, b.idpenerimaan 
              FROM jbsfina.besarjtt b, jbsfina.penerimaanjtt p, jbsfina.datapenerimaan d 
             WHERE p.idbesarjtt = b.replid 
               AND b.idpenerimaan = d.replid 
               AND b.nis = '$nis' 
               AND b.info2 = '$idTahunBuku'
             ORDER BY nama";
    $totalbesarwjb = 0;
    $totalbayarwjb = 0;
    $totaldiskonwjb = 0;
    $totalsisawjb = 0;

    $result = $db->QueryDb($sql);
    $nJtt = mysqli_num_rows($result);

    if ($nJtt > 0)
    {
        echo "<table class='tab' id='tablejtt' border='1' cellpadding='5' style='border-collapse:collapse' cellspacing='0'>";
        echo "<tr height='30' align='center' class='header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='200'>Iuran Wajib</td>";
        echo "<td width='150' align='center'>BP<br><span style='font-size: 9px; font-weight: normal'>Besar Pembayaran</span></td>";
        echo "<td width='150' align='center'>TP<br><span style='font-size: 9px; font-weight: normal'>Total Pembayaran</span></td>";
        echo "<td width='150' align='center'>TD<br><span style='font-size: 9px; font-weight: normal'>Total Diskon</span></td>";
        echo "<td width='150' align='center'>Sisa<br><span style='font-size: 9px; font-weight: normal'>Tunggakan</span></td>";
        echo "<td width='250' align='center'>PT<br><span style='font-size: 9px; font-weight: normal'>Pembayaran Terakhir</span></td>";
        echo "</tr>";
    }

    $cnt = 0;
    while ($row = mysqli_fetch_array($result))
    {
        $cnt += 1;

        $idbesarjtt = $row['id'];
        $namapenerimaan = $row['nama'];
        $idpenerimaan = $row['idpenerimaan'];
        $besar = $row['besar'];
        $lunas = $row['lunas'];
        $keterangan = $row['keterangan'];

        $sql = "SELECT SUM(jumlah), SUM(info1) 
                  FROM jbsfina.penerimaanjtt 
                 WHERE idbesarjtt = '$idbesarjtt'";
        $row2 = $db->FetchSingleRow($sql);
        $pembayaran = $row2[0] + $row2[1];
        $diskon = $row2[1];
        $sisa = $besar - $pembayaran;

        $totalbesarwjb += $besar;
        $totalbayarwjb += $pembayaran;
        $totaldiskonwjb += $diskon;
        $totalsisawjb += $sisa;

        $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal, p.info1, j.nokas
                  FROM jbsfina.penerimaanjtt p, jbsfina.jurnal j
                 WHERE p.idjurnal = j.replid
                   AND p.idbesarjtt = '$idbesarjtt'
                 ORDER BY p.tanggal DESC
                 LIMIT 1";

        $result2 = $db->QueryDb($sql);
        $byrakhir = 0;
        $dknakhir = 0;
        $tglakhir = "";
        $nojurnal = "";

        if (mysqli_num_rows($result2))
        {
            $row2 = mysqli_fetch_row($result2);
            $byrakhir = $row2[0];
            $tglakhir = $row2[1];
            $dknakhir = $row2[2];
            $nojurnal = $row2[3];
        };

        echo "<tr>";
        echo "<td align='center' class='numberColumn'>$cnt</td>";
        echo "<td align='left'><b>$namapenerimaan</b><br><a class='asmall' onclick='showRiwayatJtt($idpenerimaan, \"$namapenerimaan\")'>riwayat</a></td>";
        echo "<td align='right'><b>" . FormatRupiah($besar) . "</b></td>";
        echo "<td align='right'><b>" . FormatRupiah($pembayaran) . "</b></td>";
        echo "<td align='right'><b>" . FormatRupiah($diskon) . "</b></td>";
        echo "<td align='right'><b>" . FormatRupiah($sisa) . "</b></td>";
        echo "<td align='left'>";
        echo "<b>" . FormatRupiah($byrakhir) . "</b><br><i>diskon: " . FormatRupiah($dknakhir) . "</i><br><i>tanggal: " . $tglakhir . "</i><br><i>no jurnal: $nojurnal</i>";
        echo "</td>";
        echo "</tr>";
    }

    if ($nJtt > 0)
    {
        echo "<tr height='45'>";
        echo "<td align='right' colspan='2' class='footerColumn'><b>TOTAL</b></td>";
        echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalbesarwjb) . "</b></td>";
        echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalbayarwjb) . "</b></td>";
        echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totaldiskonwjb) . "</b></td>";
        echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalsisawjb) . "</b></td>";
        echo "<td align='left' class='footerColumn'>&nbsp;</td>";
        echo "</tr>";
        echo "</table>";
    }

    $totalbayarskr = 0;

    $sql = "SELECT DISTINCT p.idpenerimaan, d.nama 
              FROM jbsfina.penerimaaniuran p, jbsfina.datapenerimaan d, jbsfina.jurnal j
             WHERE p.idpenerimaan = d.replid 
               AND p.idjurnal = j.replid 
               AND j.idtahunbuku = '$idTahunBuku'
               AND p.nis = '$nis' 
             ORDER BY nama";
    $result = $db->QueryDb($sql);
    $nSkr = mysqli_num_rows($result);

    if ($nSkr > 0)
    {
        echo "<br><br>";
        echo "<table class='tab' id='tableskr' border='1' cellpadding='5' style='border-collapse:collapse' cellspacing='0'>";
        echo "<tr height='30' align='center' class='header'>";
        echo "<td width='30'>No</td>";
        echo "<td width='200'>Iuran Sukarela</td>";
        echo "<td width='150' align='center'>TP<br><span style='font-size: 9px; font-weight: normal'>Total Pembayaran</span></td>";
        echo "<td width='250' align='center'>PT<br><span style='font-size: 9px; font-weight: normal'>Pembayaran Terakhir</span></td>";
        echo "</tr>";
    }

    $cnt = 0;
    while ($row = mysqli_fetch_array($result))
    {
        $cnt += 1;
        $idpenerimaan = $row['idpenerimaan'];
        $namapenerimaan = $row['nama'];

        $sql = "SELECT SUM(p.jumlah) 
                  FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j 
                 WHERE p.idjurnal = j.replid
                   AND j.idtahunbuku = $idTahunBuku
                   AND p.idpenerimaan = '$idpenerimaan' 
                   AND p.nis = '$nis'";
        $pembayaran = $db->FetchSingle($sql, 0);
        $totalbayarskr += $pembayaran;

        $sql = "SELECT p.jumlah, DATE_FORMAT(p.tanggal, '%d-%b-%Y') AS ftanggal
                  FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j 
                 WHERE p.idjurnal = j.replid
                   AND j.idtahunbuku = $idTahunBuku
                   AND p.idpenerimaan = '$idpenerimaan'
                   AND p.nis='$nis'
                 ORDER BY p.replid DESC
                 LIMIT 1";
        $result2 = $db->QueryDb($sql);
        $byrakhir = 0;
        $tglakhir = "";
        if (mysqli_num_rows($result2))
        {
            $row2 = mysqli_fetch_row($result2);
            $byrakhir = $row2[0];
            $tglakhir = $row2[1];
        };

        echo "<tr>";
        echo "<td align='center' class='numberColumn'>$cnt</td>";
        echo "<td align='left'><b>$namapenerimaan</b><br><a class='asmall' onclick='showRiwayatSkr($idpenerimaan, \"$namapenerimaan\")'>riwayat</a></td>";
        echo "<td align='right'><b>" . FormatRupiah($pembayaran) . "</b></td>";
        echo "<td align='left'>";
        echo "<b>" . FormatRupiah($byrakhir) . "</b><br><i>tanggal: " . $tglakhir . "</i>";
        echo "</td>";
        echo "</tr>";
    }

    if ($nSkr > 0)
    {
        echo "<tr style='height: 45px'>";
        echo "<td align='right' colspan='2' class='footerColumn'><b>TOTAL</b></td>";
        echo "<td align='right' class='footerColumn'><b>" . FormatRupiah($totalbayarskr) . "</b></td>";
        echo "<td align='left' class='footerColumn'>&nbsp;</td>";
        echo "</tr>";
        echo "</table>";
    }

    $sql = "SELECT DISTINCT t.idtabungan, dt.nama
              FROM jbsfina.tabungan t, jbsfina.datatabungan dt
             WHERE t.idtabungan = dt.replid
               AND t.nis = '$nis'";

    $lsTab = array();

    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $lsTab[] = array($row[0], $row[1]);
    }

    if (count($lsTab) > 0)
    {
        echo "<br><br>";
        echo "<table class='tab' id='tablejtt' border='1' cellpadding='5' style='border-collapse:collapse' cellspacing='0'>";
        echo "<tr style='height: 30px' align='center'>";
        echo "<td class='bg-table-header' width='30' rowspan='2'>No</td>";
        echo "<td class='bg-table-header' width='270' rowspan='2'>Tabungan</td>";
        echo "<td class='bg-table-header' width='150' rowspan='2' align='center'>JS<br><span style='font-size: 9px; font-weight: normal'>Jumlah Setoran</span></td>";
        echo "<td class='bg-table-header' width='150' rowspan='2' align='center'>SA<br><span style='font-size: 9px; font-weight: normal'>Setoran Akhir</span></td>";
        echo "<td class='bg-table-header' width='150' rowspan='2' align='center'>JT<br><span style='font-size: 9px; font-weight: normal'>Jumlah Tarikan</span></td>";
        echo "<td class='bg-table-header' width='150' rowspan='2' align='center'>TA<br><span style='font-size: 9px; font-weight: normal'>Tarikan Akhir</span></td>";
        echo "<td class='bg-table-header' width='450' colspan='3' align='center'>Rekapitulasi</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td class='bg-table-header' width='150' align='center'>TS<br><span style='font-size: 9px; font-weight: normal'>Total Setoran</span></td>";
        echo "<td class='bg-table-header' width='150' align='center'>TT<br><span style='font-size: 9px; font-weight: normal'>Total Tarikan</span></td>";
        echo "<td class='bg-table-header' width='150' align='center'>TSD<br><span style='font-size: 9px; font-weight: normal'>Saldo</span></td>";
        echo "</tr>";
    }

    $cnt = 0;
    for($i = 0; $i < count($lsTab); $i++)
    {
        $idTab = $lsTab[$i][0];
        $nmTab = $lsTab[$i][1];

        $totsetor = 0;
        $tottarik = 0;
        $saldo = 0;
        $sql = "SELECT SUM(debet), SUM(kredit)
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $tottarik = $row[0];
            $totsetor = $row[1];
            $saldo = $totsetor - $tottarik;
        }

        $subsetor = 0;
        $subtarik = 0;
        $sql = "SELECT SUM(debet), SUM(kredit)
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $subtarik = $row[0];
            $subsetor = $row[1];
        }

        $lastsetor = 0;
        $tgllastsetor = "";
        $sql = "SELECT kredit, DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s')
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'
                   AND kredit <> 0
                 ORDER BY replid DESC
                 LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $lastsetor = $row[0];
            $tgllastsetor = $row[1];
        }

        $lasttarik = 0;
        $tgllasttarik = "";
        $sql = "SELECT debet, DATE_FORMAT(tanggal, '%d-%b-%Y %H:%i:%s')
                  FROM jbsfina.tabungan
                 WHERE idtabungan = '$idTab'
                   AND nis = '$nis'
                   AND debet <> 0
                 ORDER BY replid DESC
                 LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $lasttarik = $row[0];
            $tgllasttarik = $row[1];
        }

        $cnt += 1;
        echo "<tr>";
        echo "<td align='center' class='numberColumn'> $cnt </td>";
        echo "<td align='left'><b>$nmTab</b><br><a class='asmall hide-in-report' onclick='showRiwayatTabungan($idTab, \"$nmTab\")'>riwayat</a></td>";
        echo "<td align='right' style='background-color:#E0F3FF'><b>" . FormatRupiah($subsetor) . "</b></td>";
        echo "<td align='right' style='background-color:#E0F3FF'><b>" . FormatRupiah($lastsetor) . "</b><br><i>$tgllastsetor</i></td>";
        echo "<td align='right' style='background-color:#F2E9C6'><b>" . FormatRupiah($subtarik) . "</b></td>";
        echo "<td align='right' style='background-color:#F2E9C6'><b>" . FormatRupiah($lasttarik) . "</b><br><i>$tgllasttarik</i></td>";
        echo "<td align='right' style='background-color:#DBF4C1'><b>" . FormatRupiah($totsetor) . "</b></td>";
        echo "<td align='right' style='background-color:#DBF4C1'><b>" . FormatRupiah($tottarik) . "</b></td>";
        echo "<td align='right' style='background-color:#DBF4C1'><b>" . FormatRupiah($saldo) . "</b></td>";
        echo "</tr>";
    }

    if (count($lsTab) > 0)
    {
        echo "</table>";
    }
}

function ShowRiwayatPembayaranJtt($db, $payInfo)
{
    if ($payInfo->IdBesarJtt == 0 || $payInfo->Lunas == 2)
    {
        echo "&nbsp;";
        return;
    }

    $sql = "SELECT count(*) 
              FROM jbsfina.penerimaanjtt 
             WHERE idbesarjtt = '$payInfo->IdBesarJtt'";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $nbayar = $row[0];

    if ($nbayar == 0)
    {
        echo "<br><i>Belum ada data pembayaran iuran wajib</i>";
    }
    else
    {
        $sql = "SELECT COUNT(p.replid), SUM(p.jumlah + p.info1) AS totjumlah, SUM(p.info1) AS todiskon
                  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b
                 WHERE p.idbesarjtt = b.replid
                   AND b.replid = '$payInfo->IdBesarJtt'";
        $result = $db->QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $nData = (int) $row[0];
        $totJumlah = (int) $row[1];
        $totDiskon = (int) $row[2];

        echo "<div style='text-align: center; font-weight: bold; font-size: 15px;'>$payInfo->Penerimaan</div><br>";
        echo "<table border='0' cellpadding='0' cellspacing='0' width='95%'>";
        echo "<tr>";
        echo "<td width='100%' align='left' valign='top'>";

        echo "<table border='0' cellspacing='0' cellpadding='5'>";
        echo "<tr>";
        echo "<td style='width: 200px'>";
        echo "<span style='color: #666'>Total Pembayaran</span><br>";
        echo "<span style='font-size: 14px; font-weight: bold' id='spTotalPembayaran'>" . FormatRupiah($totJumlah). "</span>";
        echo "</td>";
        echo "<td style='width: 200px'>";
        echo "<span style='color: #666'>Total Diskon</span><br>";
        echo "<span style='font-size: 14px; font-weight: bold' id='spTotalDiskon'>" . FormatRupiah($totDiskon). "</span>";
        echo "</td>";
        echo "<td style='width: 250px'>";
        echo "<span style='color: #666'>Sisa Pembayaran</span><br>";
        echo "<span style='font-size: 14px; font-weight: bold' id='spSisaPembayaran'></span>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        echo "</td>";
        echo "</tr>";
        echo "</table>";


        $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal,
                       p.keterangan, p.jumlah, p.petugas, p.info1 AS diskon, jd.koderek AS rekkas, ra.nama AS namakas,
                       IFNULL(p.sumberdana, 'Tidak ada data') AS fsumberdana, p.jam
                  FROM jbsfina.penerimaanjtt p, jbsfina.besarjtt b, jbsfina.jurnal j, 
                       jbsfina.jurnaldetail jd, jbsfina.rekakun ra
                 WHERE p.idbesarjtt = b.replid
                   AND j.replid = p.idjurnal
                   AND j.replid = jd.idjurnal
                   AND jd.koderek = ra.kode
                   AND ra.kategori = 'HARTA'
                   AND b.replid = '$payInfo->IdBesarJtt'
                 ORDER BY p.replid DESC";
        $result = $db->QueryDb($sql);

        echo "<div style='width: 100%; overflow: auto; max-height: 400px;'>";

        echo "<table class='tab' id='tableRiwayat' border='1' style='border-collapse:collapse'>";
        echo "<tr height='30' align='center'>";
        echo "<td class='header' width='30'>No</td>";
        echo "<td class='header' width='120'>No. Jurnal/Tgl</td>";
        echo "<td class='header' width='150'>Jumlah</td>";
        echo "<td class='header' width='150'>Diskon</td>";
        echo "<td class='header' width='350'>Informasi</td>";
        echo "</tr>";

        $cnt = $nData + 1;
        while ($row = mysqli_fetch_array($result))
        {
            $cnt -= 1;

            // 2020-10-05 Check SchoolPay Transaction
            $id = $row['id'];
            $sql = "SELECT jenistrans
                      FROM jbsfina.paymenttrans
                     WHERE idpenerimaanjtt = $id";
            $res2 = $db->QueryDb($sql);
            $isSchoolPay = mysqli_num_rows($res2) > 0;
            $infoSchoolPay = "";
            if ($row2 = mysqli_fetch_row($res2))
            {
                $jenisTrans = $row2[0];
                if ($jenisTrans == 0)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #636363; color: #ffffff'>&nbsp;Vendor&nbsp;</span>";
                else if ($jenisTrans == 1)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #47973c; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";
                else if ($jenisTrans == 2)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #9f4aa3; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";

                $infoSchoolPay = $isSchoolPay ? "<span style='background-color: #296eeb; color: #ffffff; font-size: 10px;'>$jenisTrans</span>" : "";
            }

            // 2023-08-09
            if ($infoSchoolPay == "")
            {
                $sql = "SELECT COUNT(replid)
                          FROM jbsfina.pgtransdata2
                         WHERE idpenerimaanjtt = $id";
                $res2 = $db->QueryDb($sql);
                $row2 = mysqli_fetch_row($res2);
                if ($row2[0] > 0)
                {
                    $infoSchoolPay = "<span style='background-color: #43b9c9; color: #ffffff; font-size: 10px;'>&nbsp;OnlinePay&nbsp;</span>";
                }
            }

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td align='center'><strong>$row[nokas]</strong><br><i>$row[tanggal] $row[jam]</i></td>";
            echo "<td align='right' class='bg-light-blue'><b>" . FormatRupiah($row['jumlah'] + $row['diskon']) . "<br> $infoSchoolPay </b></td>";
            echo "<td align='right' class='bg-light-green'><b>" . FormatRupiah($row['diskon']) . "</b></td>";
            echo "<td align='left'>";
            echo "<b>Rek Kas</b>: $row[rekkas] $row[namakas]<br>";
            echo "<b>Sumber Dana</b>: $row[fsumberdana]<br>";
            echo "<b>Petugas</b>: $row[petugas]<br>";
            echo "<i>$row[keterangan]</i>";
            echo "</td>";
            echo "</tr>";
        }
        $sisa = $payInfo->Besar - $totJumlah;
        echo "<input type='hidden' id='sisapembayaran' value='" . FormatRupiah($sisa) . "'>";

        echo "</table>";
        echo "</div>";

    }
}

function ShowRiwayatPembayaranSkr($db, $nis, $idPenerimaan, $namaPenerimaan, $idTahunBuku)
{
    try
    {
        $sql = "SELECT COUNT(p.replid), SUM(p.jumlah)
                  FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j 
                 WHERE j.replid = p.idjurnal
                   AND j.idtahunbuku = '$idTahunBuku'
                   AND p.idpenerimaan = '$idPenerimaan'
                   AND p.nis = '$nis'";
        $result = $db->QueryDb($sql);
        $row = mysqli_fetch_row($result);
        $nData = (int)$row[0];
        $totJumlah = (int)$row[1];

        if ($nData == 0)
        {
            echo "<br><i>Belum ada data pembayaran iuran sukarela</i>";
        }
        else
        {
            echo "<div style='text-align: center; font-weight: bold; font-size: 15px;'>$namaPenerimaan</div><br>";

            echo "<table border='0' cellpadding='0' cellspacing='0' width='780'>";
            echo "<tr>";
            echo "<td width='400' align='left' valign='top'>";

            echo "<table border='0' cellspacing='0' cellpadding='5'>";
            echo "<tr>";
            echo "<td style='width: 200px'>";
            echo "<span style='color: #666'>Total Pembayaran</span><br>";
            echo "<span style='font-size: 14px; font-weight: bold'>" . FormatRupiah($totJumlah) . "</span>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";

            echo "</td>";
            echo "<td width='380' align='right' valign='bottom'>";

            echo "</td>";
            echo "</tr>";
            echo "</table>";


            $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y') as tanggal, p.keterangan, p.jumlah, p.petugas,
                           jd.koderek AS rekkas, ra.nama AS namakas, p.jam, IFNULL(p.sumberdana, 'Tidak ada data') AS fsumberdana
                      FROM jbsfina.penerimaaniuran p, jbsfina.jurnal j, jbsfina.jurnaldetail jd, jbsfina.rekakun ra 
                     WHERE j.replid = p.idjurnal
                       AND j.replid = jd.idjurnal
                       AND jd.koderek = ra.kode
                       AND j.idtahunbuku = '$idTahunBuku'
                       AND p.idpenerimaan = '$idPenerimaan'
                       AND p.nis = '$nis'
                       AND ra.kategori = 'HARTA'
                    ORDER BY p.replid DESC";
            $result = $db->QueryDb($sql);

            echo "<div style='width: 100%; overflow: auto; max-height: 400px;'>";

            echo "<table class='tab' id='tableRiwayat' border='1' style='border-collapse:collapse' align='center'>";
            echo "<tr height='30' align='center'>";
            echo "<td class='header' width='30'>No</td>";
            echo "<td class='header' width='120'>No. Jurnal/Tgl</td>";
            echo "<td class='header' width='150'>Jumlah</td>";
            echo "<td class='header' width='350'>Informasi</td>";
            echo "</tr>";

            $cnt = $nData + 1;
            while ($row = mysqli_fetch_array($result))
            {
                $cnt -= 1;

                echo "<tr height='25'>";
                echo "<td align='center' class='numberColumn'>$cnt</td>";
                echo "<td align='center'><strong>$row[nokas]</strong><br><i>$row[tanggal] $row[jam]</i></td>";
                echo "<td align='right' class='bg-light-blue'><b>" . FormatRupiah($row['jumlah']) . "</b></td>";
                echo "<td align='left'>";
                echo "<b>Rek Kas</b>: $row[rekkas] $row[namakas]<br>";
                echo "<b>Sumber Dana</b>: $row[fsumberdana]<br>";
                echo "<b>Petugas</b>: $row[petugas]<br>";
                echo "<i>$row[keterangan]</i>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
            echo "</div>";

            echo "<br>";

        }
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kha1f");
    }
}

function ShowRiwayatJtt()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idPenerimaan = RequestData("idpenerimaan", 0);
        $userId = RequestData("userid", "");
        $idTahunBuku = RequestData("idtahunbuku", 0);
        $namaPenerimaan = RequestData("namapenerimaan", "");

        $payInfo = GetPaymentInfoJtt($db, $idPenerimaan, $idTahunBuku, $userId);
        if ($payInfo->Exist == false)
        {
            echo "<i>Tidak ditemukan data penerimaan /krw70</i>";
            exit();
        }

        ShowRiwayatPembayaranJtt($db, $payInfo);

    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowRiwayatSkr()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idPenerimaan = RequestData("idpenerimaan", 0);
        $userId = RequestData("userid", "");
        $idTahunBuku = RequestData("idtahunbuku", 0);
        $namaPenerimaan = RequestData("namapenerimaan", "");

        ShowRiwayatPembayaranSkr($db, $userId, $idPenerimaan, $namaPenerimaan, $idTahunBuku);
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowRiwayatTabungan()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idtabungan = RequestData("idtabungan", 0);
        $namatabungan = RequestData("namatabungan", "");
        $idtahunbuku = RequestData("idtahunbuku", 0);
        $departemen = RequestData("departemen", "");
        $nis = RequestData("nis", "");
        $nama = RequestData("nama", "");

        echo "<div style='text-align: center; font-weight: bold; font-size: 15px;'>$namatabungan</div><br>";
        
        echo "<table class='tab' id='tabTabunganList' border='1' style='border-collapse:collapse' width='100%'>";
        echo "<tr style='height: 30px' align='center'>";
        echo "<td class='header' width='5%'>No</td>";
        echo "<td class='header' width='18%'>No. Jurnal/Tgl</td>";
        echo "<td class='header' width='15%'>Debet</td>";
        echo "<td class='header' width='15%'>Kredit</td>";
        echo "<td class='header' width='*'>Keterangan</td>";
        echo "<td class='header' width='12%'>Petugas</td>";
        echo "</tr>";

        $sql = "SELECT COUNT(p.replid)
                  FROM jbsfina.tabungan p, jbsfina.jurnal j
                 WHERE p.idjurnal = j.replid
                   AND p.nis = '$nis'
                   AND j.idtahunbuku = '$idtahunbuku'
                   AND p.idtabungan = '$idtabungan'";
        $nData = $db->FetchSingle($sql, 0);
        if ($nData == 0)
        {
            echo "<tr height='100'><td colspan='7' align='center' valign='middle'><i>Belum ada data tabungan</i></td></tr>";
            echo "</table>";
            echo "<input type='hidden' id='totalpage' value='0'>";
            echo "<input type='hidden' id='ndata' value='0'>";

            return;
        }

        $sql = "SELECT p.replid AS id, j.nokas, date_format(p.tanggal, '%d-%b-%Y %H:%i:%s') as tanggal,
                       p.keterangan, p.debet, p.kredit, p.petugas, p.idtabungan AS iddatatabungan,
                       IFNULL(p.sumberdana, '-') AS fsumberdana,
                       IFNULL(p.lokasidana, '-') AS flokasidana
                  FROM jbsfina.tabungan p, jbsfina.jurnal j
                 WHERE p.idjurnal = j.replid
                   AND p.nis = '$nis'
                   AND j.idtahunbuku = '$idtahunbuku'
                   AND p.idtabungan = '$idtabungan'
                ORDER BY p.replid DESC
                   LIMIT 10";
        $result = $db->QueryDb($sql);
        $cnt = 0;
        while ($row = mysqli_fetch_array($result))
        {
            $cnt += 1;

            $kredit = (int)$row['kredit'];
            $bgcolor = $kredit != 0 ? "#E0F3FF" : "#F9F6EA";

            $idTabunganRow = $row['id'];
            $idDataTabunganRow = $row['iddatatabungan'];

            $sql = "SELECT jenistrans
                      FROM jbsfina.paymenttrans
                     WHERE idtabungan = $idTabunganRow";
            $res2 = $db->QueryDb($sql);

            $isSchoolPay = mysqli_num_rows($res2) > 0;
            $infoSchoolPay = "";
            if ($row2 = mysqli_fetch_row($res2))
            {
                $jenisTrans = $row2[0];
                if ($jenisTrans == 0)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #636363; color: #ffffff'>&nbsp;Vendor&nbsp;</span>";
                else if ($jenisTrans == 1)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #47973c; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";
                else if ($jenisTrans == 2)
                    $jenisTrans = "&nbsp;SchoolPay&nbsp;<span style='background-color: #9f4aa3; color: #ffffff'>&nbsp;Iuran&nbsp;</span>";

                $infoSchoolPay = $isSchoolPay ? "<span style='background-color: #296eeb; color: #ffffff; font-size: 10px;'>$jenisTrans</span>" : "";
            }

            if ($infoSchoolPay == "")
            {
                $sql = "SELECT COUNT(replid)
                          FROM jbsfina.pgtransdata2
                         WHERE idtabungan = $idDataTabunganRow";
                $res2 = $db->QueryDb($sql);
                $row2 = mysqli_fetch_row($res2);
                if ($row2[0] > 0)
                {
                    $infoSchoolPay = "<span style='background-color: #43b9c9; color: #ffffff; font-size: 10px;'>&nbsp;OnlinePay&nbsp;</span>";
                }
            }

            $action = "setor";
            if ($row["debet"] > 0 && $row["kredit"] == 0)
                $action = "tarik";

            $keterangan = "";
            if ($row["fsumberdana"] != "-")
                $keterangan .= "<b>Sumber Dana</b>: $row[fsumberdana]<br>";
            if ($row["flokasidana"] != "-")
            {
                if ($action == "setor")
                    $keterangan .= "<b>Penyimpanan</b>: $row[flokasidana]<br>";
                else
                    $keterangan .= "<b>Pengambilan</b>: $row[flokasidana]<br>";
            }

            $keterangan .= "<i>$row[keterangan]</i>";

            echo "<tr height='25' style='background-color: $bgcolor;'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td align='center'><strong>$row[nokas]</strong><br><i>$row[tanggal]</i></td>";
            echo "<td align='right'>" . FormatRupiah($row['debet']) . "</td>";
            echo "<td align='right'>" . FormatRupiah($row['kredit']) . "</td>";
            echo "<td align='left'>$keterangan</td>";
            echo "<td align='center'>$row[petugas]</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
?>