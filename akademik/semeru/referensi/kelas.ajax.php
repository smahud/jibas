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
require_once('kelas.func.php');

$op = $_REQUEST['op'];
if ($op == "daftar")
{
    $db = new Db;
    try
    {
        $db->Open();

        // Ensure globals are available for ShowTableKelas
        global $departemen, $tahunajaran, $tingkat, $urut, $urutan, $page;

        $departemen = RequestData("departemen", "");
        $tahunajaran = RequestData("tahunajaran", "");
        $tingkat = RequestData("tingkat", "");
        $urut = RequestData("urut", "kelas");
        $urutan = RequestData("urutan", "ASC");
        $page = RequestData("page", 1);

        ShowTableKelas($db);
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
else if ($op == "loadselects")
{
    $db = new Db;
    try
    {
        $db->Open();

        global $departemen, $tahunajaran, $tingkat;

        $departemen = RequestData("departemen", "");
        $tahunajaran = "";
        $tingkat = "";

        // Determine defaults for the selected departemen
        $sql = "SELECT replid FROM jbsakad.tahunajaran WHERE departemen='$departemen' ORDER BY aktif DESC, tglmulai DESC LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $tahunajaran = $row[0];

        $sql = "SELECT replid FROM jbsakad.tingkat WHERE departemen='$departemen' AND aktif=1 ORDER BY urutan LIMIT 1";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
            $tingkat = $row[0];

        // Generate the updated selects
        ob_start();
        ShowSelectTahunAjaran($db);
        $htmlTahunAjaran = ob_get_clean();

        ob_start();
        ShowSelectTingkat($db);
        $htmlTingkat = ob_get_clean();

        echo json_encode([
            "htmlTahunAjaran" => $htmlTahunAjaran,
            "htmlTingkat" => $htmlTingkat
        ]);
    }
    catch (Exception $ex)
    {
        echo json_encode(["error" => $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
else if ($op == "setaktif")
{
    echo ChangeAktifKelas();
}
else if ($op == "hapus")
{
    echo HapusKelas();
}
else
{
    echo "OPERATION NOT SUPPORTED";
}
