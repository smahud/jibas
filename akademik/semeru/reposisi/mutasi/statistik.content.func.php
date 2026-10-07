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
function GetStatistikData($db)
{
    global $departemen, $tahunMutasi;

    $sql = "SELECT jm.replid, jm.jenismutasi, COUNT(m.replid) AS jumlah
              FROM jbsakad.mutasisiswa m, jbsakad.jenismutasi jm
             WHERE m.departemen = '$departemen'
               AND YEAR(m.tglmutasi) = $tahunMutasi
               AND m.jenismutasi = jm.replid
             GROUP BY jm.replid";
    $res = $db->QueryDb($sql);

    $values = [];
    $idlabels = [];
    $labels = [];
    while ($row = mysqli_fetch_array($res))
    {
        $values[] = $row['jumlah'];
        $idlabels[] = $row['replid'];
        $labels[] = $row['jenismutasi'];
    }
    return [ $values, $idlabels, $labels ];
}

function BuildChartData($values, $labels)
{
    $chartData[] = "barchart";
    $chartData[] = "Statistik Mutasi Siswa";
    $chartData[] = "Jenis Mutasi";
    $chartData[] = "Jumlah";
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

function ShowStatistikTable($values, $labels, $idlabels)
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
        $idlabel = $idlabels[$index];
        $label = $labels[$index];
        $percent = $total > 0 ? ($value / $total) * 100 : 0;
        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>" . ($index + 1) . "</td>";
        echo "<td align='left'>" . $label . "</td>";
        echo "<td align='center'>" . $value . "</td>";
        echo "<td align='center'>" . number_format($percent, 2) . "%</td>";
        echo "<td align='center' class='hide-in-report'><img src='../../images/ico/lihat.png' onclick='showStatistikTableDetail(\"$idlabel\",\"$label\")' class='cur-hand'></td>";
        echo "</tr>";
    }
    echo "</table>";
}

function PrepareListData()
{
    global $departemen, $tahunMutasi, $idLabel, $nRowPerPage;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(s.replid), GROUP_CONCAT(s.replid)
                  FROM jbsakad.mutasisiswa m, jbsakad.siswa s
                 WHERE m.nis = s.nis
                   AND m.departemen = '$departemen'
                   AND YEAR(m.tglmutasi) = $tahunMutasi
                   AND m.jenismutasi = $idLabel";

        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) <= 0)
            return json_encode([0, "data tidak ditemukan", 0, 0, [], ""]);

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

            echo "<td align='center'><img src='../../images/ico/lihat.png' class='cur-hand' onclick='detailSiswa($replid)'></td>";
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