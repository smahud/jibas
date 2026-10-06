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
function ShowInfoCalonSiswa($db)
{
    global $idCalon, $nic, $nama, $idKelompok, $kelompok, $idProses, $proses, $departemen;

    $sql = "SELECT cs.nopendaftaran, cs.nama, k.replid AS idkelompok, k.kelompok, p.replid AS idproses, p.proses, p.departemen,
               IF(cs.foto IS NULL, 0, 1) AS fotoexist, IF(cs.foto IS NULL, '', TO_BASE64(cs.foto)) as foto64,
               cs.panggilan
          FROM jbsakad.calonsiswa cs, jbsakad.kelompokcalonsiswa k, jbsakad.prosespenerimaansiswa p
         WHERE cs.idkelompok = k.replid
           AND cs.idproses = p.replid
           AND cs.replid = '$idCalon'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_array($res))
    {
        $nic = $row['nopendaftaran'];
        $nama = $row['nama'];
        $idKelompok = $row['idkelompok'];
        $kelompok = $row['kelompok'];
        $idProses = $row['idproses'];
        $proses = $row['proses'];
        $departemen = $row['departemen'];
        $userFoto = $row['fotoexist'] == 1 ? $row['foto64'] : UserInfo::$DefaultFoto;
    }

    echo "<table border='0' width='100%'>";
    echo "<tr>";
    echo "<td width='130'>";
    echo "<img style='width: 100px; height: 100px;' class='avatar-circle'";
    echo "src='data:image/jpg;base64," .  $userFoto . "'>";
    echo "</td>";
    echo "<td>";
    echo "<span class='fg-secondary fs-10'>Dashboard Calon Siswa</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 24px; color: #333; font-weight: bold\">";
    echo $nama;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 18px; color: #333;\">";
    echo $nic;
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 12px; color: #666;\">";
    echo $departemen . ' | ' . $kelompok . ' | ' . $proses;
    echo "</span>&nbsp;&nbsp;";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

    echo "<input type='hidden' id='idcalon' value='$idCalon'>";
    echo "<input type='hidden' id='nic' value='$nic'>";
    echo "<input type='hidden' id='nama' value='$nama'>";
    echo "<input type='hidden' id='departemen' value='$departemen'>";
    echo "<input type='hidden' id='idkelompok' value='$idKelompok'>";
    echo "<input type='hidden' id='kelompok' value='$kelompok'>";
    echo "<input type='hidden' id='idproses' value='$idProses'>";
    echo "<input type='hidden' id='proses' value='$proses'>";
}

function NoUserImage()
{
	return "data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAAeAAD/4QN/aHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjYtYzA2NyA3OS4xNTc3NDcsIDIwMTUvMDMvMzAtMjM6NDA6NDIgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6ZWYwM2Q4ZDUtZjFlOC03MDQxLWEwYzgtODJiNjdhY2U5MjVkIiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjVEQTVCNTkyNzQ2QjExRjFBRkQyQjlCRTgwRDZBREMwIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjVEQTVCNTkxNzQ2QjExRjFBRkQyQjlCRTgwRDZBREMwIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE1IChXaW5kb3dzKSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOmVmMDNkOGQ1LWYxZTgtNzA0MS1hMGM4LTgyYjY3YWNlOTI1ZCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDplZjAzZDhkNS1mMWU4LTcwNDEtYTBjOC04MmI2N2FjZTkyNWQiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAOQWRvYmUAZMAAAAAB/9sAhAAQCwsLDAsQDAwQFw8NDxcbFBAQFBsfFxcXFxcfHhcaGhoaFx4eIyUnJSMeLy8zMy8vQEBAQEBAQEBAQEBAQEBAAREPDxETERUSEhUUERQRFBoUFhYUGiYaGhwaGiYwIx4eHh4jMCsuJycnLis1NTAwNTVAQD9AQEBAQEBAQEBAQED/wAARCAC+AIIDASIAAhEBAxEB/8QAbAABAQEBAQEBAAAAAAAAAAAAAAIBBQQDBgEBAAAAAAAAAAAAAAAAAAAAABAAAgIBAgQEBQQCAwAAAAAAABEBAgMhBDFBEgVRYXEigZHBMlKhsUIT0TNDUxQRAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhEDEQA/AP2DDJYYFMMlhgUwyWGBTDJYYFMMlhgUwyWGBTDJYYFMMlhgUwSwBgJYYFAlhgUCWGBQJYYFAlhgUCWGBQJYYFAlhgUCWAMYZjDA1hmMMDWIcyo1mTGena49JyTxnSANx7aI1yaz4QfWMeOOFY+RYAnop+MfIm2DFbkp8YPoAPFlw2x68a+J82dCYi0TE6xPE596zS81nkAYZjDA1hmMMDWDGAJYZLDAphksMCmdHFXpx1jyg5jOqBoAAAAAePeQskW8Y/Y9h5N9wpPqB5mGSwwKYZLDApglgDGGSwwKYZLDApnWiXETHPU47OntcnXgr419s/AD7AAAAAB49/b7I9ZPYc3eZIvmmI4V9v8AkD5MMlhgUwyWGBTBLAEsMlhgUwyWGBTPvtM8Ysit9ltJ8vM8zAHcNObtd7/XEY8utOVucHQpel46qTFo8YAoA+Gfd4sMJ9V/xj6gbuc8Yccz/OdKx9TlM3LlvlvN7zr+kQQwKYZLDAphksMCmCWAJYZjDA1n0w4MuaVjh+M8oPrs9nOf330xx87HUrWtKxWsKscIgDyYu3Y665Z658I0g9VMWKn2UiPSCwB4dz2/qmb4NJ505fA8Nq5cNtYtSfkdwyYidJ1gDiTmy20m9pjwcn1w7PPllrpr+VjqxSkaxWInyiCgPlh2+PDTprDf3TPMnJs9vk416Z8a6H3AHMzdvyU92OeuPDmeSXEqdJO8efc7SmeHHtycrePqByGGL0tjvNLwrRxgxgawYwBLPttcE7jNFP4xrafI87Ot2vF04JyTxvP6QB7a1isRWsKI0iDQAAAAAAAAAAAAAADx9w20Zcf9lY99I+dTks/QnB3OP+nPfHyidPSdYAhglgCWd3t98dtrSKS5rCtHhJwGfXb7jJt8kXxz6xymPMD9GDz7Xd4tzR0lXj7qTxg9AAAAAAAAAAAAADJmIhzpEcwNOH3HJS+6tNJcRERM+cH233cup4tvOn8skc/KDmsCmCWAMYZLDAumS+O0XpM1tHCYOrte71lU3MKfzjh8YOOwwP1Nb1vWLUmLVnhMawUfmMWfNhnqxXms+R7sXestdMtIv5x7ZA7IPBTvG0t93VT1h/sfavcNnbhlr8dP3A9IPP8A+7Z/91PmRbueyr/yP0iZA9YOZk73ij/Vjm0+NtI+p4s3c91m06uis8q6frxA7G43u328e+zv+Eaycjddwzbn2/Zj/CPqeNzOshgUwyWGBTBLAEsMxhgawzGGBrDMYYGsMxhgawzGGBrDMYYGsMxhgawzGGBrBjAEsMwAawzABrDMAGsMwAawzABrDMAGsMwAawzABrBgA//Z";
}
?>