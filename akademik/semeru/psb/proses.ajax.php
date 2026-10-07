<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('proses.func.php');

$op = $_REQUEST['op'];

if ($op == 'count')
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData('departemen', '');
        $nData = CountData($db);
        
        echo json_encode([1, $nData]);
    }
    catch (Exception $ex)
    {
        echo json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "daftar")
{
    $db = new Db;
    try
    {
        $db->Open();

        $departemen = RequestData('departemen', '');
        $page = RequestData('page', 1);
        $nData = RequestData('ndata', 0);

        ShowTableProses($db);
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
else if ($op == "pagecontrol")
{
    $page = RequestData('page', 1);
    $nData = RequestData('ndata', 0);

    ShowPageControl();
}
else if ($op == "save")
{
    echo SaveProses();
}
else if ($op == "setaktif")
{
    echo SetAktifProses();
}
else if ($op == "hapus")
{
    echo HapusProses();
}
?>