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

$nRowPerPage = 10;

function ShowSelectDepartemen($db)
{
    global $departemen;

    $deps = getDepartemen($db, SI_USER_ACCESS());

    echo "<select name='departemen' id='departemen' style='width:200px' class='inputbox' onchange='onChangeDepartemen();'>";
    foreach ($deps as $value)
    {
        if ($departemen == "")
            $departemen = $value;
        $sel = ($departemen == $value) ? "selected" : "";
        echo "<option value='$value' $sel>$value</option>";
    }
    echo "</select>";
}

function ShowSelectTahunMutasi($db)
{
    global $departemen, $tahunMutasi;
    
    $sql = "SELECT DISTINCT YEAR(tglmutasi) AS tahun 
              FROM jbsakad.mutasisiswa 
             WHERE departemen = '$departemen' 
             ORDER BY tahun DESC";
    
    $res = $db->QueryDb($sql);
    echo "<select name='tahunmutasi' id='tahunmutasi' style='width:200px' class='inputbox' onchange='onChangeTahunMutasi();'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($tahunMutasi == 0)
            $tahunMutasi = $row['tahun'];
        
        $sel = ($tahunMutasi == $row['tahun']) ? "selected" : "";
        echo "<option value='$row[tahun]' $sel>$row[tahun]</option>";
    }
    echo "</select>";
}

function ShowSelectJenisMutasi($db)
{
    global $departemen, $idJenisMutasi;
    
    $sql = "SELECT replid, jenismutasi 
              FROM jbsakad.jenismutasi
             ORDER BY jenismutasi";
    
    $res = $db->QueryDb($sql);
    echo "<select name='jenismutasi' id='jenismutasi' style='width:200px' class='inputbox' onchange='onChangeJenisMutasi();'>";
    echo "<option value='0' selected>(semua jenis mutasi)</option>";
    while ($row = mysqli_fetch_assoc($res))
    {
        echo "<option value='$row[replid]'>$row[jenismutasi]</option>";
    }
    echo "</select>";
}

function ColumnColor($urut, $urutBy)
{
    return ($urut == $urutBy) ? "#fffc00" : "#25f2ff";
}

function ShowPageControl($db)
{
    global $departemen, $tahunMutasi, $idJenisMutasi, $nRowPerPage;

    $sql = "SELECT COUNT(s.replid)
              FROM jbsakad.mutasisiswa m, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.siswa s 
             WHERE m.nis = s.nis
               AND m.departemen = '$departemen' 
               AND YEAR(tglmutasi) = '$tahunMutasi'
               AND s.idkelas = k.replid
               AND k.idtingkat = t.replid";
    if ($idJenisMutasi != 0)
        $sql .= " AND m.jenismutasi = '$idJenisMutasi'";
        
    $nData = $db->FetchSingle($sql, 0);            
    if ($nData == 0)
    {
        HintInfo::ShowCenter("Belum ada data mutasi siswa");
        return;
    }

    $nPage = ceil($nData / $nRowPerPage);
    echo "Halaman ";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='<' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px;' onchange='onChangePage()'>";
    for($i = 1; $i <= $nPage; $i++)
    {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='>' onclick='onNextPage()'>";
    echo " dari $nPage, jumlah $nData data";
    echo "<input type='hidden' id='ndata' value='$nData'>";
    echo "<input type='hidden' id='npage' value='$nPage'>";
}

function ShowDaftarSiswaMutasi($db)
{
    global $departemen, $tahunMutasi, $idJenisMutasi, $nRowPerPage, $page, $urut;

    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, m.tglmutasi, m.keterangan, 
                   m.replid, t.tingkat, jm.jenismutasi 
              FROM jbsakad.mutasisiswa m, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.siswa s, jbsakad.jenismutasi jm
             WHERE m.nis = s.nis
               AND m.departemen = '$departemen' 
               AND YEAR(tglmutasi) = '$tahunMutasi'
               AND s.idkelas = k.replid
               AND k.idtingkat = t.replid
               AND m.jenismutasi = jm.replid";
    if ($idJenisMutasi != 0)
        $sql .= " AND m.jenismutasi = '$idJenisMutasi'";
    $sql .= " ORDER BY $urut
              LIMIT $startIndex, $nRowPerPage";
             
    $res = $db->QueryDb($sql);             
    if (mysqli_num_rows($res) == 0)
        return;
    
    echo "<table id='tableMutasi' class='tab tabShadow' width='95%' align='center'>";
    echo "<tr align='center'>";
    echo "<td class='header' width='5%'>No</td>";
    $fgColor = ColumnColor("s.nis", $urut);
    echo "<td class='header' width='12%'><span class='cur-hand' onclick=\"onChangeUrut('s.nis')\" style='color: $fgColor'>NIS</span></td>";
    $fgColor = ColumnColor("s.nama", $urut);
    echo "<td class='header' width='*'><span class='cur-hand' onclick=\"onChangeUrut('s.nama')\" style='color: $fgColor'>Nama</span></td>";
    echo "<td class='header' width='12%'>Kelas Terakhir</td>";
    echo "<td class='header' width='40%'>Mutasi</td>";
    echo "<td class='header hide-in-report' width='7%'>&nbsp;</td>";
    echo "</tr>";

    $no = $startIndex;
    while ($row = mysqli_fetch_array($res)) 
    {
        $no += 1;

        $nis = $row["nis"];
        $nama = $row["nama"];
        $idMutasi = $row["replid"];
        
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left' valign='top'>$nis</td>";
        echo "<td align='left' valign='top'>$nama</td>";
        echo "<td align='left' valign='top'>";
        echo "$row[tingkat] - $row[kelas]";
        echo "</td>";
        echo "<td align='left' valign='top'>";
        echo "<b>$row[jenismutasi]</b><br>";
        echo "Tanggal: " . LongDateFormat($row['tglmutasi']) . "<br>";
        echo "Keterangan: $row[keterangan]";
        echo "</td>";
        echo "<td align='center' valign='top' class='hide-in-report'>";
        echo "<img src='../../images/ico/lihat.png' title='lihat' class='cur-hand hide-in-report' onclick='detailSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/hapus.png' alt='Batal' title='Batal' class='cur-hand hide-in-report' onclick='batalMutasi(\"$idMutasi\",\"$nis\", \"$nama\")'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function BatalMutasi()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idMutasi = RequestData("idmutasi", 0);

        $sql = "SELECT m.nis, s.idkelas, m.tglmutasi, k.idtahunajaran, k.idtingkat, m.departemen 
                  FROM jbsakad.mutasisiswa m, jbsakad.siswa s, jbsakad.kelas k 
                 WHERE m.replid = '$idMutasi' 
                   AND s.nis = m.nis 
                   AND s.idkelas = k.replid";
        $res = $db->QueryDb($sql);                     
        if ($row = mysqli_fetch_array($res))
        {
            $nis = $row["nis"];
            $idTingkat = $row['idtingkat'];
            $idKelas = $row['idkelas'];
            $idTahunAjaran = $row['idtahunajaran'];
            $departemen = $row['departemen'];
        }
        else 
        {
            return json_encode([-1, "Tidak ditemukan data mutasi siswa"]);
        }

        $db->BeginTrans();

        $sql = "UPDATE jbsakad.riwayatkelassiswa 
                   SET aktif = 1 
                 WHERE nis = '$nis' 
                   AND idkelas = '$idKelas' 
                ORDER BY mulai DESC 
                LIMIT 1";
        $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                   SET aktif = 1 
                 WHERE nis = '$nis' 
                   AND departemen = '$departemen' 
                 ORDER BY mulai DESC 
                 LIMIT 1";
        $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.siswa 
                   SET aktif = 1, statusmutasi = NULL, alumni = 0 
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);
        
        $sql = "DELETE FROM jbsakad.alumni 
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.mutasisiswa 
                 WHERE replid = '$idMutasi'";
        $db->QueryDb($sql);

        $db->CommitTrans();
        
        return json_encode([1, "OK"]);
    }
    catch (Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    } 
}

?>