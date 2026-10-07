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

function GetBaseQuery()
{
    global $departemen, $idTingkat, $idKelas;
    
    if ($idKelas == 0 && $idTingkat == 0)
    {
        $sql = "SELECT TBD_COLUMN
                  FROM jbsakad.siswa s, jbsakad.angkatan a
                 WHERE s.idangkatan = a.replid
                   AND s.aktif = 1     
                   AND a.departemen = '$departemen'";
    }
    else if ($idKelas == 0)
    {
        $sql = "SELECT TBD_COLUMN
                  FROM jbsakad.siswa s, jbsakad.tingkat t, jbsakad.kelas k
                 WHERE s.idkelas = k.replid
                   AND s.aktif = 1 
                   AND k.idtingkat = t.replid 
                   AND t.replid = '$idTingkat'";
    }
    else 
    {
        $sql = "SELECT TBD_COLUMN
                  FROM jbsakad.siswa s
                 WHERE s.idkelas = '$idKelas'
                   AND s.aktif = 1";
    }

    return $sql;
}

function GetStatistikData($db)
{
    global $jenisStatistik;

    if ($jenisStatistik == "agama")
        return GetAgamaData($db);    
    elseif ($jenisStatistik == "asalsekolah")
        return GetAsalSekolahData($db);    
    elseif ($jenisStatistik == "darah")
        return GetGolonganDarahData($db);    
    elseif ($jenisStatistik == "kelamin")
        return GetJenisKelaminData($db);    
    elseif ($jenisStatistik == "kodepossiswa")
        return GetKodePosSiswaData($db);      
    elseif ($jenisStatistik == "kondisisiswa")
        return GetKondisiSiswaData($db);      
    elseif ($jenisStatistik == "pekerjaanayah")                        
        return GetPekerjaanAyahData($db);
    elseif ($jenisStatistik == "pekerjaanibu")
        return GetPekerjaanIbuData($db);
    elseif ($jenisStatistik == "pendidikanayah")
        return GetPendidikanAyahData($db);
    elseif ($jenisStatistik == "pendidikanibu")
        return GetPendidikanIbuData($db);
    elseif ($jenisStatistik == "penghasilanayah")        
        return GetPenghasilanAyahData($db);
    elseif ($jenisStatistik == "penghasilanibu")        
        return GetPenghasilanIbuData($db);
    elseif ($jenisStatistik == "statussiswa")        
        return GetStatusSiswaData($db);
    elseif ($jenisStatistik == "suku")        
        return GetSukuData($db);
    elseif ($jenisStatistik == "warganegara")        
        return GetWargaNegaraData($db);
    elseif ($jenisStatistik == "tahunlahir")        
        return GetTahunLahirData($db);
    elseif ($jenisStatistik == "usia")        
        return GetUsiaData($db);
}

function GetUsiaData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IF(s.tgllahir IS NULL, '(belum ada data)', IF(YEAR(s.tgllahir) = 0, '(belum ada data)', YEAR(NOW()) - YEAR(s.tgllahir))) AS x", $sql);
    $sql .= " GROUP BY x";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetTahunLahirData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IF(s.tgllahir IS NULL, '(belum ada data)', IF(YEAR(s.tgllahir) = 0, '(belum ada data)', YEAR(s.tgllahir))) AS x", $sql);
    $sql .= " GROUP BY x";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetWargaNegaraData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.warga, '(belum ada data)') ", $sql);
    $sql .= " GROUP BY s.warga ORDER BY s.warga";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetSukuData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.suku, '(belum ada data)') ", $sql);
    $sql .= " GROUP BY s.suku ORDER BY s.suku";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetStatusSiswaData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.status, '(belum ada data)') ", $sql);
    $sql .= " GROUP BY s.status ORDER BY s.status";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}


function GetPenghasilanIbuData($db)
{
    $labels = ["less Rp 2.000.000", "Rp 2.000.000 - Rp 5.000.000", "Rp 5.000.000 - Rp 10.000.000", "more Rp 10.000.000"];

    $sqlMain = GetBaseQuery();
    $sqlMain = str_replace("TBD_COLUMN", "COUNT(s.replid) ", $sqlMain);

    for($i = 0; $i < count($labels); $i++) 
    {
        $sql = $sqlMain;

        if ($i == 0)
            $sql .= " AND s.penghasilanibu < 2000000";
        elseif ($i == 1)
            $sql .= " AND s.penghasilanibu >= 2000000 AND s.penghasilanibu < 5000000";
        elseif ($i == 2)
            $sql .= " AND s.penghasilanibu >= 5000000 AND s.penghasilanibu < 10000000";
        elseif ($i == 3)
            $sql .= " AND s.penghasilanibu >= 10000000";

        $res = $db->QueryDb($sql);
        if($row = mysqli_fetch_row($res))
        {
            $values[] = $row[0];
        }
        else 
        {
            $values[] = 0;
        }
    }

    return [$values, $labels];
}

function GetPenghasilanAyahData($db)
{
    $labels = ["less Rp 2.000.000", "Rp 2.000.000 - Rp 5.000.000", "Rp 5.000.000 - Rp 10.000.000", "more Rp 10.000.000"];

    $sqlMain = GetBaseQuery();
    $sqlMain = str_replace("TBD_COLUMN", "COUNT(s.replid) ", $sqlMain);

    for($i = 0; $i < count($labels); $i++) 
    {
        $sql = $sqlMain;

        if ($i == 0)
            $sql .= " AND s.penghasilanayah < 2000000";
        elseif ($i == 1)
            $sql .= " AND s.penghasilanayah >= 2000000 AND s.penghasilanayah < 5000000";
        elseif ($i == 2)
            $sql .= " AND s.penghasilanayah >= 5000000 AND s.penghasilanayah < 10000000";
        elseif ($i == 3)
            $sql .= " AND s.penghasilanayah >= 10000000";

        $res = $db->QueryDb($sql);
        if($row = mysqli_fetch_row($res))
        {
            $values[] = $row[0];
        }
        else 
        {
            $values[] = 0;
        }
    }

    return [$values, $labels];
}

function GetPendidikanIbuData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.pendidikanibu, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.pendidikanibu ORDER BY s.pendidikanibu";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetPendidikanAyahData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.pendidikanayah, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.pendidikanayah ORDER BY s.pendidikanayah";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetPekerjaanIbuData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.pekerjaanibu, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.pekerjaanibu ORDER BY s.pekerjaanibu";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetPekerjaanAyahData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.pekerjaanayah, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.pekerjaanayah ORDER BY s.pekerjaanayah";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetKondisiSiswaData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.kondisi, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.kondisi ORDER BY s.kondisi";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetKodePosSiswaData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.kodepossiswa, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.kodepossiswa ORDER BY s.kodepossiswa";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetJenisKelaminData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.kelamin, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.kelamin ORDER BY s.kelamin";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetGolonganDarahData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.darah, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.darah ORDER BY s.darah";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetAsalSekolahData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.asalsekolah, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.asalsekolah ORDER BY s.asalsekolah";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function GetAgamaData($db)
{
    $sql = GetBaseQuery();
    $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), IFNULL(s.agama, '(belum ada data)') ", $sql);
    $sql .= " AND s.aktif = 1 ";
    $sql .= " GROUP BY s.agama ORDER BY s.agama";

    $values = [];
    $labels = [];
    $res = $db->QueryDb($sql);
    while($row = mysqli_fetch_row($res))
    {
        $values[] = $row[0];
        $labels[] = $row[1];
    }

    return [$values, $labels];
}

function ChartDataAgama($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Agama";
    $chartData[] = "Agama";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataAsalSekolah($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Asal Sekolah";
    $chartData[] = "Asal Sekolah";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataGolonganDarah($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Golongan Darah";
    $chartData[] = "Golongan Darah";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function BuildChartData($values, $labels)
{
    global $jenisStatistik;

    if ($jenisStatistik == "agama")
        return ChartDataAgama($values, $labels);
    elseif ($jenisStatistik == "asalsekolah")
        return ChartDataAsalSekolah($values, $labels);
    elseif ($jenisStatistik == "darah")
        return ChartDataGolonganDarah($values, $labels);
    elseif ($jenisStatistik == "kelamin")
        return ChartDataJenisKelamin($values, $labels);
    elseif ($jenisStatistik == "kodepossiswa")
        return ChartDataKodePosSiswa($values, $labels);
    elseif ($jenisStatistik == "kondisisiswa")
        return ChartDataKondisiSiswa($values, $labels);
    elseif ($jenisStatistik == "pekerjaanayah")
        return ChartDataPekerjaanAyah($values, $labels);
    elseif ($jenisStatistik == "pekerjaanibu")
        return ChartDataPekerjaanIbu($values, $labels);
    elseif ($jenisStatistik == "pendidikanayah")
        return ChartDataPendidikanAyah($values, $labels);
    elseif ($jenisStatistik == "pendidikanibu")
        return ChartDataPendidikanIbu($values, $labels);
    elseif ($jenisStatistik == "penghasilanayah")        
        return ChartDataPenghasilanAyah($values, $labels);
    elseif ($jenisStatistik == "penghasilanibu")        
        return ChartDataPenghasilanIbu($values, $labels);
    elseif ($jenisStatistik == "statussiswa")        
        return ChartDataStatusSiswa($values, $labels);
    elseif ($jenisStatistik == "suku")        
        return ChartDataSuku($values, $labels);
    elseif ($jenisStatistik == "warganegara")        
        return ChartDataWargaNegara($values, $labels);
    elseif ($jenisStatistik == "tahunlahir")        
        return ChartDataTahunLahir($values, $labels);
    elseif ($jenisStatistik == "usia")        
        return ChartDataUsia($values, $labels);
}

function ChartDataUsia($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Usia";
    $chartData[] = "Usia";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataTahunLahir($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Tahun Lahir";
    $chartData[] = "Tahun Lahir";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataWargaNegara($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Warga Negara";
    $chartData[] = "Warga Negara";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataSuku($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Suku";
    $chartData[] = "Suku";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataStatusSiswa($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Status Siswa";
    $chartData[] = "Status Siswa";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPenghasilanIbu($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Penghasilan Ibu";
    $chartData[] = "Penghasilan Ibu";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPenghasilanAyah($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Penghasilan Ayah";
    $chartData[] = "Penghasilan Ayah";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPendidikanIbu($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Pendidikan Ibu";
    $chartData[] = "Pendidikan Ibu";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPendidikanAyah($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Pendidikan Ayah";
    $chartData[] = "Pendidikan Ayah";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPekerjaanIbu($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Pekerjaan Ibu";
    $chartData[] = "Pekerjaan Ibu";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataPekerjaanAyah($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Pekerjaan Ayah";
    $chartData[] = "Pekerjaan Ayah";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataKondisiSiswa($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Kondisi Siswa";
    $chartData[] = "Kondisi Siswa";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataKodePosSiswa($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Kode Pos";
    $chartData[] = "Kode Pos";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ChartDataJenisKelamin($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Siswa Aktif Berdasarkan Jenis Kelamin";
    $chartData[] = "Jenis Kelamin";
    $chartData[] = "Jumlah Siswa";
    $chartData[] = $values;
    $chartData[] = $labels;
    return $chartData;
}

function ShowStatistikChart($values, $labels)
{
    $chartData = BuildChartData($values, $labels);
    $data =  base64_encode(json_encode($chartData));
    echo "<img src='statistik.content.chart.php?data=$data'>";
}

function FormatLabel($label)
{
    global $jenisStatistik;

    if ($jenisStatistik == "kelamin")
    {
        if ($label == "l")
            return "Laki-Laki";
        elseif ($label == "p")
            return "Perempuan";
    }

    return $label;
}

function ShowStatistikTable($values, $labels)
{
    global $jenisStatistikText;

    echo "<span style='right: 0; position: absolute;' class='cur-hand hide-in-report' onclick='cetak()'>cetak</span>&nbsp;&nbsp;<br>";
    echo "<table id='tableRekap' class='tab tabShadow' width='100%'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='35' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='*' align='center'>$jenisStatistikText</td>";
    echo "<td class='bg-table-header fg-white' width='80' align='center'>Jumlah</td>";
    echo "<td class='bg-table-header fg-white' width='80' align='center'>Persen</td>";
    echo "<td class='bg-table-header fg-white hide-in-report' width='50' align='center'>&nbsp;</td>";
    echo "</tr>";

    $total = array_sum($values);
    foreach ($values as $index => $value)
    {
        $label = $labels[$index];
        $percent = $total > 0 ? ($value / $total) * 100 : 0;
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>" . ($index + 1) . "</td>";
        echo "<td align='left'>" . FormatLabel($label) . "</td>";
        echo "<td align='center'>" . $value . "</td>";
        echo "<td align='center'>" . number_format($percent, 2) . "%</td>";
        echo "<td align='center' class='hide-in-report'><img src='../images/ico/lihat.png' onclick='showStatistikTableDetail(\"$label\")' class='cur-hand'></td>";
        echo "</tr>";
    }
    echo "</table>";
}

function BuildListQuery()
{
    global $jenisStatistik, $label;

    $sql = GetBaseQuery();
    if ($jenisStatistik == "agama")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.agama IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.agama = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "asalsekolah")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.asalsekolah IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.asalsekolah = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "darah")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.darah IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.darah = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "kelamin")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.kelamin IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.kelamin = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "kodepossiswa")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.kodepossiswa IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.kodepossiswa = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "kondisisiswa")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.kondisi IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.kondisi = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "pekerjaanayah")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.pekerjaanayah IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.pekerjaanayah = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "pekerjaanibu")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.pekerjaanibu IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.pekerjaanibu = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "pendidikanayah")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.pendidikanayah IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.pendidikanayah = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "pendidikanibu")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);
        if ($label == "(belum ada data)")
            $sql .= " AND s.pendidikanibu IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.pendidikanibu = '$label' ORDER BY s.nama";
    }
    else if ($jenisStatistik == "penghasilanayah")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        $label = str_replace("&lt;", "<", $label);
        $label = str_replace("&gt;", ">", $label);

        if ($label == "< Rp 2.000.000")
            $sql .= " AND s.penghasilanayah < 2000000 ORDER BY s.nama";
        else if ($label == "Rp 2.000.000 - Rp 5.000.000")
            $sql .= " AND s.penghasilanayah >= 2000000 AND s.penghasilanayah < 5000000 ORDER BY s.nama";
        else if ($label == "Rp 5.000.000 - Rp 10.000.000")
            $sql .= " AND s.penghasilanayah >= 5000000 AND s.penghasilanayah < 10000000 ORDER BY s.nama";
        else if ($label == "> Rp 10.000.000")
            $sql .= " AND s.penghasilanayah >= 10000000 ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "penghasilanibu")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        $label = str_replace("&lt;", "<", $label);
        $label = str_replace("&gt;", ">", $label);

        if ($label == "< Rp 2.000.000")
            $sql .= " AND s.penghasilanibu < 2000000 ORDER BY s.nama";
        else if ($label == "Rp 2.000.000 - Rp 5.000.000")
            $sql .= " AND s.penghasilanibu >= 2000000 AND s.penghasilanibu < 5000000 ORDER BY s.nama";
        else if ($label == "Rp 5.000.000 - Rp 10.000.000")
            $sql .= " AND s.penghasilanibu >= 5000000 AND s.penghasilanibu < 10000000 ORDER BY s.nama";
        else if ($label == "> Rp 10.000.000")
            $sql .= " AND s.penghasilanibu >= 10000000 ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "statussiswa")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        if ($label == "(belum ada data)")
            $sql .= " AND s.status IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.status = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "suku")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        if ($label == "(belum ada data)")
            $sql .= " AND s.suku IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.suku = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "warganegara")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        if ($label == "(belum ada data)")
            $sql .= " AND s.warga IS NULL ORDER BY s.nama";
        else
            $sql .= " AND s.warga = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "tahunlahir")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        if ($label == "(belum ada data)")
            $sql .= " AND s.tgllahir IS NULL OR YEAR(s.tgllahir) = 0 ORDER BY s.nama";
        else
            $sql .= " AND YEAR(s.tgllahir) = '$label' ORDER BY s.nama";
    }
    elseif ($jenisStatistik == "usia")
    {
        $sql = str_replace("TBD_COLUMN", "COUNT(s.replid), GROUP_CONCAT(s.replid)", $sql);

        if ($label == "(belum ada data)")
            $sql .= " AND s.tgllahir IS NULL OR YEAR(s.tgllahir) = 0 ORDER BY s.nama";
        else
            $sql .= " AND (YEAR(NOW()) - YEAR(s.tgllahir)) = '$label' ORDER BY s.nama";
    }

    return $sql;
}

function PrepareListData()
{
    global $nRowPerPage;
    

    $db = new Db();
    try
    {
        $db->Open();

        $sql = BuildListQuery();
        
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) <= 0)
            return json_encode([0, "Data statistik tidak ditemukan", 0, 0, [], ""]);

        $row = mysqli_fetch_row($res);
        $nData = (int) $row[0];
        $stReplid = $row[1];

        $lsIdPage = [];
        if (strpos($stReplid, ",") === false)
        {
            $nPage = 1;
            $lsIdPage = [[ (int) $stReplid ]];
        }
        else 
        {
            $nPage = ceil($nData / $nRowPerPage);
            $arrId = explode(",", $stReplid);

            $n = 0;
            $arrIdPage = [];
            for($i = 0; $i < count($arrId); $i++)
            {
                if ($n < $nRowPerPage)
                {
                    $arrIdPage[] = (int)$arrId[$i];
                    $n += 1;
                }

                if ($n == $nRowPerPage)
                {
                    $lsIdPage[] = $arrIdPage;

                    $n = 0;
                    $arrIdPage = [];
                }
            }

            if (count($arrIdPage) > 0)
                $lsIdPage[] = $arrIdPage;
        }
        
        return json_encode([1, "OK", $nData, $nPage, $lsIdPage, $stReplid]);      
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage(), 0, 0, [], ""]);
    }
    finally
    {
        $db->Close();
    }
}

function ShowTableListSiswa()
{
    global $nRowPerPage;

    $db = new Db();
    try
    {
        $db->Open();

        $page = RequestData("page", 1);
        $stReplid = RequestData("streplid", "");

        echo "<span style='right: 0; position: absolute;' class='cur-hand' onclick='saveExcel()'>excel</span>&nbsp;&nbsp;<br>";
        echo "<table id='tableSiswa' class='tab tabShadow' width='100%'>";
        echo "<tr>";
        echo "<td class='bg-table-header fg-white' width='35' align='center'>No</td>";
        echo "<td class='bg-table-header fg-white' width='250' align='center'>Siswa</td>";
        echo "<td class='bg-table-header fg-white' width='250' align='center'>Kelas</td>";
        echo "<td class='bg-table-header fg-white' width='50' align='center'>&nbsp;</td>";
        echo "</tr>";

        $sql = "SELECT s.replid, nis, nama, s.aktif, 
                       IFNULL(panggilan, '') AS fpanggilan,
                       t.departemen, t.tingkat, k.kelas
                  FROM jbsakad.siswa s, jbsakad.tingkat t, jbsakad.kelas k
                 WHERE s.idkelas = k.replid
                   AND k.idtingkat = t.replid 
                   AND s.replid IN ($stReplid)";
        $res = $db->QueryDb($sql);

        $no = ($page - 1) * $nRowPerPage;
        while($row = mysqli_fetch_assoc($res))
        {
            $no += 1;

            $replid = $row['replid'];

            echo "<tr>";
            echo "<td align='center' class='numberColumn'>$no</td>";

            echo "<td valign='top'>";
            echo "<span id='nama$replid' class='fs-11 fst-bold'>" . $row['nama'] . "</span><br>";
            echo "<span id='nis$replid' class='ff-consolas fs-11'>" . $row['nis'] . "</span><br>";
            echo "<span id='panggilan$replid' class='fst-italic fs-11 fg-secondary'>" . $row['fpanggilan'] . "</span><br>";
            echo "<span id='spInfoAktif$replid' class='fg-red fst-italic fs-10'>";
            if ($row['aktif'] == 0)
                echo "[Tidak Aktif]";
            echo "</span>";
            echo "</td>";

            echo "<td valign='top'>";
            echo $row['departemen'] . "<br>&nbsp;&nbsp;" . $row['tingkat'] . " " . $row['kelas'] . "<br>";
            echo "</td>";

            echo "<td align='center'>";
            echo "<img src='../images/ico/lihat.png' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
            echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>";
            echo "</td>";
            echo "</tr>";
        }

        echo "</table>";
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowPageControl()
{
    global $nPage, $nData;

    echo "Halaman ";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='<' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='>' onclick='onNextPage()'>";
    echo " dari $nPage, jumlah $nData data";
}
?>