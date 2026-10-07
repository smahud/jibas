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

function PrepareSearchData($db)
{
    global $jenisCari, $cari, $departemen, $idTingkat, $idKelas, $nRowPerPage;

    try
    {
        if ($idKelas == 0 && $idTingkat == 0)
        {
            $sql = "SELECT COUNT(s.replid), GROUP_CONCAT(s.replid)
                    FROM jbsakad.siswa s, jbsakad.angkatan a
                    WHERE s.idangkatan = a.replid
                    AND a.departemen = '$departemen'";
        }
        else if ($idKelas == 0)
        {
            $sql = "SELECT COUNT(s.replid), GROUP_CONCAT(s.replid)
                    FROM jbsakad.siswa s, jbsakad.tingkat t, jbsakad.kelas k
                    WHERE s.idkelas = k.replid
                    AND k.idtingkat = t.replid 
                    AND t.replid = '$idTingkat'";
        }
        else 
        {
            $sql = "SELECT COUNT(s.replid), GROUP_CONCAT(s.replid)
                    FROM jbsakad.siswa s
                    WHERE s.idkelas = '$idKelas'";
        }

        if ($jenisCari == "nis")
        {
            $sql .= " AND s.nis LIKE '%$cari%'";
        }
        else if ($jenisCari == "nama")
        {
            $sql .= " AND s.nama LIKE '%$cari%'";
        }
        else if ($jenisCari == "nisn")
        {
            $sql .= " AND s.nisn LIKE '%$cari%'";
        }
        else if ($jenisCari == "nik")
        {
            $sql .= " AND s.nik LIKE '%$cari%'";
        }
        else if ($jenisCari == "panggilan")
        {
            $sql .= " AND s.panggilan LIKE '%$cari%'";
        }
        else if ($jenisCari == "aktif")
        {
            $aktif = 1;
            if ($cari == "Tidak Aktif")
                $aktif = 0;
            
            $sql .= " AND s.aktif = $aktif";
        }
        else if ($jenisCari == "pinsiswa")
        {
            $sql .= " AND s.pinsiswa LIKE '%$cari%'";
        }
        else if ($jenisCari == "agama")
        {
            if ($cari == "---")
                $sql .= " AND s.agama IS NULL";
            else                 
                $sql .= " AND s.agama = '$cari'";
        }
        else if ($jenisCari == "suku")
        {
            if ($cari == "---")
                $sql .= " AND s.suku IS NULL";
            else                 
                $sql .= " AND s.suku = '$cari'";
        }
        else if ($jenisCari == "status")
        {
            if ($cari == "---")
                $sql .= " AND s.status IS NULL";
            else                 
                $sql .= " AND s.status = '$cari'";
        }
        else if ($jenisCari == "kondisi")
        {
            if ($cari == "---")
                $sql .= " AND s.kondisi IS NULL";
            else                 
                $sql .= " AND s.kondisi = '$cari'";
        }
        else if ($jenisCari == "kelamin")
        {
            if ($cari == "---")
                $sql .= " AND s.kelamin IS NULL";
            else                 
                $sql .= " AND s.kelamin = '$cari'";
        }
        else if ($jenisCari == "pendidikan")
        {
            if ($cari == "---")
                $sql .= " AND (s.pendidikanayah IS NULL OR s.pendidikanibu IS NULL)";
            else                 
                $sql .= " AND (s.pendidikanayah = '$cari' OR s.pendidikanibu = '$cari')";
        }
        else if ($jenisCari == "darah")
        {
            if ($cari == "---")
                $sql .= " AND s.darah IS NULL";
            else                
                $sql .= " AND s.darah = '$cari'";
        }
        else if ($jenisCari == "alamat")
        {
            $sql .= " AND (s.alamatsiswa LIKE '%$cari%' OR s.alamatortu LIKE '%$cari%')";
        }
        else if ($jenisCari == "jarak")
        {
            if ($cari == "j1")
                $sql .= " AND s.jarak >= '0' AND s.jarak < '5'";
            else if ($cari == "j2")
                $sql .= " AND s.jarak >= '5' AND s.jarak < '10'";
            else if ($cari == "j3")
                $sql .= " AND s.jarak >= '10' AND s.jarak < '15'";
            else if ($cari == "j4")
                $sql .= " AND s.jarak >= '15'";
            else if ($cari == "---")
                $sql .= " AND s.jarak IS NULL";
        }
        else if ($jenisCari == "hp")
        {
            $sql .= " AND (s.hpsiswa LIKE '%$cari%' OR s.hportu LIKE '%$cari%' OR s.info1 LIKE '%$cari%' OR s.info2 LIKE '%$cari%')";
        }
        else if ($jenisCari == "email")
        {
            $sql .= " AND (s.emailsiswa LIKE '%$cari%' OR s.emailayah LIKE '%$cari%' OR s.emailibu LIKE '%$cari%')";
        }
        else if ($jenisCari == "asalsekolah")
        {
            $sql .= " AND s.asalsekolah LIKE '%$cari%'";
        }
        else if ($jenisCari == "namaortu")
        {
            $sql .= " AND (s.namaayah LIKE '%$cari%' OR s.namaibu LIKE '%$cari%')";
        }
        else if ($jenisCari == "penghasilan")
        {
            if ($cari == "p1")
                $sql .= " AND (s.penghasilanayah  + s.penghasilanibu < 2000000)";
            else if ($cari == "p2")
                $sql .= " AND (s.penghasilanayah + s.penghasilanibu >= 2000000 AND s.penghasilanayah + s.penghasilanibu < 5000000)";
            else if ($cari == "p3")
                $sql .= " AND (s.penghasilanayah + s.penghasilanibu >= 5000000 AND s.penghasilanayah + s.penghasilanibu < 10000000)";
            else if ($cari == "p4")
                $sql .= " AND (s.penghasilanayah + s.penghasilanibu >= 10000000)";
            else if ($cari == "---")
                $sql .= " AND (s.penghasilanayah IS NULL OR s.penghasilanibu IS NULL)";
        }

        $sql .= " ORDER BY s.nama ASC ";

        $res = $db->QueryDb($sql);

        $row = mysqli_fetch_row($res);
        $nData = $row[0];
        $stReplid = $row[1];
        if ($nData == 0)
            return [0, "Data tidak ditemukan", 0, 0, ""];

        $lsIdPage = [];
        if (strpos($stReplid, ",") === false)
        {
            $nPage = 1;
            $lsIdPage = [[ $stReplid ]];
        }
        else 
        {
            $nPage = ceil($nData / $nRowPerPage);
            $arrId = explode(",", $stReplid);

            $n = 0;
            $arrIdPage = [];
            for($i = 0; $i < count($arrId); $i++)
            {
                if ($n < $nRowPerPage)
                {
                    $arrIdPage[] = $arrId[$i];
                    $n += 1;
                }

                if ($n == $nRowPerPage)
                {
                    $lsIdPage[] = $arrIdPage;

                    $n = 0;
                    $arrIdPage = [];
                }
            }

            if (count($arrIdPage) > 0)
                $lsIdPage[] = $arrIdPage;
        }

        //Peek::Show($sql);
        //Peek::PrintR($lsIdPage);
        
        return [1, "OK", $nData, $nPage, $lsIdPage];            
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        return [-1, $ex->getMessage(), 0, 0, ""];
    }
}

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

function ShowSearchResult($db)
{
    global $page, $jenisCari, $jenisCariText, $stReplid, $nRowPerPage;

    try
    {
        $columnCari = "";
        if ($jenisCari == "nis")
        {
            $columnCari = "s.nis AS hasilcari";
        }
        else if ($jenisCari == "nama")
        {
            $columnCari = "s.nama AS hasilcari";
        }
        else if ($jenisCari == "nisn")
        {
            $columnCari = "s.nisn AS hasilcari";
        }
        else if ($jenisCari == "nik")
        {
            $columnCari = "s.nik AS hasilcari";
        }
        else if ($jenisCari == "panggilan")
        {
            $columnCari = "s.nama AS hasilcari";
        }
        else if ($jenisCari == "pinsiswa")
        {
            $columnCari = "s.pinsiswa AS hasilcari";
        }
        else if ($jenisCari == "aktif")
        {
            $columnCari = "IF(s.aktif = 1, 'Aktif', 'Tidak Aktif') AS hasilcari";
        }
        else if ($jenisCari == "agama")
        {
            $columnCari = "s.agama AS hasilcari";
        }
        else if ($jenisCari == "suku")
        {
            $columnCari = "s.suku AS hasilcari";
        }
        else if ($jenisCari == "status")
        {
            $columnCari = "s.status AS hasilcari";
        }
        else if ($jenisCari == "kelamin")
        {
            $columnCari = "IF(s.kelamin = 'l', 'Laki-laki', 'Perempuan') AS hasilcari";
        }
        else if ($jenisCari == "kondisi")
        {
            $columnCari = "s.kondisi AS hasilcari";
        }
        else if ($jenisCari == "pendidikan")
        {
            $columnCari = "CONCAT('Ayah: ', IFNULL(s.pendidikanayah, '---'), '<br>', 'Ibu: ', IFNULL(s.pendidikanibu, '---')) AS hasilcari";
        }
        else if ($jenisCari == "penghasilan")
        {
            $columnCari = "CONCAT('Ayah: ', IFNULL(s.penghasilanayah, '---'), '<br>', 'Ibu: ', IFNULL(s.penghasilanibu, '---')) AS hasilcari";
        }
        else if ($jenisCari == "darah")
        {
            $columnCari = "s.darah AS hasilcari";    
        }
        else if ($jenisCari == "alamat")
        {
            $columnCari = "CONCAT(s.alamatsiswa, '<br>', s.alamatortu) AS hasilcari";
        }
        else if ($jenisCari == "jarak")
        {
            $columnCari = "CONCAT(s.jarak, ' km') AS hasilcari";
        }
        else if ($jenisCari == "hp")
        {
            $columnCari = "CONCAT(s.hpsiswa, '<br>', s.hportu, '<br>', s.info1, '<br>', s.info2) AS hasilcari";
        }
        else if ($jenisCari == "email")
        {
            $columnCari = "CONCAT(s.emailsiswa, '<br>', s.emailayah, '<br>', s.emailibu) AS hasilcari";
        }
        else if ($jenisCari == "asalsekolah")
        {
            $columnCari = "s.asalsekolah AS hasilcari";
        }
        else if ($jenisCari == "namaortu")
        {
            $columnCari = "CONCAT(s.namaayah, '<br>', s.namaibu) AS hasilcari";
        }

        $sql = "SELECT s.replid, nis, nama, s.aktif, $columnCari, 
                       IFNULL(panggilan, '') AS fpanggilan,
                       IF(foto IS NULL, '', TO_BASE64(foto)) AS ffoto,
                       IFNULL(s.keterangan, '') AS fketerangan,
                       t.departemen, t.tingkat, k.kelas,
                       IFNULL(alamatsiswa, '') AS falamatsiswa, 
                       IFNULL(hpsiswa, '') AS fhpsiswa, 
                       IFNULL(emailsiswa, '') AS femailsiswa,
                       IFNULL(telponsiswa, '') AS ftelponsiswa, 
                       IFNULL(telponortu, '') AS ftelponortu, 
                       IFNULL(hportu, '') AS fhportu1,
                       IFNULL(s.info1, '') AS fhportu2,
                       IFNULL(s.info2, '') AS fhportu3,
                       IFNULL(emailayah, '') AS femailayah,
                       IFNULL(emailibu, '') AS femailibu 
                  FROM jbsakad.siswa s, jbsakad.tingkat t, jbsakad.kelas k
                 WHERE s.idkelas = k.replid
                   AND k.idtingkat = t.replid 
                   AND s.replid IN ($stReplid)";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br>baris data tidak ditemukan";
            return;
        }

        echo "<table id='table' class='tab tabShadow' width='100%'>";
        echo "<tr>";
        echo "<td class='header' width='50' align='center'>No</td>";
        echo "<td class='header' width='120' align='center'>&nbsp;</td>";
        echo "<td class='header' width='250'>Siswa</td>";
        echo "<td class='header' width='250'>Kelas</td>";
        echo "<td class='header' width='250'>$jenisCariText</td>";
        echo "<td class='header' width='300'>Kontak</td>";
        echo "<td class='header hide-in-report' width='80'>&nbsp;</td>";
        echo "</tr>";

        $no = ($page - 1) * $nRowPerPage;
        while($row = mysqli_fetch_assoc($res))
        {
            $no += 1;

            $replid = $row['replid'];

            echo "<tr>";
            echo "<td align='center' class='numberColumn'>$no</td>";
            echo "<td align='center' valign='top'>";
            echo "<div style='position: relative'>";
            if (!empty($row['ffoto']))
                echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageSiswa($replid)' src='data:image/jpeg;base64,". $row['ffoto'] . "' border='0' style='width: 60px; height: 80px;'>";
            else
                echo "<img id='img$replid' class='shadow-2 cur-hand' onclick='showImageSiswa($replid)' src='" . NoUserImage() . "' border='0' style='width: 60px; height: 80px;'>";
            echo "</div>";            
            echo "</td>";
            echo "<td valign='top'>";
            echo "<span id='nama$replid' class='fs-14 fst-bold'>" . $row['nama'] . "</span><br>";
            echo "<span id='nis$replid' class='ff-consolas fs-13'>" . $row['nis'] . "</span><br>";
            echo "<span id='panggilan$replid' class='fst-italic fs-12 fg-secondary'>" . $row['fpanggilan'] . "</span><br>";
            echo "<span id='spInfoAktif$replid' class='fg-red fst-italic'>";
            if ($row['aktif'] == 0)
                echo "[Tidak Aktif]";
            echo "</span>";
            echo "</td>";

            echo "<td valign='top'>";
            echo $row['departemen'] . "<br>&nbsp;&nbsp;" . $row['tingkat'] . " " . $row['kelas'] . "<br>";
            echo "</td>";

            echo "<td valign='top'>";
            echo $row['hasilcari'];
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
            echo "</td>";

            echo "</tr>";
        }
        echo "</table>";
        


    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
}

function ShowPageControl()
{
    global $page, $nPage, $nData;

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
    echo "<input type='hidden' id='ndata' value='$nData'>";
    echo "<input type='hidden' id='npage' value='$nPage'>";
}

?>