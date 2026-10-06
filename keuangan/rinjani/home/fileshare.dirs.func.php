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

function InitUserDir($db)
{
    global $FILESHARE_UPLOAD_DIR;

    try
    {
        if (SI_USER_LEVEL() == 0)
            return;

        $userDir = $FILESHARE_UPLOAD_DIR . "/fileshare/" . SI_USER_ID();
        if (!file_exists($userDir) && !is_dir($userDir))
        {
            mkdir($userDir, 0750, true);
            
            $fhtaccess = "$userDir/.htaccess";
            $fhtaccess = str_replace("//", "/", $fhtaccess);
            if ($fp = fopen($fhtaccess, "w"))
            {
                fwrite($fp, "Options -Indexes\r\n");
                fclose($fp);
            }
        }

        $sql = "SELECT 1 
                  FROM jbsvcr.dirshare 
                 WHERE idroot = 0";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            $sql = "INSERT INTO jbsvcr.dirshare
                       SET ts = NOW(), token = '60722', issync = 0, idroot = 0, dirname = 'foot', dirfullpath = 'root/'";
            $db->QueryDb($sql);                        
        }

        $idRoot = 0;
        $dirFullPathRoot = "";
        $sql = "SELECT replid, dirfullpath 
                  FROM jbsvcr.dirshare 
                 WHERE idroot = 0";
        $res = $db->QueryDb($sql);
        if ($row = mysqli_fetch_row($res))
        {
            $idRoot = $row[0];
            $dirFullPathRoot = $row[1];
        }

        $userId = SI_USER_ID();

        $sql = "SELECT 1 
                  FROM jbsvcr.dirshare 
				 WHERE idroot = $idRoot 
                   AND idguru = '$userId' 
                   AND dirname = '$userId'";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            $dirname = $userId;
            $dirfullpath = $dirFullPathRoot . $dirname . "/";

            $sql = "INSERT INTO jbsvcr.dirshare 
                       SET idroot = '$idRoot', 
			  			   dirname = '$dirname', 
                           dirfullpath = '$dirfullpath', 
                           idguru = '$userId'";
            $db->QueryDb($sql);
        }
        
    }
    catch(Exception $ex)
    {
        echo $ex->getMessage();
    }
}             

function GetNSubDir($db, $idRoot)
{	
	$sql = "SELECT count(*) 
		      FROM jbsvcr.dirshare 
		     WHERE idroot = '$idRoot'";
	$res = $db->QueryDb($sql);
	$row = mysqli_fetch_row($res);
	return $row[0];
}

function Spacing($count)
{
	$str = "";
	for ($i = 0; $i < $count * 2; $i++) 
		$str = $str . " ";
		
	return $str;
}

function TraverseDir($db, $idDir, $count)
{	
	$sql = "SELECT d.replid, d.dirname, d.idguru, IFNULL(p.nama, '') 
	          FROM jbsvcr.dirshare d
              LEFT JOIN jbssdm.pegawai p ON p.nip = d.idguru 
	         WHERE d.idroot = '$idDir' 
	         ORDER BY d.dirname";
	$result = $db->QueryDb($sql);
	$space = Spacing($count);
	
	while ($row = mysqli_fetch_row($result))
	{
		$ajar = "";
		$msg = "";
		$idDir = $row[0];
		$dirName = $row[1];

        if ($count == 2)
		    $liName = $row[3] . " <span class='fs-11 fg-secondary fst-normal'>(" . $row[1] . ")</span>";
        else
            $liName = $row[1];

		$idGuru = $row[2];
		$namaGuru = $row[3];

		$nSubDir = GetNSubDir($db, $idDir);
		if ($nSubDir == 0)
		{
			echo "$space<li class='liBullet'>&nbsp;<a href='fileshare.files.php?iddir=$idDir' style='text-decoration:none;' target='files'>";
            echo "<span onMouseOver='showMenu($idDir)'>";
            echo "<img src='../images/ico/folder.gif' border='0'>&nbsp;$liName</a>&nbsp;";
			if (SI_USER_ID() == $idGuru)
			{
                echo "<span id='menu-$idDir' style='display:none' onMouseOver='showMenu($idDir)' onMouseOut='hideMenu($idDir)'>";
				echo "<img onclick='createfolder($idDir)' src='../images/ico/tambah.png'>&nbsp;";
                if ($count != 2)
                    echo "<img onclick='delfolder($idDir)' src='../images/ico/hapus.png'>";
                echo "</span>";
			}
			else if (strtoupper(SI_USER_ID()) == "LANDLORD")
			{
                if ($count != 2)
                {
                    echo "<span id='menu-$idDir' style='display:none' onMouseOver='showMenu($idDir)' onMouseOut='hideMenu($idDir)'>";
                    echo "<img onclick='delfolder($idDir)' src='../images/ico/hapus.png'>";
                    echo "</span>";
                }
			}
            echo "</span>";
		}
		else
		{
			echo "$space<li class='liClosed'>&nbsp;<a style='text-decoration:none;' href='fileshare.files.php?iddir=$idDir' target='files'>";
            echo "<span onMouseOver='showMenu($idDir)'>";
            echo "<img src='../images/ico/folder.gif' border='0'>&nbsp;$liName</a>&nbsp;";
			if (SI_USER_ID() == $idGuru)
			{
                echo "<span id='menu-$idDir' style='display:none' onMouseOver='showMenu($idDir)' onMouseOut='hideMenu($idDir)'>";
				echo "<img onclick='createfolder($idDir)' src='../images/ico/tambah.png'>&nbsp;";
				if ($count != 2)
                    echo "<img onclick='delfolder($idDir)' src='../images/ico/hapus.png'>";
                echo "</span>";
			}
			else if (strtoupper(SI_USER_ID()) == "LANDLORD")
			{
                if ($count != 2)
                {
                    echo "<span id='menu-$idDir' style='display:none' onMouseOver='showMenu($idDir)' onMouseOut='hideMenu($idDir)'>";
                    echo "<img onclick='delfolder($idDir)' src='../images/ico/hapus.png'>";
                    echo "</span>";
                }
			}
            echo "</span>";

			echo "$space<ul>";

			TraverseDir($db, $idDir, $count + 1);
			
			echo "$space</ul></li>";
		} //if 
	} //while
} //function

function ShowFileShareDirs($db)
{
    $sql = "SELECT d.replid, d.dirname, d.idguru 
              FROM jbsvcr.dirshare d 
             WHERE d.idroot = 0";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $idRoot = $row[0];
        $dirname = $row[1];
        $idGuru = $row[2];

        $nSubDir = GetNSubDir($db, $idRoot);
        echo "<ul class='mktree' id='tree1'>";
        if ($nSubDir == 0)
        {
            echo "&nbsp;<li class='liBullet'>&nbsp;<a style='text-decoration:none;' href='files.php?iddir=$idRoot' target='files'><img src='../images/ico/folder.gif' border='0'>&nbsp;(root)</a>&nbsp;";
            echo "</li>";
        }
        else
        {
            echo "&nbsp;<li class='liClosed'>&nbsp;<a style='text-decoration:none;' href='files.php?iddir=$idRoot' target='files'><img src='../images/ico/folder.gif' border='0'>&nbsp;(root)</a>&nbsp;";
            echo "<ul>";
            
            TraverseDir($db, $idRoot, 2);
            
            echo "</ul>";
            echo "</li>";
        }
        echo "</ul>";
 
    }             
}

function recursiveDataFolderDelete($db, $idDir)
{
	$sql ="SELECT replid FROM jbsvcr.dirshare WHERE idroot = '$idDir'";
	$res = $db->QueryDb($sql);
	while ($row = mysqli_fetch_row($res))
	{
		$sql = "DELETE FROM jbsvcr.dirshare WHERE replid = '$row[0]'";
		$db->QueryDb($sql);
		
		$sql = "DELETE FROM jbsvcr.fileshare WHERE iddir = '$row[0]'";
		$db->QueryDb($sql);
		
		recursiveDataFolderDelete($db, $row[0]);
	}
	
	$sql = "DELETE FROM jbsvcr.dirshare WHERE replid = '$idDir'";
	$db->QueryDb($sql);
	
	$sql = "DELETE FROM jbsvcr.fileshare WHERE iddir = '$iddir'";
	$db->QueryDb($sql);
}

function DeleteFolder()
{
    global $FILESHARE_UPLOAD_DIR;

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
	
	    $sql = "SELECT dirfullpath 
                  FROM jbsvcr.dirshare 
                 WHERE replid = '$idDir'";
	    $res = $db->QueryDb($sql);
	    $row = mysqli_fetch_row($res);
	    $dirFullPath = $row[0];
	
	    $fileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
	    $dir = str_replace($rootName, $fileShareDir, $dirFullPath);

        deleteFolderRecursive($dir);
	    recursiveDataFolderDelete($db, $idDir);

        return json_encode([1, "OK"]);
    }   
    catch(Exception $ex)
    {
        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}
?>