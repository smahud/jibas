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
<div style="padding: 20px; font-size: 12px">

<span class='fs-14 fst-bold'>Nota Siswa</span>
<br><br>
<span>

<span style='display:inline-block; width: 80px;'>
Bagian: 
</span>

<span id='spBagianNota'>
<?php
    $bagianNota = "Keuangan";
    ShowSelectBagianNota();
?>
</span>

<?php
if (SI_USER_LEVEL() == $SI_USER_LANDLORD) 
{ 
    echo "<input type='button' class='dialogButtonGray' style='width: 30px;' title='Kelola Bagian Nota' value=' ... ' onclick='kelolaBagianNota()'>";
}    
?>

<span style='margin-left: 30px;' class='cur-hand' onclick='tambahNotaSiswa()'>
    <img src='../images/ico/tambah.png'>&nbsp;nota baru
</span>&nbsp;&nbsp;
<span class='cur-hand' onclick='refreshNota()'>
    <img src='../images/ico/refresh.png'>&nbsp;muat ulang
</span>
</span>

<br><br>
<input type="hidden" id="jsonidnota" value="">
<input type="hidden" id="nnota" value="0">
<div id='dvDaftarNota'>
    
</div><br>

<div id='dvPageControl'>

</div>

</div>