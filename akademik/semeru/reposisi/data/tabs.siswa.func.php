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
require_once (__DIR__ . "/../../include/config.php");
require_once (__DIR__ . "/../../include/db.onfunc.php");
require_once (__DIR__ . "/../../library/msg.php");
require_once (__DIR__ . "/../../library/hintinfo.php");

function ShowSelectTingkatSiswa()
{
    global $departemen, $idTingkat, $tingkat;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE departemen = '$departemen' 
                   AND aktif = 1 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);

        echo "<select id='tabsiswa_tingkat' onChange='tabsiswa_onTingkatChange()' class='inputbox' style='width:200px'>";
        while($row = mysqli_fetch_row($res))
        {
            if ($idTingkat == "")
            {
                $idTingkat = $row[0];
                $tingkat = $row[1];
            }
            $sel = $idTingkat == $row[0] ? "selected" : "";
            echo "<option value='$row[0]' $sel>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "ka20u");
    }
    finally
    {
        $db->Close();
    }
}

function ShowSelectKelasSiswa()
{
    global $idTingkat, $idKelas, $kelas;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT k.replid, k.kelas 
                  FROM jbsakad.kelas k, jbsakad.tahunajaran ta, jbsakad.tingkat ti 
                 WHERE k.idtahunajaran = ta.replid 
                   AND k.idtingkat = ti.replid 
                   AND ti.replid = '$idTingkat' 
                   AND k.aktif = 1 
                   AND ta.aktif = 1 
                 ORDER BY k.kelas";
        $res = $db->QueryDb($sql);

        echo "<select id='tabsiswa_kelas' onChange='tabsiswa_onKelasChange()' class='inputbox' style='width:200px'>";
        while($row = mysqli_fetch_row($res))
        {
            if ($idKelas == "")
            {
                $idKelas = $row[0];
                $kelas = $row[1];
            }
            $sel = $idKelas == $row[0] ? "selected" : "";
            echo "<option value='$row[0]' $sel>$row[1]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "kjukj");
    }
    finally
    {
        $db->Close();
    }
}

function ColumnColor($urut, $urutBy)
{
    return ($urut == $urutBy) ? "#fffc00" : "#25f2ff";
}

function ShowDaftarSiswa()
{
    global $departemen, $idTingkat, $tingkat, $idKelas, $kelas, $urut;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT s.nis, s.nama, k.kelas, s.replid, a.replid AS idangkatan, a.angkatan 
                  FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.angkatan a
                 WHERE s.aktif = 1 
                   AND s.alumni = 0
                   AND k.replid = s.idkelas
                   AND a.replid = s.idangkatan
                   AND k.replid = '$idKelas' 
                 ORDER BY $urut ASC";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            HintInfo::ShowLeft("Tidak ditemukan siswa");
            return;
        }

        echo "<table id='tabsiswa_table_pilih' class='tab' border='1' style='border-collapse:collapse' width='100%' align='center'>";
        echo "<tr align='center'>";
        echo "<td class='bg-table-header' width='7%'>No</td>";
        echo "<td class='bg-table-header' width='7%' align='center'>";
        echo "<input type='checkbox' name='tabsiswa_pilih_checkbox_all' id='tabsiswa_pilih_checkbox_all' onclick=\"tabsiswa_pilih_checkAll()\">";
        echo "</td>";
        $color = ColumnColor($urut, "s.nis");
        echo "<td class='bg-table-header' width='40%' style='cursor: pointer; color: $color' onclick=\"tabsiswa_changeUrut('daftar', 's.nis')\">";
        echo "NIS";
        echo "</td>";
        $color = ColumnColor($urut, "s.nama");
        echo "<td class='bg-table-header' width='*' style='cursor: pointer; color: $color' onclick=\"tabsiswa_changeUrut('daftar', 's.nama')\">";
        echo "Nama";
        echo "</td>";
        echo "</tr>";

        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            $cnt += 1;

            $data = new stdClass();
            $data->NIS = $row[0];
            $data->Nama = $row[1];
            $data->Replid = $row[3];
            $data->Departemen = $departemen;
            $data->IdTingkat = $idTingkat;
            $data->Tingkat = $tingkat;
            $data->IdKelas = $idKelas;
            $data->Kelas = $kelas;
            $data->IdAngkatan = $row[4];
            $data->Angkatan = $row[5];
            $data->Kelompok = "siswa";

            $json64 = base64_encode(json_encode($data));

            echo "<tr>";
            echo "<td class='numberColumn' align='center'>$cnt</td>";
            echo "<td align='center'>";
            echo "<input type='checkbox' name='tabsiswa_pilih_checkbox_$cnt' id='tabsiswa_pilih_checkbox_$cnt'>";
            echo "<input type='hidden' name='tabsiswa_pilih_data_$cnt' id='tabsiswa_pilih_data_$cnt' value='$json64'>";
            echo "<input type='hidden' name='tabsiswa_pilih_nis_$cnt' id='tabsiswa_pilih_nis_$cnt' value='$row[0]'>";
            echo "<input type='hidden' name='tabsiswa_pilih_nama_$cnt' id='tabsiswa_pilih_nama_$cnt' value='$row[1]'>";
            echo "</td>";
            echo "<td colspan='2' style='position: relative'>";
            echo "<span style='color: blue'>$row[0]</span>&nbsp;&nbsp;&nbsp;$row[1]<br>";
            echo "<span style='color: #666'> A: $row[5]</span>";
            echo "<span style='position: absolute; right: 5px; top: 5px;'>";
            echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='tabsiswa_profilSiswa(\"$row[0]\")'>&nbsp;&nbsp;";
            echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='tabsiswa_dashboardSiswa(\"$row[0]\")'>&nbsp;&nbsp;";
            
            echo "</span>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' name='tabsiswa_pilih_ndata' id='tabsiswa_pilih_ndata' value='$cnt'>";
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

function ShowCariSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $searchBy = $_REQUEST["searchby"];
        $search = $_REQUEST["search"];
        $urut = $_REQUEST["urut"];
        $departemen = $_REQUEST["departemen"];
        $idTingkat = $_REQUEST["idtingkat"];

        $sql = "SELECT s.replid, s.nis, s.nama, k.kelas, k.replid AS idkelas, t.tingkat, t.replid AS idtingkat,
                       s.idangkatan, a.angkatan
                  FROM jbsakad.siswa s, jbsakad.kelas k, jbsakad.tingkat t, jbsakad.angkatan a 
                 WHERE $searchBy LIKE '%$search%' 
                   AND k.replid = s.idkelas 
                   AND s.alumni = 0 
                   AND s.aktif = 1 
                   AND k.idtingkat = t.replid 
                   AND a.replid = s.idangkatan
                   AND t.departemen = '$departemen'
                   AND t.replid = '$idTingkat'
                 ORDER BY $urut ASC";
        $res = $db->QueryDb($sql);

        if (mysqli_num_rows($res) == 0)
        {
            HintInfo::ShowLeft("Tidak ditemukan siswa");
            return;
        }

        echo "<table id='tabsiswa_table_cari' class='tab' border='1' style='border-collapse:collapse' width='100%' align='center'>";
        echo "<tr style='height: 15px;' align='center'>";
        echo "<td class='bg-table-header' width='7%'>No</td>";
        echo "<td class='bg-table-header' width='7%' align='center'>";
        echo "<input type='checkbox' name='tabsiswa_cari_checkbox_all' id='tabsiswa_cari_checkbox_all' onclick=\"tabsiswa_cari_checkAll()\">";
        echo "</td>";
        $color = ColumnColor($urut, "s.nis");
        echo "<td class='bg-table-header' width='40%' style='cursor: pointer; color: $color;' onclick=\"tabsiswa_changeUrut('cari', 's.nis')\">";
        echo "NIS";
        echo "</td>";
        $color = ColumnColor($urut, "s.nama");
        echo "<td class='bg-table-header' width='*' style='cursor: pointer; color: $color;' onclick=\"tabsiswa_changeUrut('cari', 's.nama')\">";
        echo "Nama";
        echo "</td>";
        echo "</tr>";

        $cnt = 0;
        while($row = mysqli_fetch_array($res))
        {
            $cnt += 1;

            $data = new stdClass();
            $data->NIS = $row['nis'];
            $data->Nama = $row['nama'];
            $data->Replid = $row['replid'];
            $data->Departemen = $departemen;
            $data->IdTingkat = $row['idtingkat'];
            $data->Tingkat = $row['tingkat'];
            $data->IdKelas = $row['idkelas'];
            $data->Kelas = $row['kelas'];
            $data->IdAngkatan = $row['idangkatan'];
            $data->Angkatan = $row['angkatan'];
            $data->Kelompok = "siswa";

            $json64 = base64_encode(json_encode($data));
            $nis = $row["nis"];
            $nama = $row["nama"];

            echo "<tr>";
            echo "<td class='numberColumn' align='center'>$cnt</td>";
            echo "<td align='center'>";
            echo "<input type='checkbox' name='tabsiswa_cari_checkbox_$cnt' id='tabsiswa_cari_checkbox_$cnt'>";
            echo "<input type='hidden' name='tabsiswa_cari_data_$cnt' id='tabsiswa_cari_data_$cnt' value='$json64'>";
            echo "<input type='hidden' name='tabsiswa_cari_nis_$cnt' id='tabsiswa_cari_nis_$cnt' value='$nis'>";
            echo "<input type='hidden' name='tabsiswa_cari_nama_$cnt' id='tabsiswa_cari_nama_$cnt' value='$nama'>";
            echo "</td>";
            echo "<td colspan='2' style='position: relative;'>";
            echo "<span><span style='color: blue'>$nis</span>&nbsp;&nbsp;&nbsp;$nama</span><br>";
            echo "<span style='color: #666'> A: $row[angkatan]&nbsp;&nbsp;&nbsp;T: $row[tingkat]&nbsp;&nbsp;&nbsp;K: $row[kelas]</span>";
            echo "<span style='position: absolute; right: 5px; top: 5px;'>";
            echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='tabsiswa_profilSiswa(\"$row[0]\")'>&nbsp;&nbsp;";
            echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='tabsiswa_dashboardSiswa(\"$row[0]\")'>&nbsp;&nbsp;";
            echo "</span>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='hidden' name='tabsiswa_cari_ndata' id='tabsiswa_cari_ndata' value='$cnt'>";

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
