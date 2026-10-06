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

<span class='fs-14 fst-bold'>Laporan Pembayaran Keuangan</span>
<br><br>
Tahun Buku: 
<?php
    $idTahunBuku = 0;
    ShowSelectTahunBuku($db);
?>
<span style='margin-left: 30px;' class='cur-hand' onclick='cetakKeu()'>
    <img src='../images/ico/print.png'>&nbsp;cetak
</span>
&nbsp;&nbsp;
<span class='cur-hand' onclick='refreshKeu()'>
    <img src='../images/ico/refresh.png'>&nbsp;muat ulang
</span>
<br><br>
<div id='dvLaporanPembayaran'>
<?php
    ShowLaporanPembayaranCalonSiswa($db);
?>    
</div>

</div>