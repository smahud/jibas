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
function ShowTableJenisPengujian($db, $id_pel)
{
    global $SI_USER_STAFF, $nama_pel;

    try
    {
        // Query jenisujian with LEFT JOIN to pelajaran
        $sql = "SELECT j.replid, j.jenisujian, j.idpelajaran, j.keterangan, 
                       p.nama, p.departemen, j.info1, j.urutan 
                  FROM jbsakad.jenisujian j 
                  LEFT JOIN jbsakad.pelajaran p ON j.idpelajaran = p.replid 
                 WHERE j.idpelajaran = '$id_pel' 
                 ORDER BY j.urutan, j.jenisujian";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);

        if ($nData > 0) 
        {
            // Table content
            echo "<table class='tab tabShadow' id='table' width='100%' align='center'>";
            echo "<tr height='30'>";
            echo "<td width='4%' class='header' align='center'>No</td>";
            echo "<td width='15%' class='header' align='center'>Singkatan</td>";
            echo "<td width='7%' class='header' align='center'>Urutan</td>";
            echo "<td width='30%' class='header' align='center'>Jenis Pengujian</td>";
            echo "<td width='*' class='header' align='center'>Keterangan</td>";
            echo "<td width='8%' class='header hide-in-report' align='center'>&nbsp;</td>";
            echo "</tr>";

            $cnt = 0;
            while ($row = mysqli_fetch_array($res)) 
            {
                $replid = $row['replid'];
                $jenisujian = $row['jenisujian'];
                $idpelajaran = $id_pel;
                $keterangan = $row['keterangan'];
                $info1 = $row['info1'];
                $urutan = $row['urutan'];

                echo "<tr height='25'>";
                echo "<td align='center' valign='top' class='numberColumn'>" . (++$cnt) . "</td>";
                echo "<td align='center' valign='top'>$info1</td>";
                echo "<td align='center' valign='top'>$urutan</td>";
                echo "<td align='left' valign='top'>$jenisujian</td>";
                echo "<td>$keterangan</td>";
                echo "<td align='center' class='hide-in-report' valign='top'>";
                echo "<a href='JavaScript:edit($replid)'><img src='../images/ico/ubah.png' border='0' title='Ubah Jenis Pengujian' /></a>&nbsp;";
                if (SI_USER_LEVEL() != $SI_USER_STAFF) 
                {
                    echo "<a href='JavaScript:hapus($replid, $idpelajaran)'><img src='../images/ico/hapus.png' border='0' title='Hapus Jenis Pengujian' /></a>";
                }
                echo "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } 
        else 
        {
            echo HintInfo::ShowCenter("Belum tersedia data Jenis Pengujian.<br>Silahkan klik ikon tambah untuk membuat jenis pengujian.");
            /*
            echo "<table width='100%' border='0' align='center'>";
            echo "<tr>";
            echo "<td align='center' valign='middle' height='200'>";
            echo "<font size='2' color='red'><b>Tidak ditemukan adanya data.";
            echo "<br/>Klik <a href='JavaScript:tambah()'><font size='2' color='green'>di sini</font></a>&nbsp;untuk mengisi jenis pengujian";
            echo "</b></font>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            */
        }
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function HapusJenisPengujian()
{
    $db = new Db();
    try 
    {
        $db->Open();
        
        $replid = $_REQUEST['replid'];    
        $sql = "DELETE FROM jbsakad.jenisujian 
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);
        
        echo "[1,\"Data berhasil dihapus\"]";
    }
    catch (Exception $ex) 
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data jenis pengujian tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        echo "[-1,\"Gagal menghapus data: " . $ex->getMessage() . "\"]";
    }
    finally
    {
        $db->Close();
    }
}
?>