<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 33.0 (Jan 05, 2026)
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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');

function SecurePhpExtension($filename)
{
    $lastpos = -1; $startpos = 0;
    $pos = strpos($filename, ".", $startpos);
    while($pos !== FALSE)
    {
        $lastpos = $pos;
        
        $startpos = $pos + 1;
        $pos = strpos($filename, ".", $startpos);
    }
    
    if ($lastpos != -1)
    {
        $ext = strtolower(trim(substr($filename, $lastpos)));
        if ($ext == ".php")
            $filename = $filename . ".txt";
    }

    return $filename;
}

$db = new Db();
try
{
    $db->Open();

    $idDir = RequestData("iddir", 0);
    
    $sql = "SELECT dirfullpath 
              FROM jbsvcr.dirshare 
             WHERE idroot = 0";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    $rootname = $row[0];

    $sql = "SELECT dirfullpath 
              FROM jbsvcr.dirshare 
             WHERE replid = '$idDir'";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $dirFullPath = $row[0];

    $fileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
    $destDir = str_replace($rootname, $fileShareDir, $dirFullPath);
    $fullpath = str_replace($rootname, "", $dirFullPath);

    $db->BeginTrans();

    foreach($_FILES as $key => $file)
    {
        $tmp_name = $file['tmp_name'];
        if (strlen($tmp_name) == 0)
            continue;

        $fileName = $file['name'];
        $fileSize = $file['size'];
        
        $fileName = SecurePhpExtension($fileName);

        $newFile = true;
        $targetFile = $destDir . $fileName;
        if (file_exists($targetFile))
        {
            unlink($targetFile);
            $newFile = false;
        }

        move_uploaded_file($tmp_name, $targetFile);

        if ($newFile)
        {
           $sql = "INSERT INTO jbsvcr.fileshare
                      SET iddir = '$idDir',
                          filename = '$fileName',
                          filetime = NOW(),
                          filesize = '$fileSize'";
        }
        else
        {
           $sql = "UPDATE jbsvcr.fileshare
                      SET filetime = NOW(),
                          filesize = '$fileSize'
                    WHERE iddir = '$idDir' 
                      AND filename = '$fileName'";
        }
        $db->QueryDb($sql);
    }
    
    $db->CommitTrans();

    echo json_encode([1, "OK"]);
}
catch(Exception $ex)
{
    $db->RollbackTrans();

    echo json_encode([-1, $ex->getMessage()]);
}
finally
{
    $db->Close();
}
?>
