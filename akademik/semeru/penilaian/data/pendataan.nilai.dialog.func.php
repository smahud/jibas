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
function ShowSelectRpp($db)
{
    global $idTingkat, $idSemester, $idPelajaran;

    try
    {
        $sql = "SELECT r.replid, CONCAT(r.koderpp, ' - ', r.rpp) AS rpp 
                  FROM jbsakad.rpp r, jbsakad.semester s
                 WHERE r.idsemester = s.replid
                   AND r.idtingkat = '$idTingkat' 
                   AND r.idpelajaran = '$idPelajaran' 
                   AND r.aktif = 1 
                 ORDER BY s.aktif DESC, r.urutan, r.koderpp";
        $res = $db->QueryDb($sql);
        
        echo "<select id='rpp' class='inputbox' style='width:260px'>";
        echo "<option value=''>Tanpa RPP</option>";
        while ($row = mysqli_fetch_array($res)) 
        {
            echo "<option value='$row[replid]'>$row[rpp]</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo Msg::InfoError($ex->getMessage(), "kzyqk");
    }
}

function ShowTableNilaiSiswa($db)
{
    global $idKelas;

    echo "<table class='tab tabShadow' id='tableSiswa' border='1' width='100%' align='left' cellpadding='3'>";
    echo "<tr height='25' class='bg-table-header' align='left'>";
    echo "<td width='8%' align='center'>No</td>";
    echo "<td width='6%' align='center' class='tdcheck' style='display: none;'>";
    echo "<input type='checkbox' id='cekall' class='inputbox' onchange='onCheckAll()'>";
    echo "</td>";
    echo "<td>Siswa</td>";
    echo "<td width='15%' align='center'>Nilai</td>";
    echo "<td width='20%' align='center'>Keterangan</td>";
    echo "</tr>";

    $no = 0;
    $sql = "SELECT nis, nama 
              FROM jbsakad.siswa 
             WHERE idkelas ='$idKelas' 
               AND aktif = 1 
               AND alumni = 0 
             ORDER BY nama ASC";
    $res = $db->QueryDb($sql);
    $nSiswa = mysqli_num_rows($res);
    while ($row = mysqli_fetch_array($res))
    {
        $no++;

        echo "<tr>";
        echo "<td align='center' class='bg-table-number-column'>$no</td>";
        echo "<td align='center' class='tdcheck' style='display: none;'>";
        echo "<input type='checkbox' id='cek$no' class='inputbox'>";
        echo "</td>";
        echo "<td>";
        echo "<b>$row[nama]</b><br><span class='fg-secondary'>$row[nis]</span>";
        echo "<input type='hidden' id='nis$no' value='$row[nis]'>";
        echo "</td>";
        echo "<td align='center'><input type='text' id='nilai$no' class='inputbox fs-14' maxlength='5' style='width:50px'></td>";
        echo "<td align='center'><input type='text' id='keterangan$no' class='inputbox fs-11' maxlength='50' style='width:80px'></td>";
        echo "</tr>";
    }        
    echo "</table>";
    echo "<input type='hidden' id='nsiswa' value='$nSiswa'>";
}

function SimpanNilai()
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $idTahunAjaran = RequestData("idtahunajaran", "");
        $idSemester = RequestData("idsemester", "");
        $idTingkat = RequestData("idtingkat", "");
        $idKelas = RequestData("idkelas", "");
        $kelas = RequestData("kelas", "");
        $idPelajaran = RequestData("idpelajaran", "");
        $pelajaran = RequestData("pelajaran", "");
        $idJenisUjian = RequestData("idjenisujian", "");
        $jenisUjian = RequestData("jenisujian", "");
        $idAturanNhb = RequestData("idaturannhb", "");

        $tanggal = RequestData("tanggal", date("Y-m-d"));
        $materi = RequestData("materi", "");
        $kode = RequestData("kode", "");
        $nSiswa = RequestData("nsiswa", "");
        $rpp = RequestData("rpp", "");
        $rppValue = $rpp == "" ? "NULL" : "'$rpp'";
        $nip = RequestData("nip", "");
        $nama = RequestData("nama", "");

        $db->BeginTrans();

        $sql = "SELECT 1
                  FROM jbsakad.nau 
                 WHERE idkelas = '$idKelas' 
                   AND idsemester = '$idSemester' 
                   AND idaturan = '$idAturanNhb'";
        $res = $db->QueryDb($sql);
        if(mysqli_num_rows($res) > 0)
        {
            $sql = "DELETE FROM jbsakad.nau 
                     WHERE idkelas = '$idKelas' 
                       AND idsemester = '$idSemester' 
                       AND idaturan = '$idAturanNhb'";
            $db->QueryDb($sql);
        }

        $sql = "INSERT INTO jbsakad.ujian 
                   SET idpelajaran = '$idPelajaran', idkelas = '$idKelas', 
			           idsemester = '$idSemester', idjenis = '$idJenisUjian', deskripsi = '$materi', 
			           tanggal = '$tanggal', idaturan = '$idAturanNhb', kode = '$kode', idrpp = $rppValue";
        $db->QueryDb($sql);

        $sql = "SELECT LAST_INSERT_ID()";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $idUjian = $row[0];

        for($i = 1; $i <= $nSiswa; $i++)
        {
            $nis = RequestData("nis$i", "");
            $nilai = RequestData("nilai$i", 0);
            $keterangan = RequestData("keterangan$i", "");

            $sql = "INSERT INTO jbsakad.nilaiujian 
                       SET idujian = '$idUjian', nis = '$nis', 
                           nilaiujian = '$nilai', keterangan = '$keterangan'";
            $db->QueryDb($sql);

            HitungRataSiswa($db, $idKelas, $idSemester, $idAturanNhb, $nis);
        }

        HitungRataKelasUjian($db, $idKelas, $idSemester, $idUjian);

        $deskripsi = "Pendataan nilai $jenisUjian $pelajaran kelas $kelas tanggal " . LongDateFormat($tanggal) . " guru $nama ($nip)";
        RiwayatInput::Save($db, $departemen, "NU", "1", $idUjian, $deskripsi);
       
        $db->CommitTrans();

        ShowToastAfterLoad("Nilai telah tersimpan");

        return json_encode([1, "OK"]);
    }
    catch(Exception $e)
    {
        $db->LogLastErrorIfExist();
        $db->RollbackTrans();

        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>