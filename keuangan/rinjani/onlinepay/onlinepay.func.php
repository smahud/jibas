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
function CheckAllConfigReady()
{
    global $SJS_ADDR;
    global $PG_SCHOOL_ID, $PG_ADDR, $PG_SERVICE_VALID;

    if ($SJS_ADDR === "")
    {
        echo "[-1,\"Atur dahulu konfigurasi JIBAS Sinkronisasi Jendela Sekolah\"]";
        return;
    }

    if ($PG_SCHOOL_ID == "" || $PG_ADDR == "" || $PG_SERVICE_VALID == 0)
    {
        echo "[-1,\"Atur dahulu konfigurasi Kode Sekolah\"]";
        return;
    }

    $db = new Db();
    try
    {
        $db->Open();

        /*
        $sql = "SELECT COUNT(*) FROM jbsfina.bank2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Daftar Rekening Bank\"]";
            return;
        }
        */

        $sql = "SELECT COUNT(*) FROM jbsfina.formatnomortagihan2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Format Nomor Tagihan\"]";
            return;
        }

        $sql = "SELECT COUNT(*) FROM jbsfina.formatpesanpg2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
        {
            echo "[-1,\"Atur dahulu konfigurasi Format Pesan Tagihan\"]";
            return;
        }

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "ktpeq");

        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
    finally
    {
        $db->Close();
    }

}

function CheckJsSyncAddrConfig()
{
    global $SJS_ADDR;

    if ($SJS_ADDR === "")
        echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Alamat JIBAS Sinkronisasi Jendela Sekolah belum diatur'>";
}

function CheckPgServiceConfig()
{
    global $PG_SCHOOL_ID, $PG_ADDR, $PG_SERVICE_VALID;

    if ($PG_SCHOOL_ID == "" || $PG_ADDR == "" || $PG_SERVICE_VALID == 0)
        echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
}

function CheckStatusPgConfig()
{
    global $PG_SCHOOL_ID, $PG_ADDR, $PG_SERVICE_VALID;

    if ($PG_SCHOOL_ID == "" || $PG_ADDR == "" || $PG_SERVICE_VALID == 0)
        echo "&nbsp;&nbsp;<img src='../images/warning.png'>";
}

function CheckPgProviderConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(*) FROM jbsfina.pgprovider2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Payment Gateway Provider belum diatur'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        $db->Close();
    }
}

function CheckBankConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $allBankSet = true;
        $sql = "SELECT d.departemen, COUNT(b.replid)
                  FROM jbsakad.departemen d
                  LEFT JOIN jbsfina.bank2 b ON d.departemen = b.departemen AND b.aktif = 1 
                 WHERE d.aktif = 1 
                 GROUP BY d.departemen";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $nBank = (int) $row[1];

            if ($nBank == 0)
                $allBankSet = false;
        }
        
        if (!$allBankSet)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Daftar Bank belum semuanya diatur'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "kp6ea");
    }
    finally
    {
        $db->Close();
    }
}

function CheckServiceFeeConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $lsDept = array();
        $sql = "SELECT departemen FROM jbsakad.departemen WHERE aktif = 1 ORDER BY urutan";
        $res = $db->QueryDb($sql);
        while($row = mysqli_fetch_row($res))
        {
            $lsDept[] = $row[0];
        }

        $allSet = true;
        for($i = 0; $i < count($lsDept) && $allSet; $i++)
        {
            $dept = $lsDept[$i];
            $nData = 0;

            $sql = "SELECT COUNT(id)
                      FROM jbsfina.pgservicefee2
                     WHERE departemen = '$dept'";
            $res = $db->QueryDb($sql);
            if ($row = mysqli_fetch_row($res))
                $nData = (int) $row[0];

            if ($nData == 0)
                $allSet = false;
        }

        if (!$allSet)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Biaya Layanan belum diatur untuk semua departemen'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1e");
    }
    finally
    {
        $db->Close();
    }
}

function CheckInfoBayarConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(*) FROM jbsfina.infobayar2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Informasi Tambahan belum diatur'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        $db->Close();
    }
}

function CheckFormatNomorTagihanConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(*) FROM jbsfina.formatnomortagihan2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Format Nomor Tagihan belum diatur'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        $db->Close();
    }
}


function CheckPesanPgConfig()
{
    $db = new Db();
    try
    {
        $db->Open();

        $sql = "SELECT COUNT(*) FROM jbsfina.formatpesanpg2";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        if ($row[0] == 0)
            echo "&nbsp;&nbsp;<img src='../images/warning.png' title='Format Pesan Notifikasi belum diatur'>";
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k2x1d");
    }
    finally
    {
        $db->Close();
    }
}

function CheckGetServiceFee()
{
    if (isset($_SESSION["SERVICE_FEE"]))
        return;

    global $PG_ADDR, $PG_SCHOOL_ID, $PG_DATABASE_ID;

    try
    {
        $schoolId = $PG_SCHOOL_ID;
        $dbId = $PG_DATABASE_ID;

        if (strlen($schoolId) != 5 || strlen($dbId) != 5)
            return;

        $pgServiceAddr = $PG_ADDR . "/jbsfina/svcf.php";

        $http = new HttpManager($pgServiceAddr);
        $http->setData("schoolid=$schoolId&dbid=$dbId");
        $sendGr = $http->send();
        if ($sendGr->Value < 0)
            return;

        $jsonInfo = $sendGr->Data;
        $info = json_decode($jsonInfo);
        $valid = $info[0];
        $message = $info[1];
        $serviceFee = $info[2];

        if ($valid == 1)
        {
            $content = "<?php\n";
            $content .= '$PG_SERVICE_VALID = ' . $valid . ';';
            $content .= "\n";
            //$content .= '$PG_SERVICE_FEE = ' . $serviceFee . ';';
            $content .= "\n?>";

            file_put_contents("./pgservice.config.php", $content);

            $_SESSION["SERVICE_FEE"] = $serviceFee;
        }
        else
        {
            unset($_SESSION["SERVICE_FEE"]);
        }
    }
    catch (Exception $ex)
    {
        Logger::LogErrorOnce($ex, "k27xy");
    }
}
?>
