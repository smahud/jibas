<?php
function ShowTablePinSiswa($db)
{
    global $idKelas;

    $sql = "SELECT s.replid, s.nis, s.nama, s.aktif,
                   IFNULL(s.panggilan, '') AS fpanggilan,
                   pinsiswa, pinortu, pinortuibu 
              FROM jbsakad.siswa s
             WHERE s.idkelas = '$idKelas'
            ORDER BY s.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>belum ada data siswa</i>";
        return;
    }

    echo "<br>";
    echo "<table id='table' class='tab tabShadow' width='95%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>NIS</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "<td class='bg-table-header fg-white' width='25%' align='center'>PIN Siswa</td>";
    //echo "<td class='bg-table-header fg-white' width='10%' align='center'>PIN Ayah</td>";
    //echo "<td class='bg-table-header fg-white' width='10%' align='center'>PIN Ibu</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<a class='ablue' onclick='showInfoSiswa(\"$row[nis]\")'>$row[nis]<a>";
        if ($row['aktif'] == 0)
            echo "<br><span class='fg-red fst-italic fs-10'>[Tidak Aktif]</span>";
        echo "</td>";
        echo "<td align='left' valign='top'>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary fst-italic'>$row[fpanggilan]</span>";
        echo "</td>";

        echo "<td align='center' valign='top'>";
        echo "<span id='spPinSiswa$replid' class='ff-courier fs-14'>";
        echo $row['pinsiswa'];
        echo "</span><br>";
        echo "<img src='../images/ico/refreshbw.png' class='cur-hand hide-in-report' style='height: 12px;' title='ganti pin' onclick='changePin($replid, \"siswa\")'>";
        echo "</td>";

        /*
        echo "<td align='center' valign='top'>";
        echo "<span id='spPinAyah$replid'>";
        echo $row['pinortu'];
        echo "</span><br>";
        echo "<img src='../images/ico/refreshbw.png' class='cur-hand hide-in-report' style='height: 12px;' title='ganti pin' onclick='changePin($replid, \"ayah\")'>";
        echo "</td>";

        echo "<td align='center' valign='top'>";
        echo "<span id='spPinIbu$replid'>";
        echo $row['pinortuibu'];
        echo "</span><br>";
        echo "<img src='../images/ico/refreshbw.png' class='cur-hand hide-in-report' style='height: 12px;' title='ganti pin' onclick='changePin($replid, \"ibu\")'>";
        echo "</td>";
        */

        echo "</tr>";
    }
    echo "</table>";
}

function GantiPin()
{
    $db = new Db();
    try
    {
        $db->Open();

        $newPin = RandInt(5);
        $replid = RequestData("replid", 0);
        $jenis = RequestData("jenis", "");

        $stWhere = "";
        if ($jenis == "siswa")
            $stWhere = "pinsiswa = '$newPin'";
        else if ($jenis == "ayah")
            $stWhere = "pinortu = '$newPin'";
        else if ($jenis == "ibu")
            $stWhere = "pinortuibu = '$newPin'";

        $sql = "UPDATE jbsakad.siswa
                   SET $stWhere
                 WHERE replid = $replid";
        $db->QueryDb($sql);

        return json_encode([1, $newPin]);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>