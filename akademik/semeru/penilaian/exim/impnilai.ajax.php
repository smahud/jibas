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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/departemen.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once("impnilai.process.func.php");

$op = $_REQUEST['op'];
if ($op == "getselectaspek")
{
    $db = new Db();
    try
    {
        $db->Open();

        $idpelajaran = $_REQUEST['idpelajaran'];
        $idtingkat = $_REQUEST['idtingkat'];
        $nip = $_REQUEST['nip'];
        $selaspek = $_REQUEST['selaspek'];

        $select = SelectAspek($db);

        $result = array('idaspek' => $idaspek, 'select' => urlencode($select));
        echo json_encode($result);
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
        http_response_code(500);
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "getselectjenisujian")
{
    $db = new Db();
    try
    {
        $db->Open();

        $idpelajaran = $_REQUEST['idpelajaran'];
        $idaspek = $_REQUEST['idaspek'];
        $idtingkat = $_REQUEST['idtingkat'];
        $idkelas = $_REQUEST['idkelas'];
        $nip = $_REQUEST['nip'];

        $select = SelectJenisUjian($db);

        echo urlencode($select);
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
        http_response_code(500);
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "getselectrpp")
{
    $db = new Db();
    try
    {
        $db->Open();

        $idpelajaran = $_REQUEST['idpelajaran'];
        $idtingkat = $_REQUEST['idtingkat'];
        $idsemester = $_REQUEST['idsemester'];

        $select = SelectRpp($db);

        echo urlencode($select);
        http_response_code(200);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
        http_response_code(500);
    }
    finally
    {
        $db->Close();
    }
}
?>