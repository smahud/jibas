<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('../library/departemen.php');
require_once('settingpsb.func.php');

$op = RequestData("op", "");
if ($op == "simpan")
{
    echo SimpanSettingPsb();
}
else if ($op == "prosespsb")
{
    $db = new Db();
    try
    {
        $db->Open();
         
        $departemen = RequestData("departemen", "");
        $idProsesPsb = 0;

        ShowSelectProsesPenerimaan($db);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "settingpsb")
{
    $db = new Db();
    try
    {
        $db->Open();
         
        $idProsesPsb = RequestData("idprosespsb", 0);

        ShowTableSettingPsb($db);
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}