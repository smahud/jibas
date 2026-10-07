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
function ShowTableDaftarGuru($db)
{
    global $SI_USER_STAFF, $id_pel, $nama_dep;

    try
    {
        // Query jenisujian with LEFT JOIN to pelajaran
        if ($id_pel != 0)
        {
            $sql = "SELECT g.replid, g.nip, p.nama AS namapegawai, g.statusguru, g.keterangan, j.nama AS namapel
                      FROM jbsakad.guru g, jbssdm.pegawai p, jbsakad.pelajaran j, jbsakad.statusguru s 
                     WHERE g.nip = p.nip 
                       AND g.idpelajaran = j.replid 
                       AND g.statusguru = s.status
                       AND g.idpelajaran = '$id_pel'
                     ORDER BY p.nama";
        }
        else
        {
            $sql = "SELECT g.replid, g.nip, p.nama AS namapegawai, g.statusguru, g.keterangan, j.nama AS namapel
                      FROM jbsakad.guru g, jbssdm.pegawai p, jbsakad.pelajaran j, jbsakad.statusguru s 
                     WHERE g.nip = p.nip 
                       AND g.idpelajaran = j.replid 
                       AND g.statusguru = s.status
                       AND j.departemen = '$nama_dep' 
                     ORDER BY p.nama";
        }
        
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);

        if ($nData > 0) 
        {
            // Table content
            echo "<table class='tab tabShadow' id='table' width='100%' align='center'>";
            echo "<tr height='30'>";
            echo "<td width='4%' class='header' align='center'>No</td>";
            if ($id_pel == 0)
                echo "<td width='20%' class='header' align='center'>Guru</td>";
            echo "<td width='20%' class='header' align='center'>Pelajaran</td>";
            echo "<td width='15%' class='header' align='center'>Status Guru</td>";
            echo "<td width='*' class='header' align='center'>Keterangan</td>";
            echo "<td width='8%' class='header hide-in-report' align='center'>&nbsp;</td>";
            echo "</tr>";

            $cnt = 0;
            while ($row = mysqli_fetch_array($res)) 
            {
                $replid = $row['replid'];
                $nip = $row['nip'];
                $nama = $row['namapegawai'];
                $statusguru = $row['statusguru'];
                $pelajaran = $row['namapel'];
                $keterangan = $row['keterangan'];
           
                echo "<tr height='25'>";
                echo "<td align='center' valign='top' class='numberColumn'>" . (++$cnt) . "</td>";
                echo "<td align='left' valign='top'><span style='cursor: pointer;' onclick='showInfoPegawai(\"$nip\")'><b>$nama</b><br><span class='fg-secondary fs-10'>$nip</span></span></td>";
                if ($id_pel == 0)
                    echo "<td align='left' valign='top'>$pelajaran</td>";
                echo "<td align='left' valign='top'>$statusguru</td>";
                echo "<td>$keterangan</td>";
                echo "<td align='center' class='hide-in-report' valign='top'>";
                echo "<a href='JavaScript:edit($replid)'><img src='../images/ico/ubah.png' border='0' title='Ubah Guru' /></a>&nbsp;";
                if (SI_USER_LEVEL() != $SI_USER_STAFF) 
                    echo "<a href='JavaScript:hapus($replid)'><img src='../images/ico/hapus.png' border='0' title='Hapus Guru' /></a>";
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } 
        else 
        {
            HintInfo::ShowCenter("Belum tersedia data Guru.<br>Silahkan klik ikon tambah untuk menambah data Guru");
        }
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function HapusGuru()
{
    $db = new Db();
    try 
    {
        $db->Open();
        
        $replid = $_REQUEST['replid'];    
        $sql = "DELETE FROM jbsakad.guru
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);
        
        echo "[1,\"Data berhasil dihapus\"]";
    }
    catch (Exception $ex) 
    {
        echo "[-1,\"Gagal menghapus data: " . $ex->getMessage() . "\"]";
    }
    finally
    {
        $db->Close();
    }
}
?>