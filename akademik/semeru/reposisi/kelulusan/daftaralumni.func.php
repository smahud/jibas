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

function ShowSelectTahunKelulusan($db)
{
    global $departemen, $tahunKelulusan;
    
    $sql = "SELECT DISTINCT YEAR(tgllulus) AS tahun 
              FROM jbsakad.alumni 
             WHERE departemen = '$departemen' 
             ORDER BY tahun DESC";
    
    $res = $db->QueryDb($sql);
    echo "<select name='tahunkelulusan' id='tahunkelulusan' style='width:200px' class='inputbox' onchange='onChangeTahunKelulusan();'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        if ($tahunKelulusan == 0)
            $tahunKelulusan = $row['tahun'];
        
        $sel = ($tahunKelulusan == $row['tahun']) ? "selected" : "";
        echo "<option value='$row[tahun]' $sel>$row[tahun]</option>";
    }
    echo "</select>";
}

function ColumnColor($urut, $urutBy)
{
    return ($urut == $urutBy) ? "#fffc00" : "#25f2ff";
}

function ShowPageControl($db)
{
    global $departemen, $tahunKelulusan, $nRowPerPage;

    $sql = "SELECT COUNT(s.replid)
              FROM jbsakad.alumni al, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.siswa s 
             WHERE al.departemen='$departemen' 
               AND k.idtingkat=t.replid 
               AND t.replid=al.tktakhir 
               AND k.replid=al.klsakhir 
               AND YEAR(al.tgllulus) = '$tahunKelulusan' 
               AND s.nis = al.nis 
               AND s.alumni = 1";
    $nData = $db->FetchSingle($sql, 0);            
    if ($nData == 0)
    {
        HintInfo::ShowCenter("Belum ada data alumni");
        return;
    }

    $nPage = ceil($nData / $nRowPerPage);
    
    echo "Halaman";
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

function ShowDaftarSiswaAlumni($db)
{
    global $departemen, $tahunKelulusan, $nRowPerPage, $page, $urut;

    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT s.replid AS replidsiswa, s.nis, s.nama, k.kelas, al.tgllulus, al.klsakhir, 
                   al.tktakhir, al.replid, t.tingkat 
              FROM jbsakad.alumni al, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.siswa s 
             WHERE al.departemen='$departemen' 
               AND k.idtingkat=t.replid 
               AND t.replid=al.tktakhir 
               AND k.replid=al.klsakhir 
               AND YEAR(al.tgllulus) = '$tahunKelulusan' 
               AND s.nis = al.nis 
               AND s.alumni = 1 
             ORDER BY $urut
             LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);             
    if (mysqli_num_rows($res) == 0)
        return;
    
    echo "<table id='tableAlumni' class='tab tabShadow' width='95%' align='center'>";
    echo "<tr align='center'>";
    echo "<td class='header' width='5%'>No</td>";
    $fgColor = ColumnColor("s.nis", $urut);
    echo "<td class='header' width='20%'><span class='cur-hand' onclick=\"onChangeUrut('s.nis')\" style='color: $fgColor'>NIS</span></td>";
    $fgColor = ColumnColor("s.nama", $urut);
    echo "<td class='header' width='*'><span class='cur-hand' onclick=\"onChangeUrut('s.nama')\" style='color: $fgColor'>Nama</span></td>";
    echo "<td class='header' width='20%'>Kelas Terakhir</td>";
    echo "<td class='header' width='20%'>Tanggal Lulus</td>";
    echo "<td class='header hide-in-report' width='7%'>&nbsp;</td>";
    echo "</tr>";

    $no = $startIndex;
    while ($row = mysqli_fetch_array($res)) 
    {
        $no += 1;

        $nis = $row["nis"];
        $nama = $row["nama"];
        $idAlumni = $row["replid"];
        
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left'>$nis</td>";
        echo "<td align='left'>$nama</td>";
        echo "<td align='left'>";
        echo "$row[tingkat] - $row[kelas]";
        echo "</td>";
        echo "<td align='center'>";
        echo LongDateFormat($row['tgllulus']);
        echo "</td>";
        echo "<td align='center' class='hide-in-report'>";
        echo "<img src='../../images/ico/lihat.png' title='lihat' class='cur-hand hide-in-report' onclick='detailSiswa(\"$nis\")'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/hapus.png' alt='Batal' title='Batal' class='cur-hand hide-in-report' onclick='batalAlumni(\"$idAlumni\",\"$nis\", \"$nama\")'>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function BatalAlumni()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idAlumni = RequestData("idalumni", 0);

        $sql = "SELECT a.tktakhir, a.klsakhir, a.nis, k.idtahunajaran, a.departemen 
                  FROM jbsakad.alumni a, jbsakad.kelas k 
                 WHERE a.replid = '$idAlumni' 
                   AND a.klsakhir = k.replid 
                   AND k.idtingkat = a.tktakhir";
        $res = $db->QueryDb($sql);                     
        if ($row = mysqli_fetch_array($res))
        {
            $nis = $row["nis"];
            $idTingkat = $row['tktakhir'];
            $idKelas = $row['klsakhir'];
            $idTahunAjaran = $row['idtahunajaran'];
            $departemen = $row['departemen'];
        }
        else 
        {
            return json_encode([-1, "Tidak ditemukan data pendataan alumni"]);
        }

        $db->BeginTrans();

        $sql = "UPDATE jbsakad.riwayatkelassiswa 
                   SET aktif = 1 
                 WHERE nis = '$nis' 
                   AND idkelas = '$idKelas'";
        $res = $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.riwayatdeptsiswa 
                   SET aktif = 1 
                 WHERE nis = '$nis' 
                   AND departemen = '$departemen'";
        $res = $db->QueryDb($sql);

        $sql = "UPDATE jbsakad.siswa 
                   SET aktif = 1, alumni = 0 
                 WHERE nis = '$nis'";
        $res = $db->QueryDb($sql);                 

        $sql = "DELETE FROM jbsakad.alumni 
                 WHERE replid = '$idAlumni'";
        $res = $db->QueryDb($sql);                 
        
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