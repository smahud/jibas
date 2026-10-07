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
$nRowPerPage = 15;

function NamaStatusNilai($nilai, $nilaiKkm, $statusUjian)
{
    if ($statusUjian != 2)
        return "--";
    
    if ($nilai >= $nilaiKkm)
        return "Lulus";
    
    return "Kurang";
}

function NamaStatusUjianBerakhir($status, $isRemed, $berakhir)
{
    $statusUjian = $isRemed == 1 ? "Remedial, " : "";

    if ($status == 0) // Progress
    {
        if ($berakhir == 1)
            $statusUjian .= "Berakhir, hasil belum dihitung";
        else
            $statusUjian .= "Sedang Berlangsung";
    }

    if ($status == -1) //EStatusUjian.Pending)
        $statusUjian .= "Pending";

    if ($status == 1) //EStatusUjian.FinishWait)
        $statusUjian .= "Tunggu Verifikasi Esai";

    if ($status == 2) //EStatusUjian.FinishAll)
        $statusUjian .= "Selesai";

    return $statusUjian;
}

function ShowTableHasilUjian($db)
{
    global $skalaNilai, $kkm, $idUjian, $idRemedUjian, $idUjianInUjianSerta, $page, $nRowPerPage;

    $startIndex = ($page - 1) * $nRowPerPage;
    
    $sql = "SELECT u.id, u.nis, s.replid AS idsiswa, 
                   u.jbenar, u.jsalah, u.tbobot, u.tnilai, 
                   u.nilai, u.elapsed, s.nama AS siswa,
                   u.idujian, IFNULL(u.idujianremed, 0) AS idujianremed,
                   DATE_FORMAT(u.tanggal, '%d-%m-%Y %H:%i') AS ftanggal, u.status 
              FROM jbscbe.ujianserta u, jbsakad.siswa s 
             WHERE u.nis = s.nis
               AND u.idujian = TBD_IDUJIAN
               AND u.status IN (1,2)";

    if ($idRemedUjian != 0)
    {
        $sql .= " AND u.idujianremed = TBD_IDREMEDUJIAN";
        $sql = str_replace("TBD_IDUJIAN", $idUjianInUjianSerta, $sql);
        $sql = str_replace("TBD_IDREMEDUJIAN", $idRemedUjian, $sql);
    }
    else
    {
        $sql .= " AND u.lastdata = 1";
        $sql = str_replace("TBD_IDUJIAN", $idUjian, $sql);
    }
    $sql .= " ORDER BY u.nilai DESC
              LIMIT $startIndex, $nRowPerPage";

    $res = $db->QueryDb($sql);
    echo "<table class='tab tabShadow' id='tableUjian' border='1' align='left' cellpadding='3'>";
    echo "<tr height='25' align='left'>";
    echo "<td width='40' class='bg-table-header' align='center'>No</td>";
    echo "<td class='bg-table-header' width='400'>Siswa</td>";
    echo "<td class='bg-table-header' width='70' align='center'>Nilai</td>";
    echo "<td class='bg-table-header' width='70' align='center'>Status</td>";
    echo "<td class='bg-table-header' width='70' align='center'>Benar</td>";
    echo "<td class='bg-table-header' width='70' align='center'>Salah</td>";
    echo "<td class='bg-table-header' width='80' align='center'>Waktu</td>";
    echo "</tr>";

    $cf = new ColorFactory(0, $skalaNilai);
    $no = $startIndex;
    while($row = mysqli_fetch_array($res))
    {
        $no += 1;
        $nis = $row['nis'];
        $siswa = $row['siswa'];
        $idSiswa = $row['idsiswa'];
        $nilai = $row['nilai'];
        $status = $row['status'];
        $jbenar = $row['jbenar'];
        $jsalah = $row['jsalah'];
        $elapsed = $row['elapsed'];
        $ftanggal = $row['ftanggal'];
        $namaStatusNilai = NamaStatusNilai($nilai, $kkm, $status);
        $nilaiColor = $cf->GetColorCode($nilai);

        echo "<tr height='25' align='left' valign='center'>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td style='position: relative;'>";
        echo "<b>$siswa</b><br><span class='fg-secondary'>$nis</span>";
        echo "<span style='position: absolute; right: 5px; top: 5px;'>";
        echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='profilSiswa($idSiswa)'>&nbsp;&nbsp;";
        echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='dashboardSiswa($idSiswa)'>&nbsp;&nbsp;";
        echo "</span>";
        echo "</td>";
        echo "<td align='center' style='background-color: $nilaiColor; color: white;'>$nilai</td>";
        echo "<td align='center'>$namaStatusNilai</td>";
        echo "<td align='center'>$jbenar</td>";
        echo "<td align='center'>$jsalah</td>";
        echo "<td align='center'>$elapsed menit</td>";
        echo "</tr>";
    }
    echo "</table>";
}

function ShowPageControl()
{
    global $nSiswa, $nRowPerPage;

    $nPage = ceil($nSiswa / $nRowPerPage);

    echo "Halaman ";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='<' onclick='onPrevPage()'>";
    echo "<select id='page' class='inputbox' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo "<input type='button' class='but' style='height: 25px; width: 25px;' value='>' onclick='onNextPage()'>";
    echo " dari $nPage, jumlah $nSiswa data";
    echo "<input type='hidden' id='npage' value='$nPage'>";
    echo "<input type='hidden' id='nsiswa' value='$nSiswa'>";
}

?>