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
require_once('../include/sessionchecker.php');
require_once('../library/common.func.php');
require_once('../include/rupiah.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../include/sessioninfo.php');
require_once('../library/departemen.php');
require_once('../include/errorhandler.php');
require_once('onlinepay.util.func.php');
require_once('riwayattrans.func.php');

function ShowRincianStatistikHarian()
{
    $db = new Db();
    try
    {
        $db->Open();

        DoShowRincianStatistikHarian($db);
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kp8v9");
    }
    finally
    {
        $db->Close();
    }
}

function DoShowRincianStatistikHarian($db)
{
    $page = 1;
    if (isset($_REQUEST["page"]))
        $page = $_REQUEST["page"];

    if (!isset($_REQUEST["stidpgtrans"]))
    {
        $tanggal = $_REQUEST["tanggal"];
        $departemen = $_REQUEST["departemen"];
        $bankNo = $_REQUEST["bankno"];
        $idPetugas = $_REQUEST["idpetugas"];
        $metode = $_REQUEST["metode"];

        $sql = "SELECT DISTINCT p.replid
                  FROM jbsfina.pgtrans2 p, jbsfina.pgtransdata2 pd
                 WHERE pd.idpgtrans = p.replid
                   AND p.tanggal = '$tanggal'";
        if ($departemen != "ALL") $sql .= " AND p.departemen = '$departemen'";
        if ($bankNo != "0") $sql .= " AND bankno = '$bankNo'";
        if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '$idPetugas'";
        if ($metode != "0") $sql .= " AND p.jenis = '$metode'";

        $nData = 0;
        $stIdPgTrans = "";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $nData++;

            if ($stIdPgTrans != "") $stIdPgTrans .= ",";
            $stIdPgTrans .= $row[0];
        }
    }
    else
    {
        $stIdPgTrans = $_REQUEST["stidpgtrans"];
        $nData = $_REQUEST["ndata"];
    }

    if ($nData == 0)
    {
        echo "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Tidak ditemukan data pembayaran online</span>";
        return;
    }

    $nRowPerPage = 10;
    $limitStart = ($page - 1) * $nRowPerPage;
    $nPage = ceil($nData / $nRowPerPage);
?>
    <table border="0" class="tab" cellpadding="10" cellspacing="0">
    <tr>
        <td align="left">

            <input type="hidden" id="report" value="RIWAYAT">
            <input type="hidden" id="stidpgtrans" value="<?=$stIdPgTrans?>">
            <input type="hidden" id="ndata" value="<?=$nData?>">
            Halaman
            <select id="page" class="inputbox" style="width: 50px;" onchange="changeReportPage()">
<?php       for($i = 1; $i <= $nPage; $i++)
            {
                $sel = $i == $page ? "selected" : "";
                echo "<option value='$i' $sel>$i</option>";
            }   ?>
            </select>
            dari <?= $nPage ?>

        </td>
    </tr>
    <tr>
        <td>
<?php
    $sql = "SELECT p.replid, p.nis, s.nama AS namasiswa, p.bankno, p.nomor, p.jenis,
                   DATE_FORMAT(p.waktu, '%d %b %Y<br>%H:%i') AS fwaktu, DATE_FORMAT(p.tanggal, '%d %b %Y') AS ftanggal,
                   p.idpetugas, p.petugas, p.ketver, p.transaksi, b.bank, b.bankname
              FROM jbsfina.pgtrans2 p
             INNER JOIN jbsfina.bank2 b ON p.departemen = b.departemen AND p.bankno = b.bankno 
              LEFT JOIN jbsakad.siswa s ON p.nis = s.nis
             WHERE p.replid IN ($stIdPgTrans)
             ORDER BY p.tanggal DESC, p.replid DESC
             LIMIT $limitStart, $nRowPerPage"; ?>

    <div id="dvTableContent">

    <table id="tabReport" class='tab' border="1" cellpadding="5" cellspacing="0">
    <tr style="height: 25px">
        <td class="header" width="35" align="center">No</td>
        <td class="header" width="160" align="center">Siswa</td>
        <td class="header" width="180" align="center">Jumlah</td>
        <td class="header" width="290" align="center">Bank/Petugas</td>
        <td class="header" id="thrincian" width="480" align="center">Rincian</td>
    </tr>
<?php
    $res = $db->QueryDb($sql);
    $cnt = ($page - 1) * $nRowPerPage;
    while($row = mysqli_fetch_array($res))
    {
        $cnt += 1;
        $idPgTrans = $row['replid'];

        $sql = "SELECT SUM(jumlah)
                  FROM jbsfina.pgtransdata2
                 WHERE idpgtrans = $idPgTrans";
        $res2 = $db->QueryDb($sql);
        $row2 = mysqli_fetch_row($res2);
        $jTransaksi = $row2[0];
        $rpTransaksi = FormatRupiah($jTransaksi);

        $jenis = $row["jenis"];
        $namaMetode = NamaMetode($jenis);

        echo "<tr>";
        echo "<td align='center' valign='top' style='background-color: #efefef;' rowspan='2'>$cnt</td>";
        echo "<td align='left' valign='top' colspan='3'>";

        echo "<table border='0' cellpadding='2' cellspacing='0' style='border: none; border-collapse: collapse;' width='100%'>";
        echo "<tr>";
        echo "<td style='border: none;' width='80%' valign='top'>";
        echo "$namaMetode<br><strong>$row[transaksi]</strong><br>$row[nomor]";
        echo "</td>";
        echo "<td style='border: none;'width='20%' align='right' valign='top'>";
        echo "<i>$row[fwaktu]</i>";
        echo "</td>";
        echo "</tr>";
        echo "</table>";

        echo "</td>";
        echo "<td align='left' class='rincian'  rowspan='2' valign='top' style='background-color: #efefef'>";

        echo "<table id='tabReportDetail' border='1' cellpadding='2' cellspacing='0' style='border: 1px #efefef; background-color: #fff; border-collapse: collapse'>";
        $sql = "SELECT pd.kategori, pd.jumlah, pd.diskon, pd.nokas, dp.nama AS namapenerimaan, dt.nama AS namatabungan
                  FROM jbsfina.pgtransdata2 pd
                  LEFT JOIN jbsfina.datapenerimaan dp ON pd.idpenerimaan = dp.replid
                  LEFT JOIN jbsfina.datatabungan dt ON pd.idtabungan = dt.replid
                 WHERE idpgtrans = $idPgTrans
                 ORDER BY kelompok;";
        $res2 = $db->QueryDb($sql);
        $stNoJurnal = "";
        while($row2 = mysqli_fetch_array($res2))
        {
            $kategori = $row2["kategori"];

            $nama = "";
            if ($kategori == "SISTAB")
                $nama = $row2["namatabungan"];
            else if ($kategori == "JTT")
                $nama = $row2["namapenerimaan"];
            else if ($kategori == "SKR")
                $nama = $row2["namapenerimaan"];
            else if ($kategori == "BL")
                $nama = "Biaya Layanan";

            $rp = FormatRupiah($row2["jumlah"]);

            if ($stNoJurnal != "") $stNoJurnal .= ",";
            $stNoJurnal .= $row2["nokas"];

            echo "<tr>";
            echo "<td width='220px' align='left'>$nama</td>";
            echo "<td width='110px' align='right'><span class='fst_currency fst_normal'>$rp</span></td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "</td>";
        echo "</tr>";

        echo "<tr>";
        echo "<td align='left' valign='top'>";
        echo "<strong>$row[namasiswa]</strong><br>$row[nis]";
        echo "</td>";
        echo "<td align='right' valign='top'><span class='fst_currency fst_bold fst_normal'>$rpTransaksi</strong></td>";
        echo "<td align='left' valign='top'><b>$row[bank]</b> - $row[bankname]<br><b>$row[petugas]</b> - $row[idpetugas]</td>";

        echo "</tr>";
    }
    ?>

    </table>
    </div>

    </td>
</tr>
</table>

<?php
}

function ShowRekapStatistikHarian()
{
    global $tanggal, $departemen, $bankNo, $idPetugas, $metode;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(DISTINCT nis)
                  FROM jbsfina.pgtrans2
                 WHERE tanggal = '$tanggal'";
        if ($departemen != "ALL") $sql .= " AND departemen = '$departemen'";
        if ($bankNo != "0") $sql .= " AND bankno = '$bankNo'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '$idPetugas'";
        if ($metode != "0") $sql .= " AND jenis = '$metode'";
        $res = $db->QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nSiswa = $row[0];

        $sql = "SELECT COUNT(replid)
                  FROM jbsfina.pgtrans2
                 WHERE tanggal = '$tanggal'";
        if ($departemen != "ALL") $sql .= " AND departemen = '$departemen'";
        if ($bankNo != "0") $sql .= " AND bankno = '$bankNo'";
        if ($idPetugas != "ALL") $sql .= " AND idpetugas = '$idPetugas'";
        if ($metode != "0") $sql .= " AND jenis = '$metode'";
        $res = $db->QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $nTransaksi = $row[0];

        $sql = "SELECT SUM(pd.jumlah)
                  FROM jbsfina.pgtrans2 p, jbsfina.pgtransdata2 pd
                 WHERE p.replid = pd.idpgtrans 
                   AND p.tanggal = '$tanggal'";
        if ($departemen != "ALL") $sql .= " AND p.departemen = '$departemen'";
        if ($bankNo != "0") $sql .= " AND bankno = '$bankNo'";
        if ($idPetugas != "ALL") $sql .= " AND p.idpetugas = '$idPetugas'";
        if ($metode != "0") $sql .= " AND p.jenis = '$metode'";
        $res = $db->QueryDbEx($sql);
        $row = mysqli_fetch_row($res);
        $sumTransaksi = $row[0];

        echo "<table border='0' cellpadding='2' cellspacing='0'>";
        echo "<tr>";
        echo "<td width='120'>Jumlah Siswa:</td>";
        echo "<td width='300'>$nSiswa</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Jumlah Transaksi:</td>";
        echo "<td>$nTransaksi</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td>Besar Transaksi:</td>";
        echo "<td>" . FormatRupiah($sumTransaksi) . "</td>";
        echo "</tr>";
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kasc0");
    }
    finally
    {
        $db->Close();
    }


}

function ShowRequestInfo()
{
    global $tanggal, $departemen, $bankName, $idPetugas, $metode;

    echo "<table border='0' cellpadding='2' cellspacing='0'>";
    echo "<tr>";
    echo "<td width='80'>Tanggal:</td>";
    echo "<td width='300'>$tanggal</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Departemen:</td>";
    echo "<td>";
    if ($departemen == 'ALL')
        echo 'Semua Departemen';
    else
        echo $departemen;
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Bank:</td>";
    echo "<td>";
    echo $bankName;
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Metode:</td>";
    echo "<td>";
    if ($metode == '0')
        echo 'Semua Metode';
    else if ($metode == 1)
        echo 'Pembayaran Tagihan';
    else if ($metode == 2)
        echo 'Pembayaran Keranjang';
    echo "</td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td>Petugas:</td>";
    echo "<td>";
    if ($idPetugas == 'ALL')
        echo 'Semua Petugas';
    else
        echo $idPetugas;
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}
?>

