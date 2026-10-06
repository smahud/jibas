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
function CreateFolder()
{
    global $FILESHARE_UPLOAD_DIR;

    $db = new Db();
    try
    {
        $db->Open();

        $idDir = RequestData("iddir", 0);
        $folder = RequestData("folder", "");

        $sql = "SELECT dirfullpath 
                  FROM jbsvcr.dirshare 
                 WHERE idroot = 0";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $rootName = $row[0];

        $sql = "SELECT dirfullpath 
                  FROM jbsvcr.dirshare 
                 WHERE replid = '$idDir'";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $dirFullPath = $row[0];

        $fileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
        $dir_db = $dirFullPath . $folder . "/";
        $dir_real = str_replace($rootName, $fileShareDir, $dir_db);

        if (!file_exists($dir_real))
		{
			mkdir($dir_real, 0750, true);
			
			$fhtaccess = "$dir_real/.htaccess";
			$fhtaccess = str_replace("//", "/", $fhtaccess);
			if ($fp = @fopen($fhtaccess, "w"))
			{
				@fwrite($fp, "Options -Indexes\r\n");
				@fclose($fp);
			}

            $sql = "INSERT INTO jbsvcr.dirshare 
                       SET idroot = $idDir, dirname = '$folder', dirfullpath = '$dir_db', idguru = '". SI_USER_ID() . "'";
            $res = $db->QueryDb($sql);

            return json_encode([1, "OK"]);
		}
        else 
        {
            return json_encode([-1, "Folder $folder sudah ada"]);
        }
    }
    catch(Exception $ex)
    {
        return json_encode([1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>