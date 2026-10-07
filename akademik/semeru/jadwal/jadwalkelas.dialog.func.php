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
function LoadJadwalKelas($db)
{
    global $replid;
    global $idpelajaran, $hari, $nip, $nama, $jam, $jam2, $jum, $status, $keterangan, $jamorig1, $jamorig2;

    $sql = "SELECT *, j.keterangan AS keterangan, idtahunajaran, idtingkat, t.tahunajaran, p.nama AS guru
		      FROM jbsakad.jadwal j, jbsakad.kelas k, jbsakad.tahunajaran t, jbssdm.pegawai p
		     WHERE j.replid = '$replid' 
               AND j.idkelas = k.replid 
               AND k.idtahunajaran = t.replid 
               AND p.nip = j.nipguru";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res)) 
    {
        $hari = $row['hari'];
        $nip = $row['nipguru'];
        $nama = $row['guru'];
        $jam = $row['jamke'];
        $jam2 = $row['njam'] + $jam - 1;
        $jum = $jam2 - $jam + 1;
        $status = $row['status'];
        $keterangan = $row['keterangan'];
        $jamorig1 = $row['jamke'];
        $jamorig2 = $row['njam'] + $row['jamke'] - 1;
        $idpelajaran = $row['idpelajaran'];
    }

}

function ShowSelectPelajaran($db)
{
    global $departemen, $idpelajaran;

    $sql = "SELECT replid, nama 
              FROM jbsakad.pelajaran 
             WHERE departemen = '$departemen' 
               AND aktif = 1 
             ORDER BY nama";
    $res = $db->QueryDb($sql);

    echo "<select id='pelajaran' class='inputbox' style='width: 300px;'>";
    while($row = mysqli_fetch_row($res))                        
    {
        if ($idpelajaran == 0)
            $idpelajaran = $row[0];
            
        $selected = ($row[0] == $idpelajaran) ? "selected" : "";

        echo "<option value='$row[0]' $selected>$row[1]</option>";
    }
    echo "</select>";
}

function ShowSelectStatus()
{
    global $status;

    echo "<select id='status' class='inputbox' style='width: 150px;'>";
    $sel = ($status == 0) ? "selected" : "";
    echo "<option value='0' $sel>Mengajar</option>";
    $sel = ($status == 1) ? "selected" : "";
    echo "<option value='1' $sel>Asistensi</option>";
    $sel = ($status == 2) ? "selected" : "";
    echo "<option value='2' $sel>Tambahan</option>";
    echo "</select>";
}

function SimpanBaru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $idTingkat = RequestData("idtingkat", 0);
        $idKelas = RequestData("idkelas", 0);
        $idTahunAjaran = RequestData("idtahunajaran", 0);
        $idKategori = RequestData("idkategori", 0);
        $idPelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $jam1 = RequestData("jam1", 0);
        $jam2 = RequestData("jam2", 0);
        $hari = RequestData("hari", 0);
        $status = RequestData("status", 0);
        $keterangan = RequestData("keterangan", "");

        $stIdInfo = "";
        $sql = "SELECT GROUP_CONCAT(replid) 
                  FROM jbsakad.infojadwal
                 WHERE aktif = 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $stIdInfo = $row[0];
        
        if ($stIdInfo == "")
            return json_encode([-1, "Belum ada kategori jadwal yang aktif. Buat dahulu di menu Kategori Jadwal"]);

        // Cek jadwal guru yang bentrok di kelas lain
		$sqljam = "";
		for($i = $jam1; $i <= $jam2; $i++)
		{
			if (strlen($sqljam) != 0)
				$sqljam .= " OR ";
			$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
		}
		
        $dayname = array("", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu");

		$sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2 
				  FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
				 WHERE ij.replid = j.infojadwal 
                   AND j.nipguru = pg.nip 
                   AND j.idpelajaran = p.replid 
			 	   AND j.idkelas = k.replid 
                   AND ij.replid IN ($stIdInfo) 
				   AND hari = '$hari' 
                   AND nipguru = '$nip' 
				   AND ($sqljam)";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0) 
		{
			$ket = "Jadwal yang bentrok dengan guru di kelas lain:<br>";
			while ($row = mysqli_fetch_array($res))
			{
				$ket .= "&bull; " . $row['departemen'] . ", kelas " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'] . "<br>";  	
			}
			return json_encode([0, $ket]);
		} 

        // Cek bentrok di kelas yang sama
        $sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2 
                  FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
                 WHERE ij.replid = j.infojadwal 
                   AND j.nipguru = pg.nip 
                   AND j.idpelajaran = p.replid 
                   AND j.idkelas = k.replid 
                   AND ij.replid IN ($stIdInfo)
                   AND idkelas = '$idKelas' 
                   AND hari = '$hari'
                   AND ($sqljam)";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0) 
		{
			$ket = "Jadwal yang bentrok di kelas yang sama:<br>";
			while ($row = mysqli_fetch_array($res))
			{
				$ket .= "&bull; " . $row['departemen'] . ", kelas " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'] . "<br>";  	
			}
			return json_encode([0, $ket]);
		} 

        $sql = "SELECT replid, TIME_FORMAT(jam1, '%H:%i') AS jam1 
                  FROM jbsakad.jam 
                 WHERE departemen = '$departemen' 
                   AND jamke = '$jam1'";	
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_array($res);
        $rep1 = $row['replid'];
        $jm1 = $row['jam1'];
			
        $sql = "SELECT replid, TIME_FORMAT(jam2, '%H:%i') AS jam2 
                  FROM jbsakad.jam 
                 WHERE departemen = '$departemen' 
                   AND jamke = '$jam2'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_array($res);
        $rep2 = $row['replid'];
        $jm2 = $row['jam2'];
			
        $jum = $jam2 - $jam1 + 1;

        $sql = "INSERT INTO jbsakad.jadwal 
                   SET idkelas = '$idKelas', 
                       nipguru = '$nip', 
                       idpelajaran = '$idPelajaran', 
                       departemen = '$departemen', 
                       infojadwal = '$idKategori', 
                       hari = '$hari', 
                       jamke = '$jam1', 
                       njam = '$jum', 
                       sifat = 1, 
                       `status` = '$status', 
                       keterangan = '$keterangan', 
                       jam1 = '$jm1', 
                       jam2 = '$jm2', 
                       idjam1 = '$rep1', 
                       idjam2 = '$rep2'";
        $db->QueryDb($sql);                       

        return json_encode([1, "OK"]);
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


function SimpanEdit()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $departemen = RequestData("departemen", "");
        $idTingkat = RequestData("idtingkat", 0);
        $idKelas = RequestData("idkelas", 0);
        $idTahunAjaran = RequestData("idtahunajaran", 0);
        $idKategori = RequestData("idkategori", 0);
        $idPelajaran = RequestData("idpelajaran", 0);
        $nip = RequestData("nip", "");
        $jam1 = RequestData("jam1", 0);
        $jam2 = RequestData("jam2", 0);
        $jamorig1 = RequestData("jamorig1", 0);
        $jamorig2 = RequestData("jamorig2", 0);
        $hari = RequestData("hari", 0);
        $status = RequestData("status", 0);
        $keterangan = RequestData("keterangan", "");

        $stIdInfo = "";
        $sql = "SELECT GROUP_CONCAT(replid) 
                  FROM jbsakad.infojadwal 
                 WHERE aktif = 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $stIdInfo = $row[0];
        
        if ($stIdInfo == "")
            return json_encode([-1, "Belum ada kategori jadwal yang aktif. Buat dahulu di menu Kategori Jadwal"]);

        $dayname = array("", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu", "Minggu");


        // -- Cek jadwal guru di kelas yang lain
		$sqljam = "";
		for($i = $jam1; $i <= $jam2; $i++)
		{
			if ($sqljam != "")
				$sqljam .= " OR ";
				
			if ($i >= $jamorig1 && $i <= $jamorig2)	
				$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1 AND j.replid <> $replid)";
			else			
				$sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
		}

        $sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2
				  FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
				 WHERE ij.replid = j.infojadwal 
                   AND j.nipguru = pg.nip 
                   AND j.idpelajaran = p.replid 
				   AND j.idkelas = k.replid 
                   AND ij.replid IN ($stIdInfo) 
				   AND hari = '$hari' 
                   AND nipguru = '$nip' 
				   AND ($sqljam)";
        $res = $db->QueryDb($sql);
		if (mysqli_num_rows($res) > 0) 
		{
			$ket = "Jadwal yang bentrok di kelas lain:<br>";
			while ($row = mysqli_fetch_array($res))
			{
				$ket .= "&bull;&nbsp;" . $row['departemen'] . " " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'] . "<br>";  	
			}
			return json_encode([0, $ket]);
		} 

        // Cek bentrok di kelas yang sama
        $sqljam = "";
        for($i = $jam1; $i <= $jam2; $i++)
        {
            if ($sqljam != "")
                $sqljam .= " OR ";
                
            if ($i >= $jamorig1 && $i <= $jamorig2)	
                $sqljam .= "($i >= jamke AND $i <= jamke + njam - 1 AND nipguru <> '$nip')";
            else			
                $sqljam .= "($i >= jamke AND $i <= jamke + njam - 1)";
        }

        $sql = "SELECT ij.deskripsi, pg.nama, p.nama AS pelajaran, k.kelas, j.departemen, j.hari, j.jamke AS jam1, j.jamke + j.njam - 1 AS jam2
                  FROM jbsakad.infojadwal ij, jbsakad.jadwal j, jbssdm.pegawai pg, jbsakad.pelajaran p, jbsakad.kelas k 
                 WHERE ij.replid = j.infojadwal 
                   AND j.nipguru = pg.nip 
                   AND j.idpelajaran = p.replid 
                   AND j.idkelas = k.replid 
                   AND ij.replid IN ($stIdInfo) 
                   AND hari = '$hari'   
                   AND idkelas = '$idKelas' 
                   AND ($sqljam)";
        $res = $db->QueryDb($sql);
		if (mysqli_num_rows($res) > 0) 
		{
			$ket = "Jadwal yang bentrok di kelas yang sama:<br>";
			while ($row = mysqli_fetch_array($res))
			{
				$ket .= "&bull;&nbsp;" . $row['departemen'] . " " . $row['kelas'] . ", " . $row['deskripsi'] . ", " . $row['nama'] . ", " .  $row['pelajaran'] . ", " . $dayname[$row['hari']] . " jam " . $row['jam1'] . " s/d "  . $row['jam2'] . "<br>";  	
			}
			return json_encode([0, $ket]);
		} 

        $sql1 = "SELECT replid, TIME_FORMAT(jam1, '%H:%i') AS jam1 
                   FROM jbsakad.jam 
                  WHERE departemen = '$departemen' 
                    AND jamke = '$jam1'";	
        $res = $db->QueryDb($sql1);
        $row = mysqli_fetch_array($res);
        $rep1 = $row['replid'];
        $jm1 = $row['jam1'];
			
        $sql2 = "SELECT replid, TIME_FORMAT(jam2, '%H:%i') AS jam2 
                   FROM jbsakad.jam 
                  WHERE departemen = '$departemen' 
                    AND jamke = '$jam2'";	
        $res = $db->QueryDb($sql2);
        $row = mysqli_fetch_array($res);
        $rep2 = $row['replid'];
        $jm2 = $row['jam2'];

        $jum = $jam2 - $jam1 + 1;
		
        $sql = "UPDATE jbsakad.jadwal 
                   SET idkelas = '$idKelas', 
                       nipguru = '$nip', 
                       idpelajaran = '$idPelajaran', 
                       departemen = '$departemen', 
                       infojadwal = '$idKategori', 
                       hari = '$hari', 
                       jamke = '$jam1', 
                       njam = '$jum', 
                       sifat = 1, 
                      `status` = '$status', 
                       keterangan = '$keterangan', 
                       jam1 = '$jm1', 
                       jam2 = '$jm2', 
                       idjam1 = '$rep1', 
                       idjam2 = '$rep2'
                 WHERE replid = '$replid'";
        $res = $db->QueryDb($sql);

        return json_encode([1, "OK"]);
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