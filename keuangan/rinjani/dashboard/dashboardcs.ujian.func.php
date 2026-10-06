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
function ShowSelectPelajaranUjianCbeCs($db)
{
    global $nic;

    $sql = "SELECT DISTINCT p.idpelajaran, pel.nama 
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p, jbsakad.pelajaran pel
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND p.idpelajaran = pel.replid
               AND us.nic = '$nic'
               AND p.status = 1
             ORDER BY pel.nama DESC";
    $res = $db->QueryDb($sql);                  

    echo "<select id='pelajarancbe' class='inputbox' style='width: 250px' onchange='onChangePelajaranCbe()'>";
    echo "<option value='0' selected>Semua Pelajaran</option>";
    while($row = mysqli_fetch_row($res))
    {
        echo "<option value='$row[0]'>$row[1]</option>";
    }  
    echo "</select>";
}

function ShowSelectJumlahDataCbe()
{
    echo "<select id='jumlahdata' onchange='onChangeJumlahDataCbe()' class='inputbox' style='width:80px'>";
    echo "<option value='5' selected>5</option>";
    echo "<option value='10'>10</option>";
    echo "<option value='20'>20</option>";
    echo "</select>";
}

function ShowLaporanNilaiUjianCbeCs($db)
{
    global $nic, $idPelajaran, $jumlahData;

    $sql = "SELECT DISTINCT IFNULL(us.idujianremed, us.idujian), 
                   DATE_FORMAT(us.tanggal, '%d-%b-%Y %H:%i'), 
                   p.idpelajaran, pel.nama, 
                   us.id AS idujianserta, DATEDIFF(NOW(), us.tanggal)
              FROM jbscbe.ujian u, jbscbe.ujianserta us, jbscbe.pengujian p, jbsakad.pelajaran pel
             WHERE u.id = us.idujian
               AND u.idpengujian = p.id
               AND p.idpelajaran = pel.replid
               AND us.nic = '$nic'
               AND p.status = 1";

    if ($idPelajaran != 0)               
        $sql .= " AND p.idpelajaran = $idPelajaran";
    
    $sql .= " ORDER BY us.tanggal DESC LIMIT $jumlahData";

    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        echo "<br><i>belum ada data nilai ujian CBE</i>";
        return;
    }

    $lsInit = [];
    while($row = mysqli_fetch_row($res))
    {
        // idujian, tanggal, idpelajaran, nama pelajaran, idujianserta, diff tanggal
        $lsInit[] = [$row[0], $row[1], $row[2], $row[3], $row[4], $row[5]];
    }

    if (count($lsInit) == 0)
    {
        echo "<br><i>belum ada data nilai ujian CBE</i>";
        return;
    }

    $lsUjian = [];
    for($i = 0; $i < count($lsInit); $i++)
    {
        $idUjian = $lsInit[$i][0];
        $tanggal = $lsInit[$i][1];
        $idPelajaran = $lsInit[$i][2];
        $namaPelajaran = $lsInit[$i][3];
        $idUjianSerta = $lsInit[$i][4];
        $diffTanggal = $lsInit[$i][5];

        $sql = "SELECT u.id, IFNULL(u.idremedujian, 0), u.judul
                  FROM jbscbe.ujian u 
                 WHERE u.id = '$idUjian'";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))                 
        {
            // idujian, idremedujian, judul
            $idUjian2 = $row[0];
            $idRemedUjian = $row[1];
            $judul = $row[2];

            $lsUjian[] = [$idUjian2, $idRemedUjian, $judul, $tanggal, $idPelajaran, $namaPelajaran, $idUjianSerta, $diffTanggal ];
        }
    }

    if (count($lsUjian) == 0)
    {
        echo "<br><i>belum ada data nilai ujian CBE</i>";
        return;
    }

    $lsResult = [];
    for($i = 0; $i < count($lsUjian); $i++)
    {
        $idUjian = $lsUjian[$i][0];
        $idRemedUjian = $lsUjian[$i][1];
        $judul = $lsUjian[$i][2];
        $tanggal = $lsUjian[$i][3];
        $idPelajaran = $lsUjian[$i][4];
        $pelajaran = $lsUjian[$i][5];
        $idUjianSerta = $lsUjian[$i][6];
        $diffTanggal = $lsUjian[$i][7];

        $idUjianInUjianSerta = $idRemedUjian != 0 ? $idRemedUjian : $idUjian;

        $sql = "SELECT COUNT(id) 
                  FROM jbscbe.ujianserta
                 WHERE idujian = '$idUjianInUjianSerta'
                   AND nic = '$nic'
                   AND idujianremed IS NOT NULL";
        $nData = $db->ExecuteScalar($sql, 0);
        $haveRemed = $nData > 0;

        $sifatUjian = 0;
        $sql = "SELECT p.status
                  FROM jbscbe.ujian u, jbscbe.pengujian p
                 WHERE u.idpengujian = p.id
                   AND u.id = '$idUjian'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_assoc($res))    
            $sifatUjian = $row['status'];

        $skalaNilai = 10;
        $nilaiKkm = 0;
        $viewKey = 1;
        $viewExp = 1;
        $viewResult = 1;
        $viewSoal = 1;
        $viewAfter = 0;
        $sql = "SELECT skalanilai, kkm, viewkey, viewexp, viewresult, viewsoal, viewafter
                  FROM jbscbe.ujian
                 WHERE id = '$idUjianInUjianSerta'";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_assoc($res))                 
        {
            $skalaNilai = $row['skalanilai'];
            $nilaiKkm = $row['kkm'];
            $viewKey = $row['viewkey'];
            $viewExp = $row['viewexp'];
            $viewResult = $row['viewresult'];
            $viewSoal = $row['viewsoal'];
            $viewAfter = $row['viewafter'];
        }

        $sql = "SELECT u.id, u.jbenar, u.jsalah, u.tbobot, u.tnilai, 
                       u.nilai, u.elapsed, u.idujian, 
                       IFNULL(u.idujianremed, 0) AS idujianremed,
                       DATE_FORMAT(u.tanggal, '%d-%b-%Y %H:%i') AS ftanggal, u.status,
                       DATE_FORMAT(u.tanggal, '%Y%m%d%H%i') AS tanggalsort     
                  FROM jbscbe.ujianserta u
                 WHERE u.idujian = '$idUjianInUjianSerta'
                   AND u.nic = '$nic' 
                   AND u.status IN (1,2)";
        if ($sifatUjian == 1)
        {
            if ($idRemedUjian != 0)
                $sql .= " AND u.idujianremed = $idUjian";
            else
                $sql .= " AND u.lastdata = " . ($haveRemed ? 0 : 1);
        }
        $sql .= " ORDER BY u.tanggal DESC";

        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_array($res))
        {
            $nilai = $row['nilai'];
            $statusUjianValue = $row['status'];
            $idUjianRemed = $row['idujianremed'];

            $isRemed = $idUjianRemed == 0 ? 0 : 1;
            $statusUjian = GetStatusUjian($statusUjianValue, $isRemed);
            $statusNilai = GetStatusNilai($nilai, $nilaiKkm, $statusUjianValue);

            $nilaiInfo = "";
            if ($viewResult == 1)
            {
                if ($statusUjianValue == 2)
                {
                    $nilaiInfo = $statusUjianValue != 2 ? "--\n" : $nilai . "\n";
                    $nilaiInfo = "Nilai: " . $nilaiInfo . "<br>";
                    $nilaiInfo .= "Benar: " . $row["jbenar"] . "<br>";
                    $nilaiInfo .= "Salah: " . $row["jsalah"]; 
                }
                else 
                {
                    $nilaiInfo = "Nilai: -<br>Benar: -<br>Salah: -";
                }
            }
            else 
            {
                $nilaiInfo = "(tidak ditampilkan)";
            }

            $ujian = $judul . "<br>";
            $ujian .= "Pelajaran: " . $pelajaran . "<br>";
            $ujian .= "Tanggal: " . $row["ftanggal"] . "<br>";
            $ujian .= "Waktu: " . $row["elapsed"] . " menit";

            $nilaiText = "?";
            $nilaiColor = "#b6b6b6";

            $statusStr = "Nilai KKM: " . $nilaiKkm . "<br>";
            if ($viewResult == 1)
            {
                $statusStr .= "Hasil: " . $statusNilai . "<br>";
                $statusStr .= "Status: " . $statusUjian;

                if ($statusUjianValue == 2)
                {
                    $colorTransition = new ColorFactory(0, $skalaNilai);
                    $warna = $colorTransition->GetColorCode($nilai);
                    $nilaiColor = $warna;
                    $nilaiText = $nilai;
                }   
            }

            $lsDetail = [ $ujian, // 0
                          $nilaiInfo, // 1 
                          $nilaiColor, // 2
                          $statusStr
                        ];

            $lsResult[] = $lsDetail;
        }
    }

    //Peek::PrintR($lsResult);
    echo "<table class='tab' id='tableujiancbe' cellpadding='2' cellspacing='0'>";
    echo "<tr height='30' align='center' class='bg-table-header'>";
    echo "<td width='30'>No</td>";
    echo "<td width='250'>Pelajaran</td>";
    echo "<td width='150'>Nilai</td>";
    echo "<td width='250'>Status</td>";
    echo "</tr>";

    for($i = 0; $i < count($lsResult); $i++)
    {
        $ujian = $lsResult[$i][0];
        $nilaiInfo = $lsResult[$i][1];
        $nilaiColor = $lsResult[$i][2];
        $statusStr = $lsResult[$i][3];

        echo "<tr class='row'>";
        echo "<td class='bg-table-number-column' align='center'>" . ($i + 1) . "</td>";
        echo "<td align='left'>" . $ujian . "</td>";
        echo "<td align='left' style='color: #fff; background-color: $nilaiColor'>" . $nilaiInfo . "</td>";
        echo "<td align='left'>" . $statusStr . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    
}

function GetStatusNilai($nilai, $kkm, $statusUjian)
{
    if ($statusUjian != 2)
        return "--";

    if ($nilai >= $kkm)
        return "Lulus";

    return "Kurang";
}

function GetStatusUjian($status, $isRemed)
{
    $statusUjian = $isRemed == 1 ? "Remedial, " : "";

    if ($status == -1)
        $statusUjian .= "Pending";

    if ($status == 0)
        $statusUjian .= "Sedang Berlangsung";

    if ($status == 1)
        $statusUjian .= "Tunggu Verifikasi Esai";

    if ($status == 2)
        $statusUjian .= "Selesai";

    return $statusUjian;
}
?> 