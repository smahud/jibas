<?php
require_once("../include/sessioninfo.php");
require_once("../include/sessionchecker.php");
require_once("../include/config.php");
require_once("../include/db.onfunc.php");
require_once("../library/msg.php");
require_once("../library/hintinfo.php");
require_once("../library/common.func.php");
require_once("../util/peek.php");
require_once("../library/departemen.php");
require_once('identitas.func.php');

$op = RequestData("op", "");
if ($op == "identitas")
{
    $db = new Db();
    try
    {
        $db->Open();

        $departemen = RequestData("departemen", "");
        ShowTableIdentitas($db);
    }
    catch (Exception $e)
    {
        echo $e->getMessage();
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "hapus")
{
    echo HapusIdentitas();
}