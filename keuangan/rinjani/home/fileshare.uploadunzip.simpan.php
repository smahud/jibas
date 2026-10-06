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

function GetFolderAndFileName($entry, &$folderpath, &$filename)
{
    $lastpos = -1;
    $pos = strpos($entry, "/");
    while($pos !== FALSE)
    {
       $lastpos = $pos;
       $pos = strpos($entry, "/", $pos + 1);
    }
    
    if ($lastpos == -1)
    {
       $folderpath = "";
       $filename = $entry;	
    }
    else
    {
       $folderpath = trim(substr($entry, 0, $lastpos));
       $filename = trim(substr($entry, $lastpos + 1));	
    }
}

function SearchCreateIdFolder($db, $rootfolder, $lastfoldername)
{
    global $idguru;
    
    $currfolder = $rootfolder . $lastfoldername . "/";
    
    $sql = "SELECT replid
              FROM jbsvcr.dirshare
             WHERE idguru = '$idguru'
               AND dirfullpath = '$currfolder'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
    {
        $sql = "SELECT replid
                  FROM jbsvcr.dirshare
                 WHERE idguru = '$idguru'
                   AND dirfullpath = '$rootfolder'";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) > 0)
        {
            $row = mysqli_fetch_row($res);
            $rootid = $row[0];
            
            $sql = "INSERT INTO jbsvcr.dirshare
                       SET idroot = '$rootid',
                           dirname = '$lastfoldername',
                           dirfullpath = '$currfolder',
                           idguru = '$idguru'";
            $db->QueryDb($sql);
            
            $sql = "SELECT LAST_INSERT_ID()";
            $res = $db->QueryDb($sql);
            $row = mysqli_fetch_row($res);
            return $row[0];
        }
        return -1;
    }               
    else
    {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }
}

function GetIdFolder($db, $folderpath)
{
   global $idDir, $dirFullPath, $fileShareDir, $rootName;
    
	if ($folderpath == "")
		return $idDir;
    
	//echo "<br>=====================<br>FF: $folderpath<br>";
	$startpos = 0;
	$pos = strpos($folderpath, "/", $startpos);
	if ($pos !== FALSE)
	{
		$rootfolder = $dirFullPath;
		while($pos !== FALSE)
		{
			$subfolder = substr($folderpath, $startpos, $pos - $startpos);
			$lastid = SearchCreateIdFolder($db, $rootfolder, $subfolder);
			//echo "lastid = $lastid<br>";
			
			$rootfolder = $rootfolder . $subfolder . "/";
			
			$checkfolder = str_replace($rootName, $fileShareDir, $rootfolder);
			CheckCreateFolder($checkfolder);
			
			$startpos = $pos + 1;
			$pos = strpos($folderpath, "/", $startpos);
		}
		
		$subfolder = trim(substr($folderpath, $startpos));
		if (strlen($subfolder) > 0)
		{
			$lastid = SearchCreateIdFolder($db, $rootfolder, $subfolder);
		}
		
		return $lastid;
	}
	else
	{
		return SearchCreateIdFolder($db, $dirFullPath, $folderpath);
	}
}

function ExtractSaveFile($db, $folderid, $targetfolder, $filename, $zip, $zip_entry)
{
	$newfile = true;
	$targetfile = "$targetfolder/$filename";
	if (file_exists($targetfile))
	{
		unlink($targetfile);
		$newfile = false;
	}
    
	$zipfilesize = 0;
	if ($fp = fopen($targetfile, "w"))
	{
		 if (zip_entry_open($zip, $zip_entry, "r"))
		 {
			 $zipfilesize = zip_entry_filesize($zip_entry);
			 $buf = zip_entry_read($zip_entry, $zipfilesize);
			 fwrite($fp, $buf);
			 zip_entry_close($zip_entry);
			 $writezip = true;
		 }
		 fclose($fp);
	}
		  
	if ($zipfilesize == 0)
		 return;
		  
	if ($newfile)
		 $sql = "INSERT INTO jbsvcr.fileshare
					SET iddir='$folderid',
		   			    filename='$filename',
						filetime=NOW(),
						filesize='$zipfilesize'";
	else
		 $sql = "UPDATE jbsvcr.fileshare
                    SET filetime=NOW(),
	 				    filesize='$zipfilesize'
			      WHERE iddir='$folderid' 
					AND filename='$filename'";
	$db->QueryDb($sql);
}

function CheckCreateFolder($targetfolder)
{
   $targetfolder = str_replace("//", "/", $targetfolder);
   if (!file_exists($targetfolder))
      mkdir($targetfolder, 0750, true);
	
	$htaccess = "$targetfolder/.htaccess";
	if (!file_exists($htaccess))
	{
		if ($fp = fopen($htaccess, "w"))
		{
			fwrite($fp, "Options -Indexes\r\n");
			fclose($fp);
		}
	}
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
    $rootName = $row[0];

    $sql = "SELECT dirfullpath, idguru 
              FROM jbsvcr.dirshare 
             WHERE replid = '$idDir'";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $dirFullPath = $row[0];
    $idGuru = $row[1];

    $fileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
    $destDir = str_replace($rootName, $fileShareDir, $dirFullPath);
    $fullpath = str_replace($rootName, "", $dirFullPath);

    $db->BeginTrans();

    $file = $_FILES["filezip"];
    $uploadedFile = $file['tmp_name'];
	$uploadedFileSize = (int)$file['size'];
	$uploadedFileName = trim($file['name']);
	
	$zip = zip_open($uploadedFile);
	if (is_resource($zip))
	{
        while ($zip_entry = zip_read($zip))
        {
            $entry = zip_entry_name($zip_entry);
            $entry = str_replace("\\", "/", $entry);
			
            $folderpath = ""; 
            $filename = "";
            GetFolderAndFileName($entry, $folderpath, $filename);
            if (strlen($filename) == 0)
                continue;
        
            $folderid = GetIdFolder($db, $folderpath);
            if ($folderid == -1)
                continue;

            $targetfolder = "$destDir/$folderpath";
            CheckCreateFolder($targetfolder);
            
            SecurePhpExtension($filename);
            ExtractSaveFile($db, $folderid, $targetfolder, $filename, $zip, $zip_entry);
        } 
        zip_close($zip);
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
