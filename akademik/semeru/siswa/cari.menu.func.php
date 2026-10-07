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
function ShowSelectDepartemen($db)
{
    global $departemen;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:250px' class='inputbox'>";
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
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowTahunAjaranAktif($db)
{
    global $departemen, $idTahunAjaran, $tahunAjaran;

    try
    {
        $sql = "SELECT replid, tahunajaran
                  FROM jbsakad.tahunajaran
                 WHERE departemen = '$departemen'
                   AND aktif = 1";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) <= 0)
        {
            echo "<input type='text' id='tahunajaran' class='inputbox inputbox-readonly' readonly style='width 150px' value='belum ada data tahun ajaran'>";
            echo "<input type='hidden' id='idtahunajaran' value='0'>";
            return;
        }

        $row = mysqli_fetch_row($res);
        $idTahunAjaran = $row[0];
        $tahunAjaran = $row[1];
        echo "<input type='text' id='tahunajaran' class='inputbox inputbox-readonly' readonly style='width 150px' value='$tahunAjaran'>";
        echo "<input type='hidden' id='idtahunajaran' value='$idTahunAjaran'>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectTingkat($db)
{
    global $departemen, $idTingkat;

    try
    {
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE aktif = 1 
                   AND departemen = '$departemen' 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tingkat' onchange='onChangeTingkat()' class='inputbox' style='width:150px'>";
        $sel = $idTingkat == 0 ? "selected" : "";
        echo "<option value='0'>Semua Tingkat</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            $sel = $idTingkat == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectKelas($db)
{
    global $idTingkat, $idTahunAjaran;

    try
    {
        if ($idTingkat == 0)
        {
            echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:250px'>";
            echo "<option value='0'>Semua Kelas</option>";
            echo "</select>";
            return;
        }
        
        $sql = "SELECT replid, kelas 
                  FROM jbsakad.kelas
                 WHERE aktif = 1 
                   AND idtingkat = '$idTingkat' 
                   AND idtahunajaran = '$idTahunAjaran' 
                 ORDER BY kelas";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:250px'>";
        echo "<option value='0'>Semua Kelas</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            echo "<option value='$row[replid]'>$row[kelas]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowSelectJenisCari()
{
    global $jenisCari;

    $arrJenisCari = array(
        array("nis","NIS"),
        array("nisn","NISN"),
        array("nik","NIK"),
        array("nama","Nama"),
        array("panggilan","Nama Panggilan"),
        array("aktif","Aktif"),
        array("asalsekolah","Asal Sekolah"),
        array("pinsiswa","PIN Siswa"),
        array("agama","Agama"),
        array("suku","Suku"),
        array("status","Status Siswa"),
        array("kondisi","Kondisi Siswa"),
        array("kelamin","Jenis Kelamin"),
        array("darah","Golongan Darah"),
        array("alamat","Alamat Siswa & Ortu"),
        array("jarak","Jarak Ke Sekolah"),
        array("hp","HP Siswa & Ortu"),
        array("email","Email Siswa & Ortu"),
        array("namaortu","Nama Ortu"),
        array("penghasilan","Penghasilan Ortu"),
        array("pendidikan","Pendidikan Ortu"));

    asort($arrJenisCari);

    echo "<select id='jeniscari' onchange='onChangeJenisCari()' class='inputbox' style='width:150px'>";
    foreach ($arrJenisCari as $value)
    {
        if ($jenisCari == "")
            $jenisCari = $value[0];
        $sel = $jenisCari == $value[0] ? "selected" : "";
        echo "<option value='$value[0]' $sel>$value[1]</option>";
    }
    echo "</select>";
}

function ShowSelectCari()
{
    global $jenisCari;

    try
    {
        if ($jenisCari == 'kondisi' || $jenisCari == 'status' || $jenisCari == 'agama' || $jenisCari == 'suku' || $jenisCari == 'pendidikan')
        {
            $db = new Db();
            $db->Open();

            if ($jenisCari == 'kondisi') 
                $sql = "SELECT kondisi FROM jbsakad.kondisisiswa ORDER BY kondisi ";			
    		elseif ($jenisCari == 'status') 
			    $sql = "SELECT status FROM jbsakad.statussiswa ORDER BY status ";
            elseif ($jenisCari == 'agama') 
    		    $sql = "SELECT agama FROM jbsumum.agama ORDER BY urutan";
            elseif ($jenisCari == 'suku') 
			    $sql = "SELECT suku FROM jbsumum.suku ORDER BY suku";
            elseif ($jenisCari == 'pendidikan') 
			    $sql = "SELECT pendidikan FROM jbsumum.tingkatpendidikan ORDER BY urutan";

            $res = $db->QueryDb($sql);
            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            while ($row = mysqli_fetch_array($res))
            {
                echo "<option value='$row[0]'>$row[0]</option>";
            }
            echo "<option value='---'>Tidak Diketahui</option>";
            echo "</select>";

            $db->Close();
		}
        else if ($jenisCari == 'darah') 
        {
            $arrData["A"] = "A";
            $arrData["B"] = "B";
            $arrData["AB"] = "AB";
            $arrData["O"] = "O";
            
            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            foreach ($arrData as $key => $value)
            {
                echo "<option value='$key'>$value</option>";
            }
            echo "<option value='---'>Tidak Diketahui</option>";
            echo "</select>";    
        }
        else if ($jenisCari == 'kelamin') 
        {
            $arrData["l"] = "Laki-laki";
            $arrData["p"] = "Perempuan";
            
            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            foreach ($arrData as $key => $value)
            {
                echo "<option value='$key'>$value</option>";
            }
            echo "<option value='---'>Tidak Diketahui</option>";
            echo "</select>";    
        }
        else if ($jenisCari == "jarak")
        {
            $arrData["j1"] = "< 5 km";
            $arrData["j2"] = "5 - 10 km";
            $arrData["j3"] = "10 - 15 km";
            $arrData["j4"] = "> 15 km";
            
            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            foreach ($arrData as $key => $value)
            {
                echo "<option value='$key'>$value</option>";
            }
            echo "<option value='---'>Tidak Diketahui</option>";
            echo "</select>";
        }
        else if ($jenisCari == "aktif")
        {
            $arrAktif = array("Aktif", "Tidak Aktif");
            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            foreach ($arrAktif as $value)
            {
                echo "<option value='$value'>$value</option>";
            }
            echo "</select>";
        }
        else if ($jenisCari == "penghasilan")
        {
            $arrData["p1"] = "< Rp 2.000.000";
            $arrData["p2"] = "Rp 2.000.000 - Rp 5.000.000";
            $arrData["p3"] = "Rp 5.000.000 - Rp 10.000.000";
            $arrData["p4"] = "> Rp 10.000.000";

            echo "<select id='cari' onchange='onChangeCari()' class='inputbox' style='width:250px'>";
            foreach ($arrData as $key => $value)
            {
                echo "<option value='$key'>$value</option>";
            }
            echo "<option value='---'>Tidak Diketahui</option>";
            echo "</select>";
        }
        else 
        {
            echo "<input type='text' class='inputbox' id='cari' style='width:250px'>";
        }
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }


}
?>