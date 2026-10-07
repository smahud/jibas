<?php
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('kelompok.func.php');

$op = $_REQUEST['op'];

if ($op == 'count')
{
    $db = new Db();
    try
    {
        $db->Open();

        $idProsesPsb = RequestData('idprosespsb', 0);
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
else if ($op == 'daftar')
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData('departemen', '');
        $idProsesPsb = RequestData('idprosespsb', 0);
        $page = RequestData('page', 1);
        $nData = RequestData('ndata', 0);

        ShowTableKelompok($db);
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
else if ($op == "proses")
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData('departemen', '');
        ShowActiveProses($db);    
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
else if ($op == 'pagecontrol')
{
    $nData = RequestData('ndata', 0);
    
    ShowPageControl();   
}
else if ($op == 'hapus')
{
    echo HapusKelompok();
}
else
{
    echo "OPERATION NOT SUPPORTED";
}
