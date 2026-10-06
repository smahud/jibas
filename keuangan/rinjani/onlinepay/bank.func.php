<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
        echo "<select name='departemen' id='departemen' onChange='onChangeDept()' class='inputbox' style='width:200px'>";
        $dep = getDepartemen($db, getAccess());
        foreach($dep as $value)
        {
            if ($departemen == "") $departemen = $value;
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "krt72");
    }
}

function HapusBank()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idBank = $_REQUEST['idbank'];

        $sql = "DELETE FROM jbsfina.bank2 WHERE replid = $idBank";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data bank tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}

function HapusQris()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idBank = $_REQUEST['idbank'];

        $sql = "UPDATE jbsfina.bank2 SET qrisexist = 0, qrismime = '', qris = '' WHERE replid = $idBank";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}


function SetAktif()
{
    $db = new Db();
    try
    {
        $db->Open();

        $idBank = $_REQUEST['idbank'];
        $newAktif = $_REQUEST['newaktif'];

        $sql = "UPDATE jbsfina.bank2 SET aktif = $newAktif WHERE replid = $idBank";
        $db->QueryDb($sql);

        return json_encode([1,"OK"]);
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return json_encode([-1,$msg]);
    }
    finally
    {
        $db->Close();
    }
}

function ShowBankList()
{
    global $departemen;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<table id='table' border='1' align='center' class='tab' cellpadding='5' style='border-width: 1px; border-collapse: collapse; border-color: #dddddd'>";
        echo "<tr style='height: 30px'>";
        echo "<td class='header' width='30' align='center'>No</td>";
        echo "<td class='header' width='250' align='center'>Bank</td>";
        echo "<td class='header' width='350' align='center'>Informasi</td>";
        echo "<td class='header' width='350' align='center'>Rekening</td>";
        echo "<td class='header' width='100' align='center'>Status</td>";
        echo "<td class='header' width='80'>&nbsp</td>";
        echo "</tr>";

        $no = 0;
        $sql = "SELECT b.replid, b.bank, b.bankno, b.bankloc, b.bankname, b.bankvano, b.qrisexist, b.qris, b.keterangan,
                       b.rekkas, r1.nama as namarekkas, b.rekpendapatan, r2.nama as namarekpendapatan, b.aktif
                  FROM jbsfina.bank2 b, jbsfina.rekakun r1, jbsfina.rekakun r2
                 WHERE b.rekkas = r1.kode
                   AND b.rekpendapatan = r2.kode
                   AND b.departemen = '$departemen'
                 ORDER BY b.bank";
        $res = $db->QueryDb($sql);  
        while ($row = mysqli_fetch_array($res))
        {
            $no += 1;
            $idBank = $row["replid"];

            if (getLevel() != 2)
            {
                $aktif = "<a href='#' onclick='setBankAktif($idBank, 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>";
                if ($row["aktif"] == 1)
                    $aktif = "<a href='#' onclick='setBankAktif($idBank, 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>";
            }
            else
            {
                $aktif = "<img src='../images/ico/nonaktif.png' border='0' title='set aktif'>";
                if ($row["aktif"] == 1)
                    $aktif = "<img src='../images/ico/aktif.png' border='0' title='set non aktif'>";
            } 

            echo "<tr>";
            echo "<td align='center' valign='top' class='numberColumn'>" . $no. "</td>";
            echo "<td align='left' valign='top'>";
            echo "<b>" . $row['bank']. "</b><br>";
            echo "" . $row['bankloc']. "";
            echo "</td>";
            echo "<td align='left' valign='top'>";
            echo "<b>Nama:</b> " . $row['bankname']. "<br>";
            echo "<b>No. Rekening:</b> " . $row['bankno']. "<br>";
            echo "<b>QRIS:</b><br>&nbsp;&nbsp;";
            echo "<span id='spQris" .  $idBank . "'>";
            if ($row['qrisexist'] == 0) 
            {
                echo "<i>(belum tersedia)</i>";
            }
            else 
            {
                echo "<img src='../images/ico/lihat.png' border='0' title='lihat QRIS' style='cursor: pointer' onclick='showQris($idBank)'>&nbsp;&nbsp;";    
                echo "<img src='../images/ico/hapus.png' border='0' title='hapus QRIS' style='cursor: pointer' onclick='hapusQris($idBank)'>";    
            }
            echo "</span>";
            echo "</td>";
            echo "<td align='left' valign='top'>";
            echo "<b>Kas:</b> " . $row['rekkas']. " " . $row['namarekkas']. "<br>";
            echo "<b>Pendapatan:</b> " . $row['rekpendapatan']. " " . $row['namarekpendapatan']. "<br>";
            echo "<b>Keterangan:</b> " . $row['keterangan']. "";
            echo "</td>";
            echo "<td align='center' valign='top'>";
            echo "<span id='spAktif" . $idBank. "'>";
            echo $aktif;
            echo "</span>";
            echo "</td>";
            echo "<td align='center' valign='top'>";
            if (getLevel() != 2) 
            {
                echo "<a href='#' onclick='editBank(" .  $idBank . ")' ><img src='../images/ico/ubah.png' border='0' alt=''/></a>&nbsp;";
                echo "<a href='#' onclick='hapusBank(" .  $idBank . ")'><img src='../images/ico/hapus.png' border='0' alt=''/></a>";
            }
            echo "</td>";
            echo "</tr>";
        }

        if ($no == 0)
        {
            echo "<tr style='height: 80px'>";
            echo "<td align='center' valign='middle' colspan='7'><i>belum ada data bank</i></td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        echo $msg;
    }
    finally
    {
        $db->Close();
    }
}
?>

