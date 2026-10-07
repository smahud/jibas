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

function ShowSelectAngkatan($db)
{
    global $departemen, $idAngkatan;

    $sql = "SELECT replid, angkatan
              FROM jbsakad.angkatan
             WHERE departemen = '$departemen'
               AND aktif = 1  
             ORDER BY replid DESC";
    $res = $db->QueryDb($sql);

    echo "<select id='angkatan' name='angkatan' class='inputbox' style='width:220px' onchange='onChangeAngkatan()'>";
    while ($row = mysqli_fetch_assoc($res))
    {
        $sel = $idAngkatan == $row['replid'] ? "selected" : "";
        echo "<option value='$row[replid]' $sel>$row[angkatan]</option>";
    }
    echo "</select>";
}

function ShowSelectAgama($db)
{
    global $agama;

    $sql = "SELECT agama
              FROM jbsumum.agama
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    echo "<select id='agama' name='agama' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $agama == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowSelectSuku($db)
{
    global $suku;

    $sql = "SELECT suku
              FROM jbsumum.suku
             ORDER BY suku";
    $res = $db->QueryDb($sql);
    echo "<select id='suku' name='suku' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $suku == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowSelectStatusSiswa($db)
{
    global $statusSiswa;

    $sql = "SELECT status
              FROM jbsakad.statussiswa
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    echo "<select id='statussiswa' name='statussiswa' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $statusSiswa == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowSelectKondisiSiswa($db)
{
    global $kondisiSiswa;

    $sql = "SELECT kondisi
              FROM jbsakad.kondisisiswa
             ORDER BY urutan";
    $res = $db->QueryDb($sql);
    echo "<select id='kondisisiswa' name='kondisisiswa' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $kondisiSiswa == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowSelectStatusAnak()
{
    global $statusAnak;

    echo "<select name='statusanak' id='statusanak' class='inputbox' style='width: 200px'>";
    echo "<option value='0' " . StringIsSelected($statusAnak, "0") . ">(belum ada data)</option>";
	echo "<option value='Kandung' " . StringIsSelected($statusAnak, "Kandung") . ">Anak Kandung</option>";
    echo "<option value='Angkat' " . StringIsSelected($statusAnak, "Angkat") . ">Anak Angkat</option>";
    echo "<option value='Tiri' " . StringIsSelected($statusAnak, "Tiri") . ">Anak Tiri</option>";
    echo "<option value='Lainnya' " . StringIsSelected($statusAnak, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}


function ShowSelectJenjangSekolah()
{
    global $jenjangSekolah;

    echo "<select id='jenjangsekolah' name='jenjangsekolah' class='inputbox' style='width: 150px' onchange='onChangeJenjangSekolah()'>";
    echo "<option value='0' " . StringIsSelected($jenjangSekolah, "0") . ">(belum ada data)</option>";
	echo "<option value='TK/RA' " . StringIsSelected($jenjangSekolah, "TK/RA") . ">TK | RA</option>";
    echo "<option value='SD/MI' " . StringIsSelected($jenjangSekolah, "SD/MI") . ">SD | MI</option>";
    echo "<option value='SMP/MTS' " . StringIsSelected($jenjangSekolah, "SMP/MTS") . ">SMP | MTS</option>";
    echo "<option value='SMA/SMK/MA' " . StringIsSelected($jenjangSekolah, "SMA/SMK/MA") . ">SMA | SMK | MA</option>";
    echo "<option value='Lainnya' " . StringIsSelected($jenjangSekolah, "Lainnya") . " >Lainnya</option>";
    echo "</select>";
}

function ShowSelectAsalSekolah($db)
{
    global $jenjangSekolah, $asalSekolah;

    if ($jenjangSekolah == "0")
    {
        echo "<select id='asalsekolah' name='asalsekolah' class='inputbox' style='width: 250px'>";
        echo "<option value='0'>(belum ada data)</option>";
        echo "</select>";

        return;
    }

    $sql = "SELECT sekolah
              FROM jbsakad.asalsekolah
             WHERE departemen = '$jenjangSekolah'
             ORDER BY sekolah";
    $res = $db->QueryDb($sql);             
    echo "<select id='asalsekolah' name='asalsekolah' class='inputbox' style='width: 250px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $asalSekolah == $row[0] ? "selected" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";  
}

function ShowSelectStatusOrtu($id, $selStatus)
{
    echo "<select name='$id' id='$id' class='inputbox' style='width: 325px'>";
	echo "<option value='Kandung' " . StringIsSelected($selStatus, "Kandung") . ">Ortu Kandung</option>";
    echo "<option value='Angkat' " . StringIsSelected($selStatus, "Angkat") . ">Ortu Angkat</option>";
    echo "<option value='Tiri' " . StringIsSelected($selStatus, "Tiri") . ">Ortu Tiri</option>";
    echo "<option value='Lainnya' " . StringIsSelected($selStatus, "Lainnya") . ">Lainnya</option>";
    echo "</select>";
}

function ShowSelectPendidikanOrtu($db, $id, $selPendidikan)
{
    $sql = "SELECT pendidikan
              FROM jbsumum.tingkatpendidikan
             ORDER BY urutan, pendidikan";
    $res = $db->QueryDb($sql);
    echo "<select name='$id' id='$id' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $selPendidikan == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowSelectPekerjaanOrtu($db, $id, $selPekerjaan)
{
    $sql = "SELECT pekerjaan
              FROM jbsumum.jenispekerjaan
             ORDER BY pekerjaan";
    $res = $db->QueryDb($sql);
    echo "<select name='$id' id='$id' class='inputbox' style='width: 200px'>";
    echo "<option value='0'>(belum ada data)</option>";
    while($row = mysqli_fetch_row($res))
    {
        $sel = $selPekerjaan == $row[0] ? "selected='selected'" : "";
        echo "<option value='$row[0]' $sel>$row[0]</option>";
    }
    echo "</select>";
}

function ShowDataTambahan($db)
{
    global $departemen, $nis;
    
    $sql = "SELECT replid, kolom, jenis
              FROM jbsakad.tambahandata 
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY urutan";
    $res = $db->QueryDb($sql);

    $idtambahan = "";
    while($row = mysqli_fetch_row($res))
    {
        $replid = $row[0];
        $kolom = $row[1];
        $jenis = $row[2];

        if ($idtambahan != "") $idtambahan .= ",";
        $idtambahan .= $replid;

        $replid_data = 0;
        $data = "";
        if ($jenis == 1)
        {
            $sql = "SELECT replid, teks 
                      FROM jbsakad.tambahandatasiswa 
                     WHERE nis = '$nis' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }
        }
        else if ($jenis == 2)
        {
            $sql = "SELECT replid, filename 
                      FROM jbsakad.tambahandatasiswa 
                     WHERE nis = '$nis' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $filename = $row2[1];
                $data = "<a href='tambahandata.file.php?replid=$replid_data'>$filename</a>";
            }
            else
            {
                $replid_data = 0;
                $data = "";
            }
        }
        else if ($jenis == 3)
        {
            $sql = "SELECT replid, teks 
                      FROM jbsakad.tambahandatasiswa 
                     WHERE nis = '$nis' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }

            $sql = "SELECT pilihan 
                      FROM jbsakad.pilihandata 
                     WHERE idtambahan = '$replid'
                       AND aktif = 1
                     ORDER BY urutan";
            $res2 = $db->QueryDb($sql);

            $arrList = array();
            if (mysqli_num_rows($res2) == 0)
                $arrList[] = "-";

            while($row2 = mysqli_fetch_row($res2))
            {
                $arrList[] = $row2[0];
            }

            $opt = "";
            for($i = 0; $i < count($arrList); $i++)
            {
                $pilihan = $arrList[$i];
                $sel = $pilihan == $data ? "selected" : "";
                $opt .= "<option value='$pilihan' $sel>$pilihan</option>";
            }
        }

        echo "<tr style='height: 24px;'>";
        echo "<td width='10%' align='right' valign='top'>$kolom:</td>";
        echo "<td colspan='2'>";

        if ($jenis == 1)
        {
            echo "<input type='hidden' id='jenisdata-$replid' name='jenisdata-$replid' value='1'>";
            echo "<input type='hidden' id='repliddata-$replid' name='repliddata-$replid' value='$replid_data'>";
            echo "<input type='text' class='inputbox' name='tambahandata-$replid' id='tambahandata-$replid' size='40' maxlength='1000' value='$data'>";
        }
        else if ($jenis == 2)
        {
            echo "<input type='hidden' id='jenisdata-$replid' name='jenisdata-$replid' value='2'>";
            echo "<input type='hidden' id='repliddata-$replid' name='repliddata-$replid' value='$replid_data'>";
            echo "<input type='file' name='tambahandata-$replid' id='tambahandata-$replid' size='25' style='width:215px'>";
            echo "<i>$data</i>";
        }
        else if ($jenis == 3)
        {
            echo "<input type='hidden' id='jenisdata-$replid' name='jenisdata-$replid' value='3'>";
            echo "<input type='hidden' id='repliddata-$replid' name='repliddata-$replid' value='$replid_data'>";
            echo "<select class='inputbox' name='tambahandata-$replid' id='tambahandata-$replid' style='width:215px'>";
            echo $opt;
            echo "</select>";
        }

        echo "</td>";
        echo "</tr>";
    }
    echo "<input type='hidden' id='idtambahan' name='idtambahan' value='$idtambahan'>";

}
?>