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
$nRowPerPage = 15;

function ShowSelectBagianPegawai($db)
{
    global $bagian;

    $sql = "SELECT bagian FROM jbssdm.bagianpegawai ORDER BY urutan";
    $res = $db->QueryDb($sql);

    echo "<select name='bagian' id='bagian' style='width:180px' class='inputbox' onchange='onChangeBagian();'>";
    $sel = ($bagian == "-1") ? "selected" : "";
    echo "<option value='-1' $sel>(Semua Bagian)</option>";
    while ($row = mysqli_fetch_array($res))
    {
        $sel = ($bagian == $row['bagian']) ? "selected" : "";
        echo "<option value='$row[bagian]' $sel>$row[bagian]</option>";
    }
    echo "</select>";
}

function ColumnColor($urutBy)
{
    global $urut;
    return ($urut == $urutBy) ? "#fffc00" : "#25f2ff";
}

function ShowTablePegawai($db)
{
    global $bagian, $urut, $SI_USER_STAFF;
    global $page, $nRowPerPage, $nData;

    $startIndex = ($page - 1) * $nRowPerPage;
    try
    {
        $sql_bagian = ($bagian == "-1") ? "1 = 1" : "bagian='$bagian'";
        $sql = "SELECT * 
                  FROM jbssdm.pegawai
                 WHERE $sql_bagian
                 ORDER BY $urut
                 LIMIT $startIndex, $nRowPerPage";
        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
        {
            echo "<div class='warning' style='margin: 4px; padding: 10px; text-align: center;'>Tidak ditemukan data pegawai</div>";
            return;
        }
        
        echo "<input type='hidden' id='urut' value='$urut'>";
        echo "<table class='tab tabShadow' id='table' border='1' style='border-collapse:collapse' width='95%' align='center' bordercolor='#000000'>";
        echo "<tr height='30' align='center'>";
        echo "<td class='header' width='50'>No</td>";
        echo "<td class='header' width='150'>NIP</td>";
        echo "<td class='header' width='250'>Nama</td>";
        echo "<td class='header' width='150'>Panggilan</td>";
        echo "<td class='header' width='150'>Handphone</td>";
        echo "<td class='header' width='150'>Email</td>";
        echo "<td class='header' width='120'>PIN Pegawai</td>";
        echo "<td class='header' width='65'>Status</td>";
        echo "<td class='header colButton hide-in-report' width='120'>&nbsp;</td>";
        echo "</tr>";
        $cnt = $startIndex + 1;
        while($row = mysqli_fetch_array($res))
        {
            $replid = $row['replid'];
            $nip = $row['nip'];
            $nama = $row['nama'];
            $panggilan = $row['panggilan'];
            $handphone = $row['handphone'];
            $email = $row['email'];
            $pin = $row['pinpegawai'];
            $aktif = $row['aktif'];

            echo "<tr height='25'>";
            echo "<td align='center' class='numberColumn'>$cnt</td>";
            echo "<td align='center'>$nip</td>";
            echo "<td>$nama</td>";
            echo "<td>$panggilan</td>";
            echo "<td>$handphone</td>";
            echo "<td>$email</td>";
            echo "<td align='center'>";
            echo "<span id='pin-$replid'>$pin</span>";
            if (SI_USER_LEVEL() != $SI_USER_STAFF) 
            {
                echo "&nbsp;<img class='hide-in-report' src='../images/ico/refresh.png' border='0' title='refresh pin' style='cursor:pointer' onclick='changePin($replid)'>";
            }
            echo "</td>";
            echo "<td align='center'>";

            if (SI_USER_LEVEL() == $SI_USER_STAFF) 
            {
                if ($aktif == 1)
                    echo "<img src='../images/ico/aktif.png' border='0' title='aktif'>";
                else
                    echo "<img src='../images/ico/nonaktif.png' border='0' title='tidak aktif'>";
            }
            else 
            {
                if ($aktif == 1)
                    echo "<img id='status-$replid' src='../images/ico/aktif.png' onclick='setAktif($replid, 0)' style='cursor:pointer' border='0' title='aktif'>";
                else
                    echo "<img id='status-$replid' src='../images/ico/nonaktif.png' onclick='setAktif($replid, 1)' style='cursor:pointer' border='0' title='tidak aktif'>";
            }

            echo "</td>";
            echo "<td align='center' class='colButton hide-in-report'>";
            echo "<a href='JavaScript:lihat(\"$nip\")'><img src='../images/ico/lihat.png' border='0' title='lihat'></a>&nbsp;";
            if (SI_USER_LEVEL() != $SI_USER_STAFF) 
            {
                echo "<a href='JavaScript:detail(\"$replid\")'><img src='../images/ico/print.png' border='0' title='cetak'></a>&nbsp;";
                echo "<a href='JavaScript:edit(\"$replid\")'><img src='../images/ico/ubah.png' border='0' title='ubah'></a>&nbsp;";
                echo "<a href='JavaScript:hapus(\"$replid\")'><img src='../images/ico/hapus.png' border='0' title='hapus'></a>";
            }
            echo "</td>";
            echo "</tr>";

            $cnt++;
        }
        echo "</table>";
    }
    catch (Exception $ex)
    {
        echo Msg::InfoError($ex->getMessage(), "k3edk");
    }
}

function ShowPageControl($db)
{
    global $bagian, $nRowPerPage, $nData;

    if ($nData == 0)
        return;

    $sql_bagian = ($bagian == "-1") ? "1 = 1" : "bagian='$bagian'";
    $sql = "SELECT COUNT(*)
              FROM jbssdm.pegawai
             WHERE $sql_bagian";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);             
    $nData = $row[0];

    $totalPage = ceil($nData / $nRowPerPage);

    echo "<table border='0' style='border-collapse:collapse' width='95%'>";
    echo "<tr height='30' align='center'>";
    echo "<td align='left'>";

    echo "Halaman&nbsp;&nbsp;";
    echo "<input type='hidden' id='totalpage' value='$totalPage'>";
    echo "<input type='button' class='but' style='height:28px;' value='  <  ' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $totalPage; $i++)
    {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height:28px;' value='  >  ' onclick='onNextPage()'>";
    echo "&nbsp;dari $totalPage, jumlah $nData data";

    echo "</td>";
    echo "</tr>";
    echo "</table>";
}

function ChangeAktif()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $aktif = $_REQUEST['aktif'];

        $sql = "UPDATE jbssdm.pegawai SET aktif=$aktif WHERE replid=$replid";
        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}

function ChangePin()
{
    $db = new Db;
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];
        $newPin = RandomUtil::RandInt(5);

        $sql = "UPDATE jbssdm.pegawai SET pinpegawai = $newPin WHERE replid = $replid";
        $db->QueryDb($sql);

        return "[1,\"$newPin\"]";
    }
    catch (Exception $ex)
    {
        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}

function HapusPegawai()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = $_REQUEST['replid'];

        $sql = "DELETE FROM jbssdm.pegawai WHERE replid = $replid";

        $db->QueryDb($sql);

        return "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data pegawai tidak dapat dihapus karena sudah digunakan di data lain.";
            return "[-1,\"$msg\"]";
        }

        $msg = Msg::InfoError($ex->getMessage(), "k3edk");
        return "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }
}
?>