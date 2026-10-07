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
function ShowSelectTingkat($db)
{
    global $departemenRef, $idTingkat;

    try
    {
        $sql = "SELECT replid, tingkat 
                  FROM jbsakad.tingkat 
                 WHERE aktif = 1 
                   AND departemen = '$departemenRef' 
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tingkat' onchange='onChangeTingkat()' class='inputbox' style='width:170px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTingkat == "") 
                $idTingkat = $row['replid'];
            
            $sel = $idTingkat == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[tingkat]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k4pqj");
    }
}

function ShowSelectKelas($db)
{
    global $idTingkat, $idKelas, $idTahunAjaran, $idKelasRef;

    try
    {
        $sql = "SELECT replid, kelas 
                  FROM jbsakad.kelas 
                 WHERE aktif = 1 
                   AND idtingkat = '$idTingkat'
                   AND idtahunajaran = '$idTahunAjaran'
                 ORDER BY kelas";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kelas' onchange='onChangeKelas()' class='inputbox' style='width:170px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idKelas == "") 
                $idKelas = $row['replid'];
            
            $sel = $idKelas == $row['replid'] ? "selected" : "";
            echo "<option value='$row[replid]' $sel>$row[kelas]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }
}

function ShowSelectTahunAjaran($db)
{
    global $departemenRef, $idTahunAjaran;

    try
    {
        $sql = "SELECT replid, tahunajaran, aktif 
                  FROM jbsakad.tahunajaran 
                 WHERE departemen = '$departemenRef' 
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        
        echo "<select id='tahunajaran' onchange='onChangeTahunAjaran()' class='inputbox' style='width:170px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idTahunAjaran == "") 
                $idTahunAjaran = $row['replid'];
            
            $aktif = $row['aktif'] == 1 ? " (Aktif)" : "";
            $sel = $idTahunAjaran == $row['replid'] ? "selected" : "";

            echo "<option value='$row[replid]' $sel>$row[tahunajaran] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
    }
}

function ShowSelectKategori($db)
{
    global $idTahunAjaran, $idKategori, $idKategoriRef;

    try
    {
        $sql = "SELECT replid, deskripsi, aktif 
                  FROM jbsakad.infojadwal
                 WHERE idtahunajaran = '$idTahunAjaran' 
                   AND replid <> '$idKategoriRef'
                 ORDER BY aktif DESC, replid DESC";
        $res = $db->QueryDb($sql);
        
        echo "<select id='kategori' onchange='onChangeKategori()' class='inputbox' style='width:250px'>";
        while ($row = mysqli_fetch_array($res)) 
        {
            if ($idKategori == "") 
                $idKategori = $row['replid'];
            
            $aktif = $row['aktif'] == 1 ? " (Aktif)" : "";
            $sel = $idKategori == $row['replid'] ? "selected" : "";

            echo "<option value='$row[replid]' $sel>$row[deskripsi] $aktif</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kxnau");
    }
}

function SalinJadwal()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemenRef = RequestData("departemenref", "");
        $idKelasRef = RequestData("idkelasref", "");
        $kelasRef = RequestData("kelasref", "");
        $idKategoriRef = RequestData("idkategoriref", "");
        $kategoriRef = RequestData("kategoriref", "");
        $idTahunAjaran = RequestData("idtahunajaran", "");
        $idTingkat = RequestData("idtingkat", "");
        $idKategori = RequestData("idkategori", "");
        $kategori = RequestData("kategori", "");
        $idKelas = RequestData("idkelas", "");

        $stIdInfo = "";
        $sql = "SELECT GROUP_CONCAT(replid) 
                  FROM jbsakad.infojadwal
                 WHERE aktif = 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $stIdInfo = $row[0];
        
        if ($stIdInfo == "")
            return json_encode([-1, "Belum ada kategori jadwal yang aktif. Buat dahulu di menu Kategori Jadwal"]);

        $sql = "SELECT j.nipguru, j.idpelajaran, j.hari, j.jamke, j.njam, j.keterangan, j.status
                  FROM jbsakad.jadwal j
                 WHERE j.idkelas = '$idKelas'
                   AND j.departemen = '$departemenRef'
                   AND j.infojadwal = '$idKategori'";
        $res = $db->QueryDb($sql);
        $lsJadwal = [];
        while ($row = mysqli_fetch_assoc($res))
        {
            $lsJadwal[] = $row;
        }

        $sql = "SELECT replid, jamke, TIME_FORMAT(jam1, '%H:%i') AS jam1 
                  FROM jbsakad.jam 
                 WHERE departemen = '$departemenRef'";	
        $res = $db->QueryDb($sql);
        $lsJam = [];
        while ($row = mysqli_fetch_assoc($res))
        {
            $lsJam[$row['jamke']] = [ $row['replid'], $row['jam1'] ];
        }

        $dayname = array("", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu");

        $db->BeginTrans();

        $lsBentrok = [];
        for ($p = 0; $p < count($lsJadwal); $p++)
        {
            $row = $lsJadwal[$p];
            $nipGuru = $row['nipguru'];
            $idPelajaran = $row['idpelajaran'];
            $hari = $row['hari'];
            $jamKe = $row['jamke'];
            $nJam = $row['njam'];
            $keterangan = $row['keterangan'];
            $status = $row['status'];

            $jam1 = $jamKe;
            $jam2 = $jamKe + $nJam - 1;
            
            $sqljam = "";
            for($i = $jam1; $i <= $jam2; $i++)
            {
                if (strlen($sqljam) != 0)
                    $sqljam .= " OR ";
                $sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
            }

            // Cek jadwal guru yang bentrok di kelas lain
            $sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2 
                      FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
                     WHERE ij.replid = j.infojadwal 
                       AND j.nipguru = pg.nip 
                       AND j.idpelajaran = p.replid 
                       AND j.idkelas = k.replid 
                       AND ij.replid IN ($stIdInfo) 
                       AND hari = '$hari' 
                       AND nipguru = '$nipGuru' 
                       AND ($sqljam)";
            $res = $db->QueryDb($sql);
            if (mysqli_num_rows($res) > 0) 
            {
                while ($row = mysqli_fetch_array($res))
                {
                    $ket = $row['departemen'] . ", kelas " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'];  	
                    $lsBentrok[] = $ket;
                }
            } 
            else 
            {
                 // Cek bentrok di kelas yang sama
                $sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2 
                          FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
                         WHERE ij.replid = j.infojadwal 
                           AND j.nipguru = pg.nip 
                           AND j.idpelajaran = p.replid 
                           AND j.idkelas = k.replid 
                           AND ij.replid IN ($stIdInfo)
                           AND idkelas = '$idKelasRef' 
                           AND hari = '$hari'
                           AND ($sqljam)";
                $res = $db->QueryDb($sql);
                if (mysqli_num_rows($res) > 0) 
                {
                    while ($row = mysqli_fetch_array($res))
                    {
                        $ket = $row['departemen'] . ", kelas " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'];  	
                        $lsBentrok[] = $ket;
                    }
                } 
                else 
                {
                    $idJam1 = $lsJam[$jam1][0];
                    $idJam2 = $lsJam[$jam2][0];

                    $jamStr1 = $lsJam[$jam1][1];
                    $jamStr2 = $lsJam[$jam2][1];

                    $sql = "INSERT INTO jbsakad.jadwal 
                            SET idkelas = '$idKelasRef', 
                                nipguru = '$nipGuru', 
                                idpelajaran = '$idPelajaran', 
                                departemen = '$departemenRef', 
                                infojadwal = '$idKategoriRef', 
                                hari = '$hari', 
                                jamke = '$jam1', 
                                njam = '$nJam', 
                                sifat = 1, 
                                `status` = '$status', 
                                keterangan = '$keterangan', 
                                jam1 = '$jamStr1', 
                                jam2 = '$jamStr2', 
                                idjam1 = '$idJam1', 
                                idjam2 = '$idJam2'";
                    $res = $db->QueryDb($sql);    
                }
            }
        }

        $db->CommitTrans();

        $nBentrok = count($lsBentrok);
        $data64 = base64_encode(json_encode($lsBentrok));
        
        return json_encode([1, $nBentrok, $data64]);
    }
    catch (Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>
