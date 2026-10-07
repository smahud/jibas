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
$nRowPerPage = 2;
$nColPerRow = 3;

function ShowSearchRpp()
{
    global $cari, $idPelajaran, $idTingkat, $idSemester, $status;

    $db = new Db();
    try
    {
        $db->Open();

        $lsCari = explode(" ", $cari);
        $stCari = "";
        for($i = 0; $i < count($lsCari); $i++)
        {
            if ($stCari != "") $stCari .= " ";
            $stCari .= "+" . $lsCari[$i];
        }
        
        $sql = "SELECT replid
                  FROM jbsakad.rpp
                 WHERE idpelajaran = '$idPelajaran'
                   AND idtingkat = '$idTingkat'
                   AND idsemester = '$idSemester'
                   AND aktif IN ($status) 
                   AND MATCH(rpp, deskripsi_data) AGAINST ('$stCari' IN BOOLEAN MODE)";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><br><span class='fg-secondary'><i>Tidak ditemukan data</i></span>";
            return;
        }

        $stIdRpp = ""; 
        while($row = mysqli_fetch_row($res))
        {
            if ($stIdRpp != "")
                $stIdRpp .= ",";

            $stIdRpp .= $row[0];
        }
        
        ShowTableRpp($db, $stIdRpp);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowListRpp()
{
    global $page, $nRowPerPage, $nColPerRow;
    global $idPelajaran, $idTingkat, $idSemester, $status;

    $db = new Db();
    try
    {
        $db->Open();

        $nItem = $nRowPerPage * $nColPerRow;
        $startIndex = ($page - 1) * $nItem;
        
        $sql = "SELECT replid
                  FROM jbsakad.rpp
                 WHERE idpelajaran = '$idPelajaran'
                   AND idtingkat = '$idTingkat'
                   AND idsemester = '$idSemester'
                   AND aktif IN ($status) 
                 ORDER BY urutan
                 LIMIT $startIndex, $nItem";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            HintInfo::ShowCenter("Belum tersedia data RPP.<br>Silahkan klik ikon tambah untuk menambah data RPP");
            return;
        }

        $stIdRpp = ""; 
        while($row = mysqli_fetch_row($res))
        {
            if ($stIdRpp != "")
                $stIdRpp .= ",";

            $stIdRpp .= $row[0];
        }
        
        ShowTableRpp($db, $stIdRpp);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
    finally
    {
        $db->Close();
    }
}


function ShowTableRpp($db, $stIdRpp)
{
    global $nColPerRow, $SI_USER_STAFF;

    $sql = "SELECT replid, koderpp, rpp, deskripsi, aktif
              FROM jbsakad.rpp
             WHERE replid IN ($stIdRpp)
            ORDER BY urutan, koderpp";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        HintInfo::ShowCenter("Belum tersedia data RPP.<br>Silahkan klik ikon tambah untuk menambah data RPP");
        return;
    }

    echo "<table border='0' cellpadding='5' cellspacing='5' width='100%'>";

    $cnt = 0;
    while ($row = mysqli_fetch_row($res))
    {
        $cnt += 1;

        $idRpp = $row[0];
        $kodeRpp = $row[1];
        $materi = $row[2];
        $deskripsi = $row[3];
        $aktif = $row[4];

        if ($cnt == 1)
            echo "<tr style='height: 350px'>";

        echo "<td width='33%' align='left' valign='top' onmouseover='onHoverRpp($idRpp)' onmouseleave='onLeaveRpp($idRpp)'>";
        echo "<div id='rpp-$idRpp' style='overflow: auto; width: 100%; height: 320px'>";
        echo "<span class='fs-14 fst-bold fg-black'><span class='fs-14 fg-maroon'>$kodeRpp</span><span class='fg-secondary'>&nbsp;-&nbsp;</span>$materi</span><br>";
        echo $deskripsi;
        echo "</div>";
        echo "<div id='menu-rpp-$idRpp' style='width: 100%; height: 30px; text-align: left; padding-left: 3px; visibility: hidden; '>";
        echo "<img src='../images/ico/lihat.png' onclick='view($idRpp)' class='cur-hand' title='lihat'>&nbsp;&nbsp;&nbsp;";            
        if ($aktif == 1)
            echo "<img src='../images/ico/aktif.png' id='aktif-$idRpp' onclick='setAktif($idRpp, 0)' class='cur-hand' title='aktif'>&nbsp;&nbsp;&nbsp;";
        else 
            echo "<img src='../images/ico/nonaktif.png' id='aktif-$idRpp' onclick='setAktif($idRpp, 1)' class='cur-hand' title='non aktif'>&nbsp;&nbsp;&nbsp;";
        echo "<img src='../images/ico/ubah.png' onclick='edit($idRpp)' class='cur-hand' title='edit'>&nbsp;&nbsp;&nbsp;";
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
            echo "<img src='../images/ico/hapus.png' onclick='hapus($idRpp)' class='cur-hand' title='hapus'>";
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

function ShowPageControl()
{
    global $page, $status, $nRowPerPage, $nColPerRow;
    global $idPelajaran, $idTingkat, $idSemester;

    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(replid)
                  FROM jbsakad.rpp
                 WHERE idpelajaran = '$idPelajaran'
                   AND idtingkat = '$idTingkat'
                   AND idsemester = '$idSemester'
                   AND aktif IN ($status)";
        $nData = $db->ExecuteScalar($sql, 0);
        if ($nData == 0)
            return;

        $nPage = 1;
        $nItem = $nRowPerPage * $nColPerRow;
        if ($nData > $nItem)
        {
            $nPage = ceil($nData / $nItem);
        }

        echo "Halaman ";
        echo "<select class='inputbox' id='page' style='width: 50px' onchange='onChangePage()'>";
        for ($i = 1; $i <= $nPage; $i++)
        {   
            $sel = $page == $i ? "selected" : "";
            echo "<option value='$i' $sel>$i</option>";
        }
        echo "</select>";
        echo " dari $nPage, jumlah $nData data";

    }
    catch (Exception $e)
    {
        $db->LogLastErrorIfExist();
        
        echo $e->getMessage();
    }
    finally
    {
        $db->Close();
    }   
}

function SetAktifRpp()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $newAktif = RequestData("newaktif", 0);

        $sql = "UPDATE jbsakad.rpp
                   SET aktif = '$newAktif'
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        echo json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $db->LogLastErrorIfExist();

        echo json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
} 

function HapusRpp()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);

        $sql = "DELETE FROM  jbsakad.rpp
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        echo json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        $db->LogLastErrorIfExist();
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data RPP tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }
        
        echo json_encode([-1, "Gagal menghapus data: " . $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}

function IsAktifSelected($value, $compare)
{
    return $value == $compare ? "selected" : "";
}
?>