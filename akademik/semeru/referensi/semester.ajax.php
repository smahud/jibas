<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/hintinfo.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('semester.func.php');

$op = $_REQUEST['op'];
if ($op == "daftar")
{
    $db = new Db;
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        $urut = RequestData("urut", "semester");
        $urutan = RequestData("urutan", "ASC");
        $page = RequestData("page", 1);

        ShowTableSemester($db);
    }
    catch (Exception $ex)
    {
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "setaktif")
{
    echo ChangeAktifSemester();
}
else if ($op == "hapus")
{
    echo HapusSemester();
}
else
{
    echo "OPERATION NOT SUPPORTED";
}
