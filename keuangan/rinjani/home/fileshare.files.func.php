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
function GetFullPath($db, $iddir)
{
    $sql = "SELECT dirfullpath 
              FROM jbsvcr.dirshare 
             WHERE idroot = 0";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $rootname = $row[0];

    $sql = "SELECT dirfullpath, idguru 
              FROM jbsvcr.dirshare 
             WHERE replid = '$iddir'";
    $result = $db->QueryDb($sql);
    $row = mysqli_fetch_row($result);
    $idguru = $row[1];
    $dirfullpath = $row[0];
    $fullpath = str_replace($rootname, "", $dirfullpath);    

    return array($idguru, $fullpath);
}

function GetUserTotalSize($db, $idguru)
{
    $sql = "SELECT IFNULL(SUM(f.filesize), 0)
              FROM jbsvcr.fileshare f, jbsvcr.dirshare d
             WHERE f.iddir = d.replid
               AND d.idguru = '$idguru'";
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    return $row[0];
}

function ShowFiles($db, $iddir, $idguru, $fullpath)
{
    global $FILESHARE_ADDR, $FILESHARE_UPLOAD_DIR;

    $sql = "SELECT replid, filename, filesize, date_format(filetime, '%d-%b-%Y %h:%i:%s') as filetime
              FROM jbsvcr.fileshare
             WHERE iddir='$iddir' ORDER BY filename";
    $res = $db->QueryDb($sql);
    $numFile = @mysqli_num_rows($res);
    if ($numFile == 0)
    {
        echo "<br><i>Belum ada file di folder ini</i>";
        return;
    }

    echo "<table id='table' class='tab tabShadow' cellpadding='2' cellspacing='0' width='100%' id='table'>";
    echo "<tr height='30'>";
    echo "<td width='7%' align='center' class='header'>No</td>";
    if (SI_USER_ID() == $idguru) 
    {
        echo "<td width='7%' align='center' class='header'>";
        echo "<input type='checkbox' name='cek' id='cek' onClick='cekAll()' title='pilih semua'>";
        echo "</td>";
    }
    echo "<td width='*' align='center' class='header'>Name</td>";
    echo "<td width='12%' align='center' class='header'>Size</td>";
    echo "<td width='22%' align='center' class='header'>Date</td>";
    echo "</tr>";

    $cnt = 0;
    while ($row = mysqli_fetch_array($res))
    {
        $cnt += 1;
        $file_addr = "$FILESHARE_UPLOAD_DIR/fileshare/$fullpath/$row[filename]";

        echo "<tr height='25'>";
        echo "<td align='center' class='bg-table-number-column'>$cnt</td>";
        if (SI_USER_ID() == $idguru)
        {
            echo "<td align='center'>";
            echo "<input type='checkbox' name='cekfile$cnt' id='cekfile$cnt'>";
            echo "<input type='hidden' name='idfile$cnt' id='idfile$cnt' value='$row[replid]'>";
            echo "</td>";    
        }
        echo "<td align='left'>";
        echo "<a title='$FILESHARE_ADDR/fileshare/$fullpath/$row[filename]' ";
        echo "href='$FILESHARE_ADDR/fileshare/$fullpath/$row[filename]' target='_blank'>";
        echo $row['filename'];
        echo "</a>";
        echo "</td>";
        echo "<td align='right'>";
        $filesize = $row['filesize'];
        echo FileSizeInByte($filesize);
        echo "</td>";
        echo "<td align='center'>";
        echo $row['filetime'];
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";

    echo "<br>";
    echo "<input type='hidden' name='numfile' id='numfile' value='$cnt'>";
    echo "<span style='margin-left:20px' class='cur-hand fg-maroon' onclick='delSelected()'><img src='../images/ico/hapus.png'>&nbsp;hapus file terpilih</span>";
    //echo "<span style='margin-left:20px' class='cur-hand fg-maroon' onclick='delAll()'><img src='../images/ico/hapus.png'>&nbsp;hapus semua file</span>";
}

function DelSelectedFiles()
{
    global $FILESHARE_UPLOAD_DIR;

    $db = new Db();
    try
    {   
        $db->Open();

        $db->BeginTrans();

        $sql = "SELECT dirfullpath 
                  FROM jbsvcr.dirshare 
                 WHERE idroot = 0";
        $res = $db->QueryDb($sql);
        $row = mysqli_fetch_row($res);
        $rootname = $row[0];
        $fileShareDir = "$FILESHARE_UPLOAD_DIR/fileshare/";
        
        $jsonIdFiles = RequestData("jsonidfiles", "");
        $idFiles = json_decode($jsonIdFiles);

        for($i = 0; $i < count($idFiles); $i++)
        {
            $idFile = $idFiles[$i];

            $sql = "SELECT d.dirfullpath, f.filename
					  FROM jbsvcr.dirshare d, jbsvcr.fileshare f
					 WHERE f.replid = '$idFile'
					   AND f.iddir = d.replid";
            $res = $db->QueryDb($sql);
            $row = @mysqli_fetch_row($res);
            $dir_real = $row[0];

		    $dir_real = str_replace($rootname, $fileShareDir, $dir_real);
		    $file_path = "$dir_real/$row[1]";
		    if (file_exists($file_path))
			    DeleteFile($file_path);
		   
		    $sql = "DELETE FROM jbsvcr.fileshare 
                     WHERE replid = '$idFile'";
		    $db->QueryDb($sql);
        }

        $db->CommitTrans();

        return json_encode([1, "OK"]);
    }
    catch(Exception $ex)
    {
        $db->RollbackTrans();

        return json_encode([-1, $ex->getMessage()]);
    }
    finally
    {
        $db->Close();
    }
}