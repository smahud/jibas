<?php
function GetCountInput($db, $nip, $kategori)
{
    global $departemen, $rentang, $keyword;
    
    $whereKeyword = "";
    if (!empty($keyword))
        $whereKeyword = " AND MATCH(deskripsi) AGAINST ('$keyword' IN BOOLEAN MODE) "; 

    $sql = "SELECT COUNT(id)
              FROM jbsjs.riwayatinput
             WHERE departemen = '$departemen'
               AND kategori = '$kategori'
               $whereKeyword
               AND tanggal BETWEEN DATE_SUB(CURDATE(), INTERVAL $rentang DAY) AND CURDATE()";
    if ($nip == "jibas")               
        $sql .= " AND userid IS NULL";
    else
        $sql .= " AND userid = '$nip'";
        
    $res = $db->QueryDb($sql);
    $row = mysqli_fetch_row($res);
    
    return $row[0] == 0 ? "" : $row[0];
}
?>