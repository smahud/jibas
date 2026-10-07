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
        case 0:
            return "s.replid DESC";
        case 2:
            return "s.nopendaftaran ASC";
        case 3:
            return "s.sum1 DESC";
        case 4:
            return "s.sum2 DESC";
        case 5:
            return "s.ujian1 DESC";
        case 6:
            return "s.ujian2 DESC";            
        case 7:
            return "s.ujian3 DESC";            
        case 8:
            return "s.ujian4 DESC";            
        case 9:
            return "s.ujian5 DESC";            
        case 10:
            return "s.ujian6 DESC";            
        case 11:
            return "s.ujian7 DESC";            
        case 12:
            return "s.ujian8 DESC";            
        case 13:
            return "s.ujian9 DESC";            
        case 14:
            return "s.ujian10 DESC";            
        default:
            return "s.nama ASC";
    }
}

function ShowSelectOrderBy()
{
    global $orderBy;

    echo "<select id='orderby' class='inputbox' style='width: 120px;' onchange='onChangeOrderBy()'>";
    $sel = ($orderBy == 0) ? "selected" : "";
    echo "<option value='0' $sel>Terbaru</option>";
    $sel = ($orderBy == 1) ? "selected" : "";
    echo "<option value='1' $sel>Nama</option>";
    $sel = ($orderBy == 2) ? "selected" : "";
    echo "<option value='2' $sel>No Pendaftaran</option>";
    $sel = ($orderBy == 3) ? "selected" : "";
    echo "<option value='3' $sel>Sumbangan 1</option>";
    $sel = ($orderBy == 4) ? "selected" : "";
    echo "<option value='4' $sel>Sumbangan 2</option>";
    $sel = ($orderBy == 5) ? "selected" : "";
    echo "<option value='5' $sel>Ujian 1</option>";
    $sel = ($orderBy == 6) ? "selected" : "";
    echo "<option value='6' $sel>Ujian 2</option>";
    $sel = ($orderBy == 7) ? "selected" : "";
    echo "<option value='7' $sel>Ujian 3</option>";
    $sel = ($orderBy == 8) ? "selected" : "";
    echo "<option value='8' $sel>Ujian 4</option>";
    $sel = ($orderBy == 9) ? "selected" : "";
    echo "<option value='9' $sel>Ujian 5</option>";
    $sel = ($orderBy == 10) ? "selected" : "";
    echo "<option value='10' $sel>Ujian 6</option>";
    $sel = ($orderBy == 11) ? "selected" : "";
    echo "<option value='11' $sel>Ujian 7</option>";
    $sel = ($orderBy == 12) ? "selected" : "";
    echo "<option value='12' $sel>Ujian 8</option>";
    $sel = ($orderBy == 13) ? "selected" : "";
    echo "<option value='13' $sel>Ujian 9</option>";
    $sel = ($orderBy == 14) ? "selected" : "";
    echo "<option value='14' $sel>Ujian 10</option>";
    echo "</select>";
}

function ShowTableCalonSiswaInfo($db)
{
    global $page, $nRowPerPage, $idKelompok, $orderBy, $nData;

    $orderBy = GetOrderBy($orderBy);
    $startIndex = ($page - 1) * $nRowPerPage;

    $sql = "SELECT s.replid, nopendaftaran, nama, s.aktif,
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
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompok' 
            ORDER BY $orderBy
            LIMIT $startIndex, $nRowPerPage";
    $res = $db->QueryDb($sql);
    $nData = mysqli_num_rows($res);
    if ($nData == 0)
    {
        HintInfo::ShowCenter("Belum ada data calon siswa<br>Silahkan klik tombol <b>tambah</b> untuk menambahkannya.");
        echo "<input type='hidden' id='ndata' value='0'>";
        return;
    }

    echo "<input type='hidden' id='ndata' value='$nData'>";
    echo "<table id='table' class='tab tabShadow' width='100%'>";
    echo "<tr>";
    echo "<td class='header' width='3%' align='center'>No</td>";
    echo "<td class='header' width='10%' align='center'>&nbsp;</td>";
    echo "<td class='header' width='*'>Calon Siswa</td>";
    echo "<td class='header' width='25%'>Informasi</td>";
    echo "<td class='header' width='25%'>Kontak</td>";
    echo "<td class='header hide-in-report' width='15%'>&nbsp;</td>";
    echo "</tr>";

    $no = $startIndex;
    while($row = mysqli_fetch_assoc($res))
    {
        $no++;

        $replid = $row['replid'];

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
        echo "<img src='../images/ico/ubah.png' class='cur-hand hide-in-report' onclick='gantiFoto($replid)' style='position: absolute; bottom: 0; right: 0'>";            
        echo "</div>";            
        echo "</td>";
        echo "<td valign='top'>";
        echo "<span id='nama$replid' class='fs-14 fst-bold'>" . $row['nama'] . "</span><br>";
        echo "<span id='nopendaftaran$replid' class='ff-consolas fs-13'>" . $row['nopendaftaran'] . "</span><br>";
        echo "<span id='panggilan$replid' class='fst-italic fs-12 fg-secondary'>" . $row['fpanggilan'] . "</span><br>";
        echo "<span id='spInfoAktif$replid' class='bg-maroon fg-yellow w80 br3' style='display: inline-block; text-align: center'>";
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
        echo "<span class='fg-secondary fst-bold'>Siswa</span><br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>HP:</span> " . LinkWhatsApp($row['fhpsiswa']) . "<br>";
        echo "&nbsp;&nbsp;<span class='fg-secondary'>Email:</span> " . LinkEmail($row['femailsiswa']) . "<br>";
        
        echo "<span class='fg-secondary fst-bold'>Orang Tua</span><br>";
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
        echo "<img src='../images/ico/lihat.png' title='profil' class='cur-hand' onclick='profilCalonSiswa($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/stat01.png' title='dashboard' class='cur-hand' onclick='dashboardCalonSiswa($replid)'>&nbsp;&nbsp;";

        if ($row['aktif'])
            echo "<img id='imAktif$replid' src='../images/ico/aktif.png' title='aktif' class='cur-hand' onclick='setNewAktif($replid, 0)'>&nbsp;&nbsp;";
        else
            echo "<img id='imAktif$replid' src='../images/ico/nonaktif.png' title='tidak aktif' class='cur-hand' onclick='setNewAktif($replid, 1)'>&nbsp;&nbsp;";

        echo "<img src='../images/ico/ubah.png' title='ubah mudah' class='cur-hand' onclick='ubahMudah($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/ubah2x.png' title='ubah lengkap' class='cur-hand' onclick='ubahLengkap($replid)'>&nbsp;&nbsp;";
        echo "<img src='../images/ico/hapus.png' title='hapus' class='cur-hand' onclick='hapusSiswa($replid, \"$row[nopendaftaran]\")'>";
        echo "</td>";

        echo "</tr>";
    }
    echo "</table>";
}

function ShowPageControl($db)
{
    global $page, $nRowPerPage, $idKelompok, $nData;

    if ($nData == 0)
        return;
    
    $sql = "SELECT COUNT(replid)
              FROM jbsakad.calonsiswa s
             WHERE s.idkelompok = '$idKelompok'";
    $nData = $db->ExecuteScalar($sql, 0);

    if ($nData == 0)
        echo "";

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

        $sql = "UPDATE jbsakad.calonsiswa
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

function HapusCalonSiswa()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        $nopendaftaran = RequestData("nopendaftaran", "");

        $db->BeginTrans();

        $sql = "DELETE FROM jbsakad.tambahandatacalon
                 WHERE nopendaftaran = '$nopendaftaran'";
        $db->QueryDb($sql);

        $sql = "DELETE FROM jbsakad.calonsiswa
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        $db->CommitTrans();

        return json_encode([1, "Data berhasil dihapus"]);
    }
    catch(Exception $ex)
    {
        $lastError = $db->LastError();
        $errNo = $lastError[0];

        $db->RollbackTrans();

        if ($errNo == 1451)
        {
            $msg = "Data calon siswa tidak dapat dihapus karena sudah digunakan di data lain.";
            return json_encode([-1, $msg]);
        }
        
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }  
}
?>