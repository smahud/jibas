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

function ShowSelectJenisMutasi($db)
{
    global $idJenisMutasi;

    $sql = "SELECT replid, jenismutasi
              FROM jbsakad.jenismutasi 
             ORDER BY jenismutasi";
    $res = $db->QueryDb($sql);
    echo "<select id='jenismutasi' onchange='onChangeJenisMutasi()' class='inputbox' style='width:250px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        if ($idJenisMutasi == 0)
            $idJenisMutasi = $row["replid"];

        $sel = $idJenisMutasi == $row['replid'] ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[jenismutasi]</option>";
    }
    echo "</select>";             
}

function ShowPageControl($db)
{
    global $departemen, $idJenisMutasi, $nRowPerPage;

    $sql = "SELECT COUNT(replid)
              FROM jbsakad.mutasisiswa
             WHERE departemen='$departemen' 
               AND jenismutasi='$idJenisMutasi'";
    $nData = $db->FetchSingle($sql, 0);            
    if ($nData == 0)
        return;

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
    global $departemen, $idJenisMutasi, $nRowPerPage, $page;

    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT mut.replid AS replidmutasi, s.replid AS replidsiswa, s.nis, s.nama, k.kelas, 
                   mut.tglmutasi, t.tingkat, mut.keterangan, mut.jenismutasi
              FROM jbsakad.mutasisiswa mut, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.siswa s 
             WHERE mut.departemen='$departemen' 
               AND mut.jenismutasi='$idJenisMutasi' 
               AND k.idtingkat=t.replid 
               AND k.replid=s.idkelas
               AND s.nis = mut.nis 
             ORDER BY mut.replid DESC
             LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);             
    if (mysqli_num_rows($res) == 0)
        return;
    
    echo "<table id='tableSiswaMutasi' class='tab' width='100%' align='center'>";
    echo "<tr align='center'>";
    echo "<td class='bg-table-header' width='7%'>No</td>";
    echo "<td class='bg-table-header' width='*''>Siswa</td>";
    echo "<td class='bg-table-header' width='40%'>Informasi</td>";
    echo "<td class='bg-table-header' width='7%'>&nbsp;</td>";
    echo "</tr>";

    $no = $startIndex;
    while ($row = mysqli_fetch_array($res)) 
    {
        $no += 1;

        $nis = $row["nis"];
        $nama = $row["nama"];
        $keterangan = $row["keterangan"];
        $tglMutasi = $row["tglmutasi"];
        $idMutasi = $row["replidmutasi"];
        $idJenisMutasi = $row["jenismutasi"];

        $data64 = base64_encode(json_encode([$idMutasi, $idJenisMutasi, $tglMutasi, $keterangan, $nis, $nama]));
        
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='left' style='position: relative;'>";
        echo "<b>$nama</b><br>";
        echo "<span class='fg-secondary'>$nis</span><br>";
        echo "<span class='fg-secondary'>$row[tingkat] - $row[kelas]</span>";
        echo "</td>";
        echo "<td valign='top' style='position: relative'>";
        echo "$row[keterangan]<br>";
        echo "tanggal: " . LongDateFormat($tglMutasi);
        echo "<div style='position: absolute; top: 10px; right: 5px;'>";
        echo "<img src='../../images/ico/ubah.png' alt='Ubah' title='Ubah' class='cur-hand' onclick='ubahMutasi(\"$data64\")'>";
        echo "</div>";
        echo "</td>";
        echo "<td align='center'>";
        echo "<img src='../../images/ico/hapus.png' alt='Batal' title='Batal' class='cur-hand' onclick='batalMutasi(\"$idMutasi\",\"$nis\", \"$nama\")'>";
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