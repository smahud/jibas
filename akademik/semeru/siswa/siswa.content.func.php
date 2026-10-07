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
$nRowPerPage = 10;

function LinkWhatsApp($nomor)
{
    if (trim($nomor) == "")
        return "";

    $orig = $nomor;        

    $nomor = str_replace(" ", "", $nomor);
    $nomor = str_replace(".", "", $nomor);
    $nomor = str_replace("-", "", $nomor);
    $nomor = str_replace(")", "", $nomor);
    $nomor = str_replace("(", "", $nomor);

    if (substr($nomor, 0, 1) == "0")
        $nomor = "+62" . substr($nomor, 1);

    return "<a class='ablack' style='color: black; font-weight: normal;' target='_blank' href='https://wa.me/$nomor'>" . $orig . "</a>&nbsp;";
}

function LinkEmail($email)
{
    if (trim($email) == "")
        return "";

    return "<a class='ablack' style='color: black; font-weight: normal;'  target='_blank' href='mailto:$email'>" . $email . "</a>&nbsp;";
}

function GetOrderBy($orderBy)
{
    switch ($orderBy)
    {
        case 2:
            return "s.nis ASC";
        case 3:
            return "s.replid DESC";
        default:
            return "s.nama ASC";
    }
}

function ShowSelectOrderBy()
{
    global $orderBy;

    echo "<select id='orderby' class='inputbox' style='width: 120px;' onchange='onChangeOrderBy()'>";
    $sel = ($orderBy == 1) ? "selected" : "";
    echo "<option value='1' $sel>Nama</option>";
    $sel = ($orderBy == 2) ? "selected" : "";
    echo "<option value='2' $sel>NIS</option>";
    $sel = ($orderBy == 3) ? "selected" : "";
    echo "<option value='3' $sel>Terbaru</option>";
    echo "</select>";
}

function ShowTableSiswa($db)
{
    global $page, $nRowPerPage, $idKelas, $orderBy, $nData;

    $orderBy = GetOrderBy($orderBy);
    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT s.replid, nis, nama, s.aktif,
                   IFNULL(panggilan, '') AS fpanggilan,
                   IFNULL(asalsekolah, '') AS fasalsekolah,
                   IFNULL(tmplahir, '') AS ftmplahir, 
                   IF(tgllahir IS NULL, '', DATE_FORMAT(tgllahir, '%Y-%m-%d')) AS ftgllahir,
                   IFNULL(kelamin, '') AS fkelamin,
                   IFNULL(alamatsiswa, '') AS falamatsiswa, 
                   IFNULL(hpsiswa, '') AS fhpsiswa, 
                   IFNULL(emailsiswa, '') AS femailsiswa,
                   IFNULL(telponsiswa, '') AS ftelponsiswa, 
                   IFNULL(telponortu, '') AS ftelponortu, 
                   IFNULL(hportu, '') AS fhportu1,
                   IFNULL(s.info1, '') AS fhportu2,
                   IFNULL(s.info2, '') AS fhportu3,
                   IFNULL(emailayah, '') AS femailayah,
                   IFNULL(emailibu, '') AS femailibu,
                   IF(foto IS NULL, '', TO_BASE64(foto)) AS ffoto,
                   IFNULL(s.keterangan, '') AS fketerangan
              FROM jbsakad.siswa s, jbsakad.kelas k
             WHERE s.idkelas = '$idKelas' 
               AND s.idkelas = k.replid 
               AND s.alumni = 0 
            ORDER BY $orderBy
            LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);
    $nData = mysqli_num_rows($res);
    if ($nData == 0)
    {
        HintInfo::ShowCenter("Belum ada data siswa<br>Silahkan klik ikon tambah untuk menambah data siswa");
        return;
    }

    echo "<table id='table' class='tab tabShadow' width='100%'>";
    echo "<tr>";
    echo "<td class='header' width='3%' align='center'>No</td>";
    echo "<td class='header' width='10%' align='center'>&nbsp;</td>";
    echo "<td class='header' width='*'>Siswa</td>";
    echo "<td class='header' width='25%'>Informasi</td>";
    echo "<td class='header' width='25%'>Kontak</td>";
    echo "<td class='header hide-in-report' width='15%'>&nbsp;</td>";
    echo "</tr>";

    $no = $startIndex;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];
        $nis = $row['nis'];

        $gender = "";
        if ($row['fkelamin'] == "p")
            $gender = "Perempuan";
        else if ($row['fkelamin'] == "l")
            $gender = "Laki-laki";

        echo "<tr>";
        echo "<td align='center' class='numberColumn'>$no</td>";
        echo "<td align='center' valign='top'>";
        echo "<div style='position: relative'>";
        if (!empty($row['ffoto']))
            echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageSiswa($replid)' src='data:image/jpeg;base64,". $row['ffoto'] . "' border='0' style='width: 60px; height: 80px;'>";
        else
            echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageSiswa($replid)' src='" . NoUserImage() . "' border='0' style='width: 60px; height: 80px;'>";
        echo "<img src='../images/ico/ubah.png' class='cur-hand' onclick='gantiFoto($replid)' style='position: absolute; bottom: 0; right: 0'>";            
        echo "</div>";            
        echo "</td>";
        echo "<td valign='top'>";
        echo "<span id='nama$replid' class='fs-14 fst-bold'>" . $row['nama'] . "</span><br>";
        echo "<span id='nis$replid' class='ff-consolas fs-13'>" . $row['nis'] . "</span><br>";
        echo "<span id='panggilan$replid' class='fst-italic fs-12 fg-secondary'>" . $row['fpanggilan'] . "</span><br>";
        echo "<span id='spInfoAktif$replid' class='bg-maroon fg-yellow w100 br3' style='display: inline-block; text-align: center'>";
        if ($row['aktif'] == 0)
            echo "Tidak Aktif";
        echo "</span>";

        echo "</td>";

        echo "<td valign='top'>";
        if ($row['ftgllahir'] != '')
        {
            echo "<span class='fg-secondary'>Kelahiran:</span> " . $row['ftmplahir'] . ", " . LongDateFormat($row['ftgllahir']) . "<br>";
            echo "<span class='fg-secondary'>Usia:</span> " . CountAge($row['ftgllahir']) . "<br>";
        }
        else 
        {
            echo "<span class='fg-secondary'>Kelahiran:</span> " . $row['ftmplahir'] . "<br>";
        }
        echo "<span class='fg-secondary'>Gender:</span> " . $gender . "<br>";
        echo "<span class='fg-secondary'>Asal:</span> " . $row['fasalsekolah'] . "<br>";
        echo "<span class='fg-secondary'>Alamat:</span> " . $row['falamatsiswa'] . "<br>";
        echo "<span class='fg-secondary'>Keterangan:</span> " . $row['fketerangan'] . "<br>";
        echo "</td>";

        echo "<td>";
        echo "<span class='fg-secondary'>Siswa:</span><br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>HP:</span> " . LinkWhatsApp($row['fhpsiswa']) . "<br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>Email:</span> " . LinkEmail($row['femailsiswa']) . "<br>";
        
        echo "<span class='fg-secondary'>Orang Tua:</span><br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>HP:</span> ";
        echo LinkWhatsApp($row['fhportu1']);
        echo LinkWhatsApp($row['fhportu2']);
        echo LinkWhatsApp($row['fhportu3']);
        echo "<br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>Email:</span> ";
        echo LinkEmail($row['femailayah']);
        echo LinkEmail($row['femailibu']);
        echo "</td>";

        echo "<td align='center' valign='top' class='hide-in-report'>";
        echo "<img src='../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilSiswa($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardSiswa($replid)'>&nbsp;&nbsp;";

        if ($row['aktif'])
            echo "<img id='imAktif$replid' src='../images/ico/aktif.png' title='aktif' class='cur-hand' onclick='setNewAktif($replid, 0)'>&nbsp;&nbsp;";
        else
            echo "<img id='imAktif$replid' src='../images/ico/nonaktif.png' title='tidak aktif' class='cur-hand' onclick='setNewAktif($replid, 1)'>&nbsp;&nbsp;";

        echo "<img src='../images/ico/ubah.png' title='ubah mudah' class='cur-hand' onclick='ubahMudah($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/ubah2x.png' title='ubah lengkap' class='cur-hand' onclick='ubahLengkap($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/hapus.png' title='hapus' class='cur-hand' onclick='hapusSiswa(\"$nis\")'>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
}

function ShowPageControl($db)
{
    global $page, $nRowPerPage, $idKelas;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.siswa s
             WHERE s.idkelas = '$idKelas' 
               AND s.alumni = 0";
    $nData = $db->ExecuteScalar($sql, 0);

    if ($nData == 0)
        return;

    $nPage = (int) ceil($nData / $nRowPerPage);
    echo "Halaman ";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='<' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {
        $sel = $i == $page ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='>' onclick='onNextPage()'>";
    echo " dari $nPage, jumlah $nData data";
    echo "<input type='hidden' id='npage' value='$nPage'>";
}

function SetAktif()
{
   $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $newAktif = RequestData("newaktif", 0);

        $sql = "UPDATE jbsakad.siswa
                   SET aktif = '$newAktif'
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil diupdate"]);
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

function HapusSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nis = RequestData('nis', '');

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.tambahandatasiswa
                 WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.siswa 
                  WHERE nis = '$nis'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch (Exception $ex)
    {
        $db->LogLastErrorIfExist();

        $lastError = $db->LastError();
        $errNo = $lastError[0];

        $db->RollbackTrans();

        if ($errNo == 1451) // Cannot delete or update a parent row: a foreign key constraint fails
        {
            $msg = "Data siswa tidak dapat dihapus karena sudah digunakan di data lain.";
            return json_encode([-1, $msg]);
        }

        $msg = Msg::InfoError($ex->getMessage(), "kem31");
        return json_encode([-1, $msg]);
    }
    finally
    {
        $db->Close();
    }
}

?>