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
function DeleteFile($file)
{
    if (file_exists($file))
    {
        // chmod($file, 0644);
        if (is_dir($file))
        {
            $handle = opendir($file); 
            while($filename = readdir($handle))
            {
                if ($filename != "." && $filename != "..")
                    delete($file."/".$filename);
            }
            closedir($handle);
            rmdir($file);
        }
        else
        {
            unlink($file);
        }
    }
}

function DeleteFolderRecursive($dir)
{
	$current_dir = opendir($dir);
  	while($entryname = readdir($current_dir))
  	{
		if ($entryname == "." || $entryname == "..")
			continue;
		
     	if(is_dir("$dir/$entryname"))
        	deleteFolderRecursive("$dir/$entryname");
	 	else
        	unlink("$dir/$entryname");
  	}
  	closedir($current_dir);
  	rmdir($dir);
}

function FileSizeInByte($filesize)
{
    if ($filesize < 1024)
    {
        return $filesize . " B";
    }

    if ($filesize < 1024 * 100 * 9)
    {
        $filesize = round($filesize / 1024, 2);
        return "$filesize KB";
    }
    
    if ($filesize < 1024 * 1024 * 100 * 9)
    {
        $filesize = round($filesize / (1024 * 1024), 2);
        return "$filesize MB";
    }
    
    $filesize = round($filesize / (1024 * 1024 * 1024), 2);
    return "$filesize GB";
}

?>
