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
function LoadJam($db) 
{
    global $departemen;

    $sql = "SELECT jamke, TIME_FORMAT(jam1, '%H:%i'), TIME_FORMAT(jam2, '%H:%i'), IFNULL(keterangan, '')
              FROM jbsakad.jam 
             WHERE departemen = '$departemen' 
             ORDER BY jamke";

    $result = $db->QueryDb($sql);
    $maxJam = mysqli_num_rows($result);

    $jam = [];
    while ($row = mysqli_fetch_row($result)) 
    {
        $jam[$row[0]]['jam1'] = $row[1];
        $jam[$row[0]]['jam2'] = $row[2];
        $jam[$row[0]]['keterangan'] = $row[3];
    }

    return ['jam' => $jam, 'maxJam' => $maxJam];
}

function LoadJadwal($db) 
{
    global $nip, $departemen, $idKategori;

    $sql = "SELECT j.replid AS id, j.hari AS hari, j.jamke AS jam, j.njam AS njam, 
                   j.keterangan AS ket, l.nama AS pelajaran, k.kelas, 
                   CASE j.status WHEN 0 THEN 'Mengajar' WHEN 1 THEN 'Asistensi' WHEN 2 THEN 'Tambahan' END AS status 
              FROM jbsakad.jadwal j, jbsakad.pelajaran l, jbsakad.kelas k 
             WHERE j.nipguru = '$nip'
               AND j.departemen = '$departemen'
               AND j.infojadwal = $idKategori
               AND j.idkelas = k.replid 
               AND j.idpelajaran = l.replid";
    $res = $db->QueryDb($sql);

    $jadwal = [];
    while ($row = mysqli_fetch_assoc($res)) 
    {
        $jadwal[$row['hari']][$row['jam']] = [
            'id'        => $row['id'],
            'njam'      => $row['njam'],
            'pelajaran' => $row['pelajaran'],
            'kelas'     => $row['kelas'],
            'status'    => $row['status'],
            'ket'       => $row['ket'],
        ];
    }

    return $jadwal;
}

function GetCell($r, $c, &$mask, $jadwal)
{
    if ($mask[$c] == 0) 
    {
        if (isset($jadwal[$c][$r])) 
        {
            $mask[$c] = $jadwal[$c][$r]['njam'] - 1;
            $entry = $jadwal[$c][$r];

            $s  = "<td class='jadwal' rowspan='{$entry['njam']}' width='110px' style='position: relative' onMouseOver='cellHover(this)' onclick='cellClick(this)' onMouseOut='cellOut(this)'>";
            $s .= "{$entry['kelas']}<br>";
            $s .= "<b>{$entry['pelajaran']}</b><br>";
            $s .= "<i>{$entry['status']}</i><br><br>";
            $s .= "<img class='cur-hand hide-in-report'  src='../images/ico/ubah.png' onclick='edit({$entry['id']})'> &nbsp;";
            $s .= "<img class='cur-hand hide-in-report' src='../images/ico/hapus.png' onclick='hapus({$entry['id']})'>";

            $ket = trim($entry['ket']);
            if ($ket != "")
            {
                $s .= "<div onclick='showKeterangan($r, $c)' style='position: absolute; bottom: 5px; left: 2px; right: 2px;' class='fg-secondary fst-italic cur-hand'>";
                $s .= "<input type='hidden' id='ket-$r-$c' value='$ket'>";
                if (strlen($ket) > 15)
                    $ket = substr($ket, 0, 15) . "...";
                $s .= $ket;
                $s .= "</div>";
            }
            
            $s .= "</td>";

            return $s;
        } 
        else 
        {
            $s  = "<td class='jadwal' width='110px' onMouseOver='cellHover(this)' onclick='cellClick(this)' onMouseOut='cellOut(this)'>";
            $s .= "<img class='cur-hand hide-in-report' src='../images/ico/tambah.png' onclick='tambah($r, $c)'>";
            $s .= "</td>";

            return $s;
        }
    } 
    else 
    {
        --$mask[$c];
        return '';
    }
}

function HapusJadwal()
{
    $db = new Db();
    try
    {
        $db->Open();

        $replid = RequestData("replid", 0);
        
        $sql = "DELETE FROM jbsakad.jadwal 
                 WHERE replid = '$replid'";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
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

function HapusJadwalGuru()
{
    $db = new Db();
    try
    {
        $db->Open();

        $nip = RequestData("nip", "");
        $idKategori = RequestData("idkategori", 0);
        
        $sql = "DELETE FROM jbsakad.jadwal 
                 WHERE nipguru = '$nip'
                   AND infojadwal = $idKategori";
        $db->QueryDb($sql);

        return json_encode([1, "Data berhasil dihapus"]);
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
?>