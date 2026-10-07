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
function ShowSelectPelajaran($db)
{
    global $nip, $departemen, $idTingkat, $idPelajaran;

    $sql= "SELECT DISTINCT aturannhb.idpelajaran, pelajaran.nama 
			 FROM jbsakad.aturannhb aturannhb, jbsakad.pelajaran pelajaran 
			WHERE aturannhb.nipguru = '$nip' 
			  AND idpelajaran=pelajaran.replid 
			  AND pelajaran.departemen='$departemen' 
			  AND aturannhb.idtingkat='$idTingkat' 
			  AND aturannhb.aktif = 1 
   	        ORDER BY pelajaran.nama";
    $res = $db->QueryDb($sql);

    if (mysqli_num_rows($res) == 0)
    {
        $msg  = "Belum tersedia data <b>Pelajaran</b> atau <b>Aturan Perhitungan Nilai Rapor</b> untuk guru terpilih.";
        $msg .= "<br><br>Pastikan telah mendata <b>Pelajaran</b> yang diajar oleh guru terpilih di menu <b>Guru &amp; Pelajaran</b>";
        $msg .= "<br><br>Tentukan juga <b>Aturan Perhitungan Nilai Rapor</b> untuk pelajaran yang diajar oleh guru terpilih";
        HintInfo::ShowLeft($msg, 250);
        return;
    }

    echo "<select id='pelajaran' onchange='onChangePelajaran()' class='inputbox' style='width:240px'>";
    while ($row = mysqli_fetch_array($res)) 
    {
        if ($idPelajaran == 0)
            $idPelajaran = $row['idpelajaran'];

        $sel = $idPelajaran == $row['idpelajaran'] ? "selected" : "";
        echo "<option value='$row[idpelajaran]' $sel>$row[nama]</option>";
    }
    echo "</select>";
}

function ShowJenisUjian($db)
{
    global $nip, $idPelajaran, $idTingkat, $idKelas, $idSemester;

    $lsAspek = [];
    $sql = "SELECT DISTINCT a.dasarpenilaian, dp.keterangan 
              FROM jbsakad.aturannhb a, jbsakad.dasarpenilaian dp
             WHERE idpelajaran = '$idPelajaran' 
               AND a.dasarpenilaian = dp.dasarpenilaian
               AND dp.aktif = 1 
               AND idtingkat = '$idTingkat' 
               AND nipguru = '$nip' 
             ORDER BY keterangan";
    $res = $db->QueryDb($sql);
    while ($row = mysqli_fetch_array($res))
    {
        $lsAspek[] = [ $row['dasarpenilaian'], $row['keterangan'] ];
    }

    if (count($lsAspek) == 0)
        return;
    
    echo "<div style='width: 98%; position: relative; padding: 5px 2px;'>";
    echo "<b>Jenis Ujian:</b>";
    echo "<span onclick='onChangePelajaran()' class='cur-hand fg-secondary' style='position: absolute; right: 5px; top: 5px;'>muat ulang</span>";
    echo "</div>";
    $nTable = 0;
    for($i = 0; $i < count($lsAspek); $i++)
    {
        $nTable++;
        
        $aspek = $lsAspek[$i][0];
        $namaAspek = $lsAspek[$i][1];

        echo "<table class='tab tabShadow' id='table$nTable' border='1' width='100%' align='left' cellpadding='3'>";
        echo "<tr height='25' class='bg-table-header' align='left'>";
        echo "<td colspan='2'><b>$namaAspek</b></td>";
        echo "</tr>";
        
        $sql = "SELECT j.replid AS idjenisujian, j.jenisujian, a.replid 
			      FROM jbsakad.aturannhb a, jbsakad.jenisujian j 
				 WHERE a.idpelajaran = '$idPelajaran' 
				   AND a.dasarpenilaian = '$aspek' 
				   AND a.idjenisujian = j.replid 
				   AND a.idtingkat = '$idTingkat' 
				   AND a.nipguru = '$nip' 
			 	   AND a.aktif = 1
				 ORDER BY j.urutan, j.jenisujian";
        $res = $db->QueryDb($sql);
        while ($row = mysqli_fetch_array($res))
        {
            $idJenisUjian = $row['idjenisujian'];
            $jenisUjian = $row['jenisujian'];
            $idAturanNhb = $row['replid'];
            
            $sql = "SELECT COUNT(u.replid)
                      FROM jbsakad.ujian u
                     WHERE u.idaturan='$idAturanNhb' 
                       AND u.idkelas='$idKelas' 
                       AND u.idpelajaran='$idPelajaran'
                       AND u.idsemester='$idSemester'";
            $res2 = $db->QueryDb($sql);
            $row2 = mysqli_fetch_row($res2);
            $nUjian = $row2[0];

            echo "<tr>";
            echo "<td width='*' height='22' class='cur-hand' onclick='showDaftarNilai(\"$idJenisUjian\", \"$jenisUjian\", \"$idAturanNhb\", \"$aspek\", \"$namaAspek\")' align='left'>";
            echo $jenisUjian;
            echo "</td>";
            echo "<td width='10%' align='center' class='bg-light-purple fg-secondary'>$nUjian</td>";
            echo "</tr>";
        }
        echo "</table>&nbsp;";
    }
    echo "<input type='hidden' id='nTable' value='$nTable'>";
}
?>