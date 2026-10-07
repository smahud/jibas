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
function ShowSelectDepartemen($idSelect, $onChange)
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        $dep = getDepartemen($db, SI_USER_ACCESS());
        echo "<select id='$idSelect' onchange='$onChange' style='width:250px' class='inputbox'>";
        foreach ($dep as $value) 
        {
            if ($departemen == "") 
                $departemen = $value;
            
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k70qn");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectPelajaran()
{
    global $departemen, $idPelajaran;

    $db = new Db();
    try
    {   
        $db->Open();
        
        $sql = "SELECT replid, nama 
                  FROM jbsakad.pelajaran 
                 WHERE departemen = '$departemen' 
                   AND aktif = 1 
                 ORDER BY nama";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tabguru_pelajaran_pilih' onchange='tabguru_onChangePelajaran()' class='inputbox' style='width:200px'>";
        $sel = $idPelajaran == "" || $idPelajaran == 0 ? "selected" : "";
        echo "<option value='0' $sel>Semua Pelajaran</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            $sel = $idPelajaran == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[nama]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }
    finally
    {
        $db->Close();
    }
}


function ShowDaftarGuru()
{
    global $idPelajaran, $urut;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT DISTINCT p.nip, p.nama
                  FROM jbssdm.pegawai p, jbsakad.guru g
                 WHERE p.nip = g.nip 
                   AND p.aktif = 1";
        if ($idPelajaran != 0)
            $sql .= " AND g.idpelajaran = $idPelajaran";
        $sql .= " ORDER BY $urut";

        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><i>Tidak ditemukan data guru</i>";
            return;
        }

        echo "<table id='tabguru_table_pilih' class='tab' border='1' style='border-collapse:collapse' width='100%' align='center'>";
        echo "<tr align='center'>";
        echo "<td class='header-sm' width='10%'>No</td>";
        echo "<td class='header-sm' width='40%'>";
        echo "<span style='cursor: pointer' onclick=\"tabguru_changeUrut('daftar', 'p.nip')\">NIP</span>";
        echo "</td>";
        echo "<td class='header-sm' width='*'>";
        echo "<span style='cursor: pointer' onclick=\"tabguru_changeUrut('daftar', 'p.nama')\">Nama</span>";
        echo "</td>";
        echo "</tr>";

        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            $cnt += 1;

            $nip = $row[0];

            $lsIdPelajaran = [];
            $lsPelajaran = [];
            $stPelajaran = "";
            $sql = "SELECT g.idpelajaran, pl.nama AS pelajaran
                      FROM jbsakad.guru g, jbsakad.pelajaran pl
                     WHERE g.nip = '$nip'
                       AND pl.replid = g.idpelajaran
                     ORDER BY pl.nama";
            $res2 = $db->QueryDb($sql);
            while($row2 = mysqli_fetch_row($res2))
            {
                $lsIdPelajaran[] = $row2[0];
                $lsPelajaran[] = $row2[1];

                if ($stPelajaran != "") $stPelajaran .= ", ";
                $stPelajaran .= $row2[1];
            }

            $data = new stdClass();
            $data->NIP = $row[0];
            $data->Nama = $row[1];
            $data->Replid = $row[3];
            $data->Bagian = $row[2];
            $data->IdPelajaran = json_encode($lsIdPelajaran);
            $data->Pelajaran = json_encode($lsPelajaran);
            $data->StatusGuru = $row[6];
            $data->Kelompok = "guru";

            $json64 = base64_encode(json_encode($data));

            echo "<tr style='cursor: pointer' onclick='tabguru_pilihGuru(\"guru\", \"$json64\")'>";
            echo "<td class='numberColumn' align='center'>$cnt</td>";
            echo "<td colspan='2'><span style='color: blue'>$row[0]</span>&nbsp;&nbsp;&nbsp;$row[1]<br>";
            echo "<span style='color: #333'>$stPelajaran</span>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k8d3t");
    }
    finally
    {
        $db->Close();
    }
}

function ShowCariGuru()
{
    global $departemen, $search, $searchBy, $urut;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT p.nip, p.nama, p.bagian, p.replid, g.idpelajaran, pl.nama AS pelajaran, g.statusguru
                  FROM jbssdm.pegawai p, jbsakad.guru g, jbsakad.pelajaran pl
                 WHERE p.nip = g.nip 
                   AND pl.replid = g.idpelajaran
                   AND p.aktif = 1
                   AND pl.departemen = '$departemen'
                   AND $searchBy LIKE '%$search%'
                 ORDER BY $urut";

        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><i>Tidak ditemukan data guru</i>";
            return;
        }

        echo "<table id='tabguru_table_pilih' class='tab' border='1' style='border-collapse:collapse' width='100%' align='center'>";
        echo "<tr align='center'>";
        echo "<td class='header-sm' width='10%'>No</td>";
        echo "<td class='header-sm' width='40%'>";
        echo "<span style='cursor: pointer' onclick=\"tabguru_changeUrut('daftar', 'p.nip')\">NIP</span>";
        echo "</td>";
        echo "<td class='header-sm' width='*'>";
        echo "<span style='cursor: pointer' onclick=\"tabguru_changeUrut('daftar', 'p.nama')\">Nama</span>";
        echo "</td>";
        echo "</tr>";

        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            $cnt += 1;

            $data = new stdClass();
            $data->NIP = $row[0];
            $data->Nama = $row[1];
            $data->Replid = $row[3];
            $data->Bagian = $row[2];
            $data->IdPelajaran = $row[4];
            $data->Pelajaran = $row[5];
            $data->StatusGuru = $row[6];
            $data->Kelompok = "guru";

            $json64 = base64_encode(json_encode($data));

            echo "<tr style='cursor: pointer' onclick='tabguru_pilihGuru(\"guru\", \"$json64\")'>";
            echo "<td class='numberColumn' align='center'>$cnt</td>";
            echo "<td colspan='2'><span style='color: blue'>$row[0]</span>&nbsp;&nbsp;&nbsp;$row[1]<br>";
            echo "<span style='color: #666'> Pelajaran: $row[5] ($row[6])</span>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";

    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k8d3t");
    }
    finally
    {
        $db->Close();
    }
}
?>