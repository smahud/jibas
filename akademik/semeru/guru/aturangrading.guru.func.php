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
function ShowPelajaranGuru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nip = RequestData("nip", "");

        $sql = "SELECT DISTINCT d.departemen 
                  FROM jbsakad.guru g, jbsakad.pelajaran p, jbsakad.departemen d
                 WHERE g.idpelajaran = p.replid 
                   AND p.departemen = d.departemen 
                   AND g.nip = '$nip'
                   AND g.aktif = 1
                   AND p.aktif = 1      
                   AND d.aktif = 1 
                 ORDER BY d.urutan";	
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            echo HintInfo::ShowLeft("Belum tersedia data Pelajaran untuk pegawai terpilih.<br>Silahkan atur pelajaran yang diajarkan pegawai di bagian <b>Guru & Pelajaran</b> menu <b>Guru</b>.", 250);
            return;
        }

        echo "<table class='tab tabShadow' id='table' border='1' width='100%' align='left'>";
        while ($row = mysqli_fetch_assoc($res))
        {
            $departemen = $row['departemen'];

            echo "<tr height='25' class='bg-light-gray'>";            
            echo "<td colspan='2' align='left'><b>$departemen</b></td>";
            echo "</tr>";

            $sql = "SELECT p.replid, p.kode, p.nama 
                      FROM jbsakad.guru g, jbsakad.pelajaran p
                     WHERE g.idpelajaran = p.replid 
                       AND p.departemen = '$departemen' 
                       AND g.nip = '$nip'
                       AND g.aktif = 1
                       AND p.aktif = 1      
                     ORDER BY p.nama";
            $res2 = $db->QueryDb($sql);
            while ($row2 = mysqli_fetch_assoc($res2))
            {
                $replid = $row2['replid'];
                $kode = $row2['kode'];
                $nama = $row2['nama'];

                echo "<tr height='25' onclick=\"pilih('$replid', '$nama', '$departemen')\" style='cursor:pointer;'>";
                echo "<td width='15%' align='left'>$kode</td>";
                echo "<td width='*' align='left'>$nama</td>";
                echo "</tr>";
            }
        }
        echo "</table>";
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        echo Msg::InfoError($e->getMessage(), "kev7x");
    }
    finally
    {
        $db->Close();
    }
}
?>
