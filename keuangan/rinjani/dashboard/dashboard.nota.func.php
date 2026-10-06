<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
$nRowPerPage = 2;
$nColPerRow = 3;

function ShowSelectBagianNota()
{
    global $nis, $bagianNota;

    $db = new Db();
    try
    {
        $db->Open();

        echo "<select id='bagiannota' onchange='onChangeBagianNota()' class='inputbox' style='width:150px'>";
        $sql = "SELECT bagian, urutan
                  FROM jbsumum.bagiannota
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);

        $sql = "SELECT COUNT(id)
                  FROM jbsumum.nota
                 WHERE nis = '$nis'";
        $nData = $db->ExecuteScalar($sql, 0);
        $namaBagian = "Semua Bagian ($nData)";
        echo "<option value='---'>$namaBagian</option>";
        while ($row = mysqli_fetch_row($res))
        {
            $sql = "SELECT COUNT(id)
                      FROM jbsumum.nota
                     WHERE nis = '$nis'
                       AND bagian = '$row[0]'";
            $nData = $db->ExecuteScalar($sql, 0);
            $namaBagian = "$row[0] ($nData)";

            $sel = ($bagianNota == $row[0]) ? "selected" : "";
            echo "<option value='$row[0]' $sel>$namaBagian</option>";
        }
        echo "</select>";
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function FetchListNota()
{
    global $page, $nRowPerPage, $nColPerRow;

    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData("nis", "");
        $bagianNota = RequestData("bagiannota", "---");

        $nItem = $nRowPerPage * $nColPerRow;
        $startIndex = ($page - 1) * $nItem;
        
        $sql = "SELECT id
                  FROM jbsumum.nota
                 WHERE nis = '$nis'";
        if ($bagianNota != "---")                 
            $sql .= " AND bagian = '$bagianNota'";
        $sql .= " ORDER BY id DESC
                  LIMIT 1000";      

        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
            return json_encode([0, "belum ada data", ""]);

        $nItemPerPage = $nRowPerPage * $nColPerRow;
        $lsIdNota = array(); 
        $stIdNota = "";
        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            if ($cnt == 0)
                $stIdNota = "";
            
            if ($stIdNota != "") $stIdNota .= ",";
            $stIdNota .= $row[0];
            $cnt += 1;

            if ($cnt == $nItemPerPage)
            {
                $lsIdNota[] = $stIdNota;
                $cnt = 0;
            }
        }

        if ($stIdNota != "")
        {
            $lsIdNota[] = $stIdNota;
        }

        return json_encode([1, $nData, $lsIdNota]);
    }
    catch (Exception $e)
    {
        return json_encode([-1, $e->getMessage(), ""]);
    }
    finally
    {
        $db->Close();
    }
}

function ShowPageControl()
{
    global $nData, $nRowPerPage, $nColPerRow;
    
    $nData = RequestData("ndata", 0);

    $nPage = 1;
    $nItem = $nRowPerPage * $nColPerRow;
    if ($nData > $nItem)
        $nPage = ceil($nData / $nItem);

    echo "Halaman ";
    echo "<select class='inputbox' id='page' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {   
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo " dari <span id='spNPage'>$nPage</span>, jumlah <span id='spNData'>$nData</span> data";
    
}

function ShowTableNota()
{
    global $SI_USER_LANDLORD;
    global $nColPerRow;

    $db = new Db();
    try 
    {
        $db->Open();

        $stIdNota = RequestData("stidnota", "");
        
        $sql = "SELECT n.id, n.judul, n.nota, IFNULL(n.pemilik, 'landlord'), 
                       IFNULL(p.nama, 'Administrator JIBAS'),     
                       DATE_FORMAT(n.waktu, '%d %M %Y %H:%i') AS fwaktu,
                       n.bagian
                  FROM jbsumum.nota n
                  LEFT JOIN jbssdm.pegawai p ON n.pemilik = p.nip
                 WHERE n.id IN ($stIdNota)
                 ORDER BY n.id DESC";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><br><span class='fg-secondary'><i>Belum ada data</i></span>";
            return;
        }

        echo "<table border='0' cellpadding='5' cellspacing='5' width='100%'>";

        $cnt = 0;
        while ($row = mysqli_fetch_row($res))
        {
            $cnt += 1;

            $idNota = $row[0];
            $judul = $row[1];
            $nota = $row[2];
            $pemilik = $row[3];
            $nama = $row[4];
            $fwaktu = $row[5];
            $namaBagian = $row[6];

            if ($cnt == 1)
                echo "<tr style='height: 350px'>";

            if ($pemilik == "landlord")
                $userInfo = "Administrator JIBAS";
            else 
                $userInfo = "$nama ($pemilik)";
                        
            echo "<td width='33%' align='left' valign='top' onmouseover='onHoverNota($idNota)' onmouseleave='onLeaveNota($idNota)'>";
            echo "<div id='nota-$idNota' style='overflow: auto; width: 100%; height: 250px'>";
            echo "<span class='fs-11' style='display: inline-block; border-radius: 5px; padding: 5px 5px; background-color: #efefef'>&nbsp;$namaBagian&nbsp;</span><br>";
            echo "<span class='fs-14 fst-bold fg-black'>$judul</span><br>";
            echo "<span class='fs-11 fg-secondary fst-italic'>oleh $userInfo - $fwaktu</span><br><br>";
            echo "<span class='fs-13' style='line-height: 25px;'>";
            echo $nota;
            echo "</span>";
            echo "</div>";
            echo "<div id='menu-nota-$idNota' style='width: 100%; height: 30px; text-align: left; padding-left: 3px; visibility: hidden; '>";
            echo "<img src='../images/ico/lihat.png' onclick='view($idNota)' class='cur-hand' title='lihat'>&nbsp;&nbsp;&nbsp;";

            if ((SI_USER_LEVEL() == $SI_USER_LANDLORD && $pemilik == "landlord") || SI_USER_ID() == $pemilik)
            {
                echo "<img src='../images/ico/ubah.png' onclick='edit($idNota)' class='cur-hand' title='edit'>&nbsp;&nbsp;&nbsp;";
                echo "<img src='../images/ico/hapus.png' onclick='hapus($idNota)' class='cur-hand' title='hapus'>";
            }

            echo "</div>";
            echo "</td>";

            if ($cnt == $nColPerRow)
            {
                echo "</tr>";
                $cnt = 0;
            }
        }

        if ($cnt > 0)
        {
            while ($cnt < $nColPerRow)
            {
                echo "<td width='33%' align='left'></td>";
                $cnt += 1;
            }
            echo "</tr>";
        }
        echo "</table>";

    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }

    
}

function HapusNota()
{
    $db = new Db();
    try
    {
        $db->Open();

        $id = RequestData("id", 0);

        $sql = "DELETE FROM jbsumum.nota
                 WHERE id = $id";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }   
}
?>