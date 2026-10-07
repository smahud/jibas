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
function GetRataSiswa2($db, $idPelajaran, $idJenis, $idKelas, $idSemester, $idAturan, $nis)
{
	$sql = "SELECT rataUS 
              FROM jbsakad.rataus 
		     WHERE idsemester ='$idSemester' 
               AND idkelas ='$idKelas' 
               AND idjenis = '$idJenis' 
			   AND idpelajaran ='$idPelajaran' 
               AND idaturan = '$idAturan' 
               AND nis = '$nis'";
	$res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
        return round($row[0], 2);

    return 0;
}

function GetRataKelas($db, $idKelas, $idSemester, $idUjian)
{
	$sql = "SELECT nilaiRK 
              FROM jbsakad.ratauk 
             WHERE idsemester = '$idSemester' 
               AND idkelas = '$idKelas' 
               AND idujian = '$idUjian'";
	$res = $db->QueryDb($sql);
	if ($row = mysqli_fetch_row($res))
        return round($row[0], 2);

    return 0;
}

function HitungRataSiswa($db, $idKelas, $idSemester, $idAturan, $nis)
{
    $sql = "SELECT GROUP_CONCAT(replid) 
			  FROM jbsakad.ujian 
			 WHERE idkelas = '$idKelas' 
               AND idsemester = '$idSemester' 
               AND idaturan = '$idAturan'";    
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $idUjian = $row[0];
    if ($idUjian == "")
        $idUjian = 0;

    $sql = "SELECT SUM(nilaiujian), COUNT(replid)
              FROM jbsakad.nilaiujian 
             WHERE idujian IN ($idUjian) 
               AND nis = '$nis'";
    $res = $db->QueryDb($sql);
    $totalNilai = 0;
    $jumlahNilai = 0;
    if ($row = mysqli_fetch_row($res))
    {
        $totalNilai = $row[0];
        $jumlahNilai  = $row[1];
    }

    $rataUs = 0;
    if ($jumlahNilai > 0)
	    $rataUs = round($totalNilai / $jumlahNilai, 2);
	
	$sql = "SELECT idpelajaran, idjenisujian 
              FROM jbsakad.aturannhb 
             WHERE replid = '$idAturan'";
	$res = $db->QueryDb($sql);
	$row = mysqli_fetch_row($res);
	$idPelajaran = $row[0];
	$idJenis = $row[1];

    $sql = "SELECT replid 
              FROM jbsakad.rataus 
		     WHERE idsemester = '$idSemester' 
               AND idkelas = '$idKelas' 
               AND idjenis = '$idJenis' 
			   AND idpelajaran = '$idPelajaran' 
               AND idaturan = '$idAturan' 
               AND nis = '$nis'";
	$result = $db->QueryDb($sql);
	$num = mysqli_num_rows($result);
	if ($num == 0)
    {
		$sql = "INSERT INTO jbsakad.rataus 
                   SET idsemester = '$idSemester', idkelas = '$idKelas', idjenis = '$idJenis', 
			           idpelajaran = '$idPelajaran', idaturan = '$idAturan', nis = '$nis', rataUS = '$rataUs'";
    }
	else 
    {
        $row = mysqli_fetch_row($result);
        $idRataUs = $row[0];

		$sql = "UPDATE jbsakad.rataus 
                   SET rataUS = '$rataUs' 
                 WHERE replid = '$idRataUs'";
	}
    $db->QueryDb($sql);
}

function HitungRataKelasUjian($db, $idKelas, $idSemester, $idUjian)
{
    $sql = "SELECT SUM(nilaiujian), COUNT(replid)
              FROM jbsakad.nilaiujian 
             WHERE idujian = '$idUjian'";
    $res = $db->QueryDb($sql);
    $totalNilai = 0;
    $jumNilai = 0;
    if ($row = mysqli_fetch_row($res))
    {
        $totalNilai = $row[0];
        $jumNilai   = $row[1];
    }

    $rataUk = 0;
    if ($jumNilai > 0)
        $rataUk = round($totalNilai / $jumNilai, 2);

    $sql = "SELECT replid 
              FROM jbsakad.ratauk 
             WHERE idsemester = '$idSemester' 
               AND idkelas = '$idKelas' 
               AND idujian = '$idUjian'";
    $result = $db->QueryDb($sql);
    $num = mysqli_num_rows($result);
    if ($num == 0)
    {
        $sql = "INSERT INTO jbsakad.ratauk 
                   SET idsemester = '$idSemester', idkelas = '$idKelas', idujian = '$idUjian', nilairk = '$rataUk'";
    }
    else
    {
        $row = mysqli_fetch_row($result);
        $idRataUk = $row[0];

        $sql = "UPDATE jbsakad.ratauk 
                   SET nilairk = '$rataUk' 
                 WHERE replid = '$idRataUk'";
    }
    $db->QueryDb($sql);
}
?>