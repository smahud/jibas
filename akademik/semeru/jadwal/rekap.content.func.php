<?php
function ShowInfoJadwal($db)
{
    global $idKategori, $kategori, $tahunAjaran;

    $sql = "SELECT i.deskripsi, DATE_FORMAT(t.tglmulai, '%d %M %Y') AS ftglmulai, 
                   DATE_FORMAT(t.tglakhir, '%d %M %Y') AS ftglakhir 
              FROM jbsakad.infojadwal i, jbsakad.tahunajaran t  
             WHERE i.replid = $idKategori 
               AND t.replid = i.idtahunajaran";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        echo "<span style='display: inline-block; min-width: 100px; margin-bottom: 10px;'>Tahun Ajaran</span>" . $tahunAjaran . "<br>";
        echo "<span style='display: inline-block; min-width: 100px; margin-bottom: 10px'>Periode</span>" . $row["ftglmulai"] . " - " . $row["ftglakhir"] . "<br>";
        echo "<span style='display: inline-block; min-width: 100px; margin-bottom: 10px'>Ketegori</span>" . $kategori . "<br>";
        echo "<span class='fg-secondary fst-italic' style='margin-bottom: 10px'>" . $row["deskripsi"] . "</span>";
    }
}

function ShowTableRekap($db)
{
    global $idKategori;

    $sql = "SELECT p.nip, p.nama, 
                   SUM(IF(j.status = 0, 1, 0)), 
                   SUM(IF(j.status = 1, 1, 0)), 
                   SUM(IF(j.status = 2, 1, 0)), 
                   SUM(j.njam), COUNT(DISTINCT(j.idkelas)), COUNT(DISTINCT(j.hari)) 
              FROM jbsakad.jadwal j, jbssdm.pegawai p 
             WHERE j.nipguru = p.nip 
               AND j.infojadwal = '$idKategori' 
             GROUP BY j.nipguru 
             ORDER BY p.nama";	
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowCenter("<b>Belum ada data</b><br>Silahkan atur dahulu jadwal guru di menu <b>Penyusunan Jadwal per Kelas</b> atau <b>Penyusunan Jadwal per Guru</b>");
        return;
    }

    echo "<table class='tab tabShadow' id='table' width='100%' align='center'>";
    echo "<tr height='15'>";
    echo "<td width='4%' rowspan='2 'class='header bg-table-header' align='center'>No</td>";
    echo "<td width='*'rowspan='2' class='header bg-table-header' align='center'>Guru</td>";
    echo "<td colspan='6' width='60%' class='header bg-table-header' align='center'>Jumlah</td>";
    echo "</tr>";
    echo "<tr height='15'>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Mengajar</td>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Asistensi</td>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Tambahan</td>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Jam</td>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Kelas</td>";
    echo "<td width='8%' class='bg-table-header fg-white' align='center'>Hari</td>";
    echo "</tr>";

    $cnt = 0;
    while ($row = mysqli_fetch_row($res))
    {
        echo "<tr height='25'>";
        echo "<td align='center' class='numberColumn' >" . ++$cnt. "</td>";
        echo "<td><span class='cur-hand' onclick='showInfoPegawai(\"$row[0]\")'><b>$row[1]</b><br><span class='fg-secondary'>$row[0]</span></span></td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[2], '') . "</td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[3], '') . "</td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[4], '') . "</td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[5], '') . "</td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[6], '') . "</td>";
        echo "<td align='center' class='fs-14'>" . IfZero($row[7], '') . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}

function IfZero($value, $ret)
{
    return ($value == 0) ? $ret : $value;
}
?>
