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
function ProcessTestSjs()
{
    global $SJS_ADDR,$SJS_PORT;
    
    try
    {
        if ($SJS_ADDR == "" || $SJS_PORT == "")
        {
            echo "[-1,\"Pengaturan Sinkronisasi Jendela Sekolah belum dilakukan\"]";
            return;
        }

        $sjsIpAddr = "$SJS_ADDR:$SJS_PORT";
        
        $http = new HttpManager($sjsIpAddr);
        $http->setData("op=test");
        $sendGr = $http->send();
        
        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            echo "[-1,\"$msg\"]";
            return;
        }

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
}

function ProcessInitOp()
{
    global $SJS_PORT;

    try
    {
        $ipAddr  = $_REQUEST["ipaddr"];
        $testIpAddr = "$ipAddr:$SJS_PORT";

        $http = new HttpManager($testIpAddr);
        $http->setData("op=initop");
        $sendGr = $http->send();
        
        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            echo "[-1,\"$msg\"]";
            return;
        }

        $resultGr = GenericReturn::fromJson($sendGr->Data);
        if ($resultGr->Value < 0)
        {
            WritePgService(0, $resultGr->Text);

            $msg = $resultGr->Text;
            echo "[-1,\"$msg\"]";
            return;
        }

        $lsData = json_decode($resultGr->Data);
        if ($lsData == null)
        {
            WritePgService(0, "Registration Data tidak valid");

            echo "[-1,\"Registration Data tidak valid\"]";
            return;
        }

        $schoolId = $lsData[0];
        $databaseId = $lsData[1];
        $pgAddr = $lsData[2];

        WriteAppServerConfig($ipAddr, $SJS_PORT);

        WritePgSchoolId($schoolId, $databaseId);

        WritePgServer($pgAddr);

        WritePgService(1, "OK");

        echo "[1,\"OK\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\"]";
    }
}

function WritePgServer($pgAddr) 
{
    $content  = "<?php\n";
    $content .= '// CREATED ON ' . date("Y-m-d H:i:s");
    $content .= "\n";
    $content .= '$PGMAIN_ADDR = "https://paygate.jendelasekolah.id";';
    $content .= "\n";
    $content .= '$PG_ADDR = "https://' . $pgAddr . '";';
    $content .= "\n?>";

    file_put_contents("./pgserver.config.php", $content);
}

function WritePgService($valid, $message) 
{
    $content  = "<?php\n";
    $content .= '// CREATED ON ' . date("Y-m-d H:i:s");
    $content .= "\n";
    $content .= '$PG_SERVICE_VALID = ' . $valid . ';';
    $content .= "\n";
    $content .= '$PG_SERVICE_MESSAGE = "' . $message . '";';
    $content .= "\n?>";

    file_put_contents("./pgservice.config.php", $content);
}

function WritePgSchoolId($schoolId, $databaseId)
{
    $content  = "<?php\n";
    $content .= '// CREATED ON ' . date("Y-m-d H:i:s");
    $content .= "\n";
    $content .= '$PG_SCHOOL_ID = "' . $schoolId . '";';
    $content .= "\n";
    $content .= '$PG_DATABASE_ID = "' . $databaseId . '";';
    $content .= "\n?>";

    file_put_contents("./pgschoolid.config.php", $content);
}

function WriteAppServerConfig($ipAddr, $port)
{
    $content  = "<?php\n";
    $content .= '// CREATED ON ' . date("Y-m-d H:i:s");
    $content .= "\n";
    $content .= '$SJS_ADDR = "' . $ipAddr . '";';
    $content .= "\n";
    $content .= '$SJS_PORT = "' . $port . '";';
    $content .= "\n?>";

    file_put_contents("./appserver.config.php", $content);
}

function CreateRekAkun($db, $kategori, $namaRekAkun)
{
    $sql = "SELECT MAX(kode) FROM jbsfina.rekakun WHERE kategori = '$kategori'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);  
    $maxKode = $row[0];

    $found = false;
    while(!$found)
    {
        $maxKode += 1;

        $sql = "SELECT COUNT(kode) FROM jbsfina.rekakun WHERE kode = '$maxKode'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);  
        $found = $row[0] == 0;
    }

    $sql = "INSERT INTO jbsfina.rekakun 
               SET kode = '$maxKode',
                   nama = '$namaRekAkun',
                   kategori = '$kategori',
                   keterangan = 'Otomatis dibuat oleh sistem'";
    $db->QueryDb($sql);

    return $maxKode;
}
?>