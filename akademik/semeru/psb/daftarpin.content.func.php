<?php
function ShowTablePinCalonSiswa($db)
{
    global $idKelompok;

    $sql = "SELECT cs.replid, cs.nopendaftaran as nic, cs.nama, cs.aktif, 
                   cs.panggilan, cs.pinsiswa 
              FROM jbsakad.calonsiswa cs
             WHERE cs.idkelompok = '$idKelompok'
             ORDER BY cs.nama";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) <= 0)
    {
        echo "<br><br><i>belum ada data calon siswa</i>";
        return;
    }

    echo "<br>";
    echo "<table id='table' class='tab tabShadow' width='95%' align='center'>";
    echo "<tr>";
    echo "<td class='bg-table-header fg-white' width='5%' align='center'>No</td>";
    echo "<td class='bg-table-header fg-white' width='20%' align='center'>No. Pendaftaran</td>";
    echo "<td class='bg-table-header fg-white' width='*'>Nama</td>";
    echo "<td class='bg-table-header fg-white' width='25%' align='center'>PIN Calon Siswa</td>";
    echo "</tr>";

    $no = 0;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];

        echo "<tr style='height: 35px'>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<a class='ablue' onclick='showInfoCalonSiswa(\"$row[nic]\")'>$row[nic]<a>";
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
        echo "<img src='../images/ico/refreshbw.png' class='cur-hand hide-in-report' style='height: 12px;' title='ganti pin' onclick='changePin($replid, \"calonsiswa\")'>";
        echo "</td>";

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
        if ($jenis == "calonsiswa")
            $stWhere = "pinsiswa = '$newPin'";

        $sql = "UPDATE jbsakad.calonsiswa
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