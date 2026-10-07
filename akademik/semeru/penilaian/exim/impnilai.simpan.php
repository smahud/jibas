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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/logger.php');
require_once('../../library/datearith.php');
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once("impnilai.process.func.php");
require_once('../data/penilaian.rerata.func.php');

$db = new Db;
$db->TryOpenExit(true);

try
{
    require_once("impnilai.simpan.validate.php");

    $departemen = $_REQUEST['departemen'];
    $idpelajaran = $_REQUEST['pelajaran'];
    $idkelas = $_REQUEST['idkelas'];
    $idaturan = $_REQUEST['idaturan'];
    $kodeujian = SafeText($_REQUEST['kodeujian']);
    $nnilai = $_REQUEST['nnilai'];

    $thn = $_REQUEST['tahun'];
    $bln = $_REQUEST['bulan'];
    $tgl = $_REQUEST['tanggal'];

    $tanggal = "$thn-$bln-$tgl";

    $idrpp = $_REQUEST['idrpp'];
    $keterangan = SafeText($_REQUEST['materi']);

    $sql = "SELECT replid 
              FROM jbsakad.semester 
             WHERE departemen = '$departemen' 
               AND aktif = 1";
    $idsemester = (int) $db->FetchSingle($sql, 0);

    $sql = "SELECT idjenisujian
              FROM jbsakad.aturannhb
             WHERE replid = '$idaturan'";
    $idjenisujian = (int) $db->FetchSingle($sql, 0);

    $db->BeginTrans();

    $sql = "SELECT nilaiAU, replid, keterangan 
              FROM jbsakad.nau 
             WHERE idkelas = '$idkelas' 
               AND idsemester = '$idsemester' 
               AND idaturan = '$idaturan'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) > 0)
    {
        $sql = "DELETE FROM jbsakad.nau 
                 WHERE idkelas = '$idkelas' 
                   AND idsemester = '$idsemester' 
                   AND idaturan = '$idaturan'";
        $db->QueryDb($sql);
    }

    $rpp = $idrpp == -1 ? "NULL" : "'$idrpp'";

    $sql = "INSERT INTO jbsakad.ujian 
               SET idpelajaran = '$idpelajaran', 
                   idkelas = '$idkelas', 
                   idsemester = '$idsemester', 
                   idjenis = '$idjenisujian', 
                   deskripsi = '$keterangan', 
                   tanggal = '$tanggal', 
                   idaturan = '$idaturan', 
                   kode = '$kodeujian',
                   idrpp = $rpp";
    $db->QueryDb($sql);

    $sql = "SELECT LAST_INSERT_ID()";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $idujian = $row[0];

    for($i = 1; $i <= $nnilai; $i++)
    {
        $param = "nis$i";
        $nis = $_REQUEST[$param];

        $param = "nilai$i";
        $nilai = $_REQUEST[$param];

        $param = "keterangan$i";
        $ket = SafeText($_REQUEST[$param]);
        
        $sql = "INSERT INTO jbsakad.nilaiujian 
                   SET nilaiujian = '$nilai', 
                       nis = '$nis', 
                       idujian = '$idujian', 
                       keterangan = '$ket'";
        $db->QueryDb($sql);

        HitungRataSiswa($db, $idkelas, $idsemester, $idaturan, $nis);
    }

    HitungRataKelasUjian($db, $idkelas, $idsemester, $idujian);

    $db->CommitTrans();

    echo "<br><font style='font-size: 16px; color: blue;'>Data Nilai Telah Berhasil di Impor!</font>";
}
catch (Exception $ex)
{
    $db->RollbackTrans();

    echo "<br><font style='font-size: 16px; color: red;'>Impor Data Nilai Gagal!<br>" . $ex->getMessage() . "</font>";
}
finally
{
    $db->Close();
}   
