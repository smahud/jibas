<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 * 
 * @version: 36.0 (Oct 07, 2026)
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
$tag_mandatory = "&nbsp;<span style='color: red; font-weight: bold'>*</span>";
$bullet_grey = "<span style='color: #999;'>&bull;</span>&nbsp;";
$bullet_red = "<span style='color: #8d0000;'>&bull;</span>&nbsp;";
$bullet_blue = "<span style='color: #1a73e8;'>&bull;</span>&nbsp;";

function StringIsSelected($value, $comparer)
{
	if ($value == $comparer) 
		return "selected";
	else
		return "";
}

function IntIsSelected($value, $comparer)
{
	$a = (int)$value;
	$b = (int)$comparer;
	
	if ($a == $b) 
		return "selected";
	else
		return "";
}

function StringIsChecked($value, $comparer)
{
	if ($value == $comparer) 
		return "checked";
	else
		return "";
}

function IntIsChecked($value, $comparer)
{
	if ($value == $comparer) 
		return "checked";
	else
		return "";
}

function RandStr($length)
{
	$charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, rand(0, 61), 1);
	return $s;		
}

function RandInt($length)
{
	$charset = "0123456789";
	$s = "";
	while(strlen($s) < $length) 
		$s .= substr($charset, rand(0, 9), 1);
	return $s;		
}

function NamaBulan($bln)
{
	if ($bln == 1)
		return "Januari";
	elseif ($bln == 2)
		return "Februari";		
	elseif ($bln == 3)
		return "Maret";		
	elseif ($bln == 4)
		return "April";		
	elseif ($bln == 5)
		return "Mei";
	elseif ($bln == 6)
		return "Juni";		
	elseif ($bln == 7)
		return "Juli";
	elseif ($bln == 8)
		return "Agustus";		
	elseif ($bln == 9)
		return "September";
	elseif ($bln == 10)
		return "Oktober";		
	elseif ($bln == 11)
		return "November";
	elseif ($bln == 12)
		return "Desember";		
}

function NamaBulanPendek($bln)
{
	if ($bln == 1)
		return "Jan";
	elseif ($bln == 2)
		return "Feb";		
	elseif ($bln == 3)
		return "Mar";		
	elseif ($bln == 4)
		return "Apr";		
	elseif ($bln == 5)
		return "Mei";
	elseif ($bln == 6)
		return "Jun";		
	elseif ($bln == 7)
		return "Jul";
	elseif ($bln == 8)
		return "Agu";		
	elseif ($bln == 9)
		return "Sep";
	elseif ($bln == 10)
		return "Okt";		
	elseif ($bln == 11)
		return "Nov";
	elseif ($bln == 12)
		return "Des";		
}

function NamaHari($hari) 
{
	if ($hari == 1)
		return "Senin";
	elseif ($hari == 2)
		return "Selasa";		
	elseif ($hari == 3)
		return "Rabu";		
	elseif ($hari == 4)
		return "Kamis";		
	elseif ($hari == 5)
		return "Jum'at";
	elseif ($hari == 6)
		return "Sabtu";
	elseif ($hari == 7)
		return "Minggu";
}

function WeekdayNameFromMysql($hari) 
{
	if ($hari == 0)
		return "Senin";
	elseif ($hari == 1)
		return "Selasa";		
	elseif ($hari == 2)
		return "Rabu";		
	elseif ($hari == 3)
		return "Kamis";		
	elseif ($hari == 4)
		return "Jum'at";
	elseif ($hari == 5)
		return "Sabtu";
	elseif ($hari == 6)
		return "Minggu";
}

function WeekdayNameFromPhp($hari) 
{
	if ($hari == 0)
		return "Minggu";
	elseif ($hari == 1)
		return "Senin";		
	elseif ($hari == 2)
		return "Selasa";		
	elseif ($hari == 3)
		return "Rabu";		
	elseif ($hari == 4)
		return "Kamis";
	elseif ($hari == 5)
		return "Jum'at";
	elseif ($hari == 6)
		return "Sabtu";
}

function rpad($string, $padchar, $length)
{
	$result = trim($string);
	if (strlen($result) < $length) {
		$nzero = $length - strlen($result);
		$zero = "";
		for($i = 0; $i < $nzero; $i++)
			$zero .= "0";
		$result = $zero . $result;
	}
	return $result;
}

function MySqlDateFormat($date)
{
    $ls = explode("-", $date);
    $d = $ls[0];
    $m = $ls[1];
    $y = $ls[2];
    return "$y-$m-$d";
}

function RegularDateFormat($mysqldate)
{
    $ls = explode("-", $mysqldate);
    $d = $ls[2];
    $m = $ls[1];
    $y = $ls[0];
    return "$d-$m-$y";
}

function LongDateFormat($mysqldate)
{
	//list($y, $m, $d) = split('[/.-]', $mysqldate);
	//return "$d ". NamaBulan($m) ." $y";
    $ls = explode("-", $mysqldate);
    $d = $ls[2];
    $m = $ls[1];
    $y = $ls[0];
    return "$d ". NamaBulan($m) ." $y";
}

function JmlHari($bln,$th)
{
	if ($bln == 4 || $bln == 6|| $bln == 9 || $bln == 11) 
		$n = 30;
	else if ($bln == 2 && $th % 4 <> 0)
		$n = 28;
	else if ($bln == 2 && $th % 4 == 0)
		$n = 29;
	else 
		$n = 31;
	return $n;
}

function SafeInput($text)
{
    $text = trim($text);
    $text = str_replace("'", "`", $text);
    $text = str_replace("<", "&lt;", $text);
    return str_replace(">", "&gt;", $text);
}

function RequestData($name, $defaultValue)
{
    return isset($_REQUEST[$name]) ? SafeInput($_REQUEST[$name]) : $defaultValue;
}

function ArrayData($arrData, $keyName, $defaultValue)
{
    return array_key_exists($keyName, $arrData) ? $arrData[$keyName] : $defaultValue;
}

function stripHtmlTags(string $str, bool $decodeEntities = true, bool $normalizeWhitespace = true): string
{
    if (empty($str)) return '';

    // Remove all HTML tags
    $result = strip_tags($str);

    // Decode HTML entities (&amp; → &, &lt; → <, etc.)
    if ($decodeEntities) {
        $result = html_entity_decode($result, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    // Collapse multiple spaces/newlines into a single space
    if ($normalizeWhitespace) {
        $result = preg_replace('/\s+/', ' ', $result);
        $result = trim($result);
    }

    return $result;
}

function NoUserImage()
{
	return "data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAAeAAD/4QN/aHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzA2NyA3OS4xNTc3NDcsIDIwMTUvMDMvMzAtMjM6NDA6NDIgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6ZWYwM2Q4ZDUtZjFlOC03MDQxLWEwYzgtODJiNjdhY2U5MjVkIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjVEQTVCNTkyNzQ2QjExRjFBRkQyQjlCRTgwRDZBREMwIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjVEQTVCNTkxNzQ2QjExRjFBRkQyQjlCRTgwRDZBREMwIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE1IChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOmVmMDNkOGQ1LWYxZTgtNzA0MS1hMGM4LTgyYjY3YWNlOTI1ZCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDplZjAzZDhkNS1mMWU4LTcwNDEtYTBjOC04MmI2N2FjZTkyNWQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAQCwsLDAsQDAwQFw8NDxcbFBAQFBsfFxcXFxcfHhcaGhoaFx4eIyUnJSMeLy8zMy8vQEBAQEBAQEBAQEBAQEBAAREPDxETERUSEhUUERQRFBoUFhYUGiYaGhwaGiYwIx4eHh4jMCsuJycnLis1NTAwNTVAQD9AQEBAQEBAQEBAQED/wAARCAC+AIIDASIAAhEBAxEB/8QAbAABAQEBAQEBAAAAAAAAAAAAAAIBBQQDBgEBAAAAAAAAAAAAAAAAAAAAABAAAgIBAgQEBQQCAwAAAAAAABEBAgMhBDFBEgVRYXEigZHBMlKhsUIT0TNDUxQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AP2DDJYYFMMlhgUwyWGBTDJYYFMMlhgUwyWGBTDJYYFMMlhgUwSwBgJYYFAlhgUCWGBQJYYFAlhgUCWGBQJYYFAlhgUCWAMYZjDA1hmMMDWIcyo1mTGena49JyTxnSANx7aI1yaz4QfWMeOOFY+RYAnop+MfIm2DFbkp8YPoAPFlw2x68a+J82dCYi0TE6xPE596zS81nkAYZjDA1hmMMDWDGAJYZLDAphksMCmdHFXpx1jyg5jOqBoAAAAAePeQskW8Y/Y9h5N9wpPqB5mGSwwKYZLDApglgDGGSwwKYZLDApnWiXETHPU47OntcnXgr419s/AD7AAAAAB49/b7I9ZPYc3eZIvmmI4V9v8AkD5MMlhgUwyWGBTBLAEsMlhgUwyWGBTPvtM8Ysit9ltJ8vM8zAHcNObtd7/XEY8utOVucHQpel46qTFo8YAoA+Gfd4sMJ9V/xj6gbuc8Yccz/OdKx9TlM3LlvlvN7zr+kQQwKYZLDAphksMCmCWAJYZjDA1n0w4MuaVjh+M8oPrs9nOf330xx87HUrWtKxWsKscIgDyYu3Y665Z658I0g9VMWKn2UiPSCwB4dz2/qmb4NJ505fA8Nq5cNtYtSfkdwyYidJ1gDiTmy20m9pjwcn1w7PPllrpr+VjqxSkaxWInyiCgPlh2+PDTprDf3TPMnJs9vk416Z8a6H3AHMzdvyU92OeuPDmeSXEqdJO8efc7SmeHHtycrePqByGGL0tjvNLwrRxgxgawYwBLPttcE7jNFP4xrafI87Ot2vF04JyTxvP6QB7a1isRWsKI0iDQAAAAAAAAAAAAAADx9w20Zcf9lY99I+dTks/QnB3OP+nPfHyidPSdYAhglgCWd3t98dtrSKS5rCtHhJwGfXb7jJt8kXxz6xymPMD9GDz7Xd4tzR0lXj7qTxg9AAAAAAAAAAAAADJmIhzpEcwNOH3HJS+6tNJcRERM+cH233cup4tvOn8skc/KDmsCmCWAMYZLDAumS+O0XpM1tHCYOrte71lU3MKfzjh8YOOwwP1Nb1vWLUmLVnhMawUfmMWfNhnqxXms+R7sXestdMtIv5x7ZA7IPBTvG0t93VT1h/sfavcNnbhlr8dP3A9IPP8A+7Z/91PmRbueyr/yP0iZA9YOZk73ij/Vjm0+NtI+p4s3c91m06uis8q6frxA7G43u328e+zv+Eaycjddwzbn2/Zj/CPqeNzOshgUwyWGBTBLAEsMxhgawzGGBrDMYYGsMxhgawzGGBrDMYYGsMxhgawzGGBrBjAEsMwAawzABrDMAGsMwAawzABrDMAGsMwAawzABrBgA//Z";
}


function CountAge($birthdateString)
{
    // Create DateTime objects for the birthdate and the current date
    $birthdate = new DateTime($birthdateString);
    $currentDate = new DateTime();

    // If the birthdate is in the future, you might want to handle it
    if ($birthdate > $currentDate) {
        return "0 tahun 0 bulan";
    }

    // Calculate the difference
    $interval = $currentDate->diff($birthdate);

    // Extract years and months
    $years = $interval->y;
    $months = $interval->m;

    return "{$years} tahun {$months} bulan";
}
?>