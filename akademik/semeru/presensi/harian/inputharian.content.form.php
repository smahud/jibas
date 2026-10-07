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
require_once('../../include/sessioninfo.php');
require_once('../../include/sessionchecker.php');
require_once('../../include/config.php');
require_once('../../include/db.onfunc.php');
require_once('../../library/msg.php');
require_once('../../library/common.func.php');
require_once('../../util/peek.php');
require_once('inputharian.content.func.php');

$replid = RequestData("replid", 0);
$idKelas = RequestData("idkelas", 0);
$idSemester = RequestData("idsemester", 0);
$idTahunAjaran = RequestData("idtahunajaran", 0);

$db = new Db();
$db->TryOpenExit();
if ($replid > 0)
{
    $sql = "SELECT WEEKDAY(tanggal1), DAY(tanggal1), MONTH(tanggal1), YEAR(tanggal1),
                   WEEKDAY(tanggal2), DAY(tanggal2), MONTH(tanggal2), YEAR(tanggal2), 
                   hariaktif 
              FROM jbsakad.presensiharian 
             WHERE replid = '$replid'";
    $res = $db->QueryDb($sql);
    if ($row = mysqli_fetch_row($res))
    {
        $hariAwal = $row[0];
        $tglAwal = $row[1];
        $blnAwal = $row[2];
        $thnAwal = $row[3];
        $hariAkhir = $row[4];
        $tglAkhir = $row[5];
        $blnAkhir = $row[6];
        $thnAkhir = $row[7];
        $hariAktif = $row[8];
    }

    $sqlSiswa = "SELECT s.nis, s.nama
                   FROM jbsakad.siswa s, jbsakad.phsiswa p
                  WHERE p.nis = s.nis 
                    AND p.idpresensi = '$replid' 
                  ORDER BY s.nama";
}
else 
{
    $tglAwal = date('d');
    $blnAwal = date('m');
    $thnAwal = date('Y');
    $hariAwal = date('w');

    $tglAkhir = date('d');
    $blnAkhir = date('m');
    $thnAkhir = date('Y');
    $hariAkhir = date('w');

    $hariAktif = 1;

    $sqlSiswa = "SELECT s.nis, s.nama
                   FROM jbsakad.siswa s
                  WHERE s.idkelas = '$idKelas' 
                    AND s.aktif = 1 
                    AND s.alumni = 0 
                  ORDER BY s.nama";
}

$sql = "SELECT MONTH(tglmulai), YEAR(tglmulai),
               MONTH(tglakhir), YEAR(tglakhir)
          FROM jbsakad.tahunajaran
          WHERE replid = $idTahunAjaran";
$res = $db->QueryDb($sql);
if ($row = mysqli_fetch_row($res))
{
    $blnTa1 = $row[0];
    $thnTa1 = $row[1];
    $blnTa2 = $row[2];
    $thnTa2 = $row[3];

    if ($blnTa2 <= $blnTa1)
    {
        $startBlnTa = 1;
        $endBlnTa = (12 - $blnTa1) + $blnTa2;
    }
    else
    {
        $startBlnTa = $blnTa1;
        $endBlnTa = $blnTa2;
    }
}
?>
<input type="hidden" name="replid" id="replid" value="<?= $replid ?>">
<table border="0" cellpadding="2" cellspacing="0">
<tr>
    <td style="width: 90px">
        Tanggal Mulai:
    </td>
    <td>
<?php
    echo "<select id='tahunawal' onchange='onChangeAwal()' class='inputbox' style='width: 80px'>";
    for ($i = $thnTa1; $i <= $thnTa2; $i++)
        echo "<option value='$i' " . StringIsSelected($thnAwal, $i) . ">$i</option>";
    echo "</select>";

    echo "<select id='bulanawal' onchange='onChangeAwal()' class='inputbox' style='width: 100px'>";
    for ($i = $startBlnTa; $i <= $endBlnTa; $i++)
    {
        $bln = $blnTa1 + $i - 1;
        if ($bln > 12)
            $bln = $bln - 12;
        $sel = $bln == $blnAwal ? "selected" : "";
        echo "<option value='$bln' $sel>" . NamaBulan($bln) . "</option>";
    }
    echo "</select>";

    echo "<span id='spTanggalAwal'>";
    echo "<select id='tanggalawal' class='inputbox' onchange='countHariAktif(); dayNameAwal()' style='width: 50px'>";
    $lastTglAwal = date('t', strtotime($thnAwal . '-' . $blnAwal . '-01'));
    for ($i = 1; $i <= $lastTglAwal; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAwal, $i) . ">$i</option>";
    echo "</select>";
    echo "</span>&nbsp;&nbsp;";

    echo "<span id='spHariAwal' class='fg-secondary'>";
    $dt = "$thnAwal-$blnAwal-$tglAwal";
    echo WeekdayNameFromPhp(date("w", strtotime($dt)));
    echo "</span>";

?>      
    </td>
</tr>  
<tr>
    <td>
        Tanggal Akhir:
    </td>
    <td>
<?php
    echo "<select id='tahunakhir' onchange='onChangeAkhir()' class='inputbox' style='width: 80px'>";
    for ($i = $thnTa1; $i <= $thnTa2; $i++)
        echo "<option value='$i' " . StringIsSelected($thnAkhir, $i) . ">$i</option>";
    echo "</select>";

    echo "<select id='bulanakhir' onchange='onChangeAkhir()' class='inputbox' style='width: 100px'>";
    for ($i = $startBlnTa; $i <= $endBlnTa; $i++)
    {
        $bln = $blnTa1 + $i - 1;
        if ($bln > 12)
            $bln = $bln - 12;
        $sel = $bln == $blnAkhir ? "selected" : "";
        echo "<option value='$bln' $sel>" . NamaBulan($bln) . "</option>";
    }
    echo "</select>";

    echo "<span id='spTanggalAkhir'>";
    echo "<select id='tanggalakhir' class='inputbox' onchange='countHariAktif(); dayNameAkhir();' style='width: 50px'>";
    $lastTglAkhir = date('t', strtotime($thnAkhir . '-' . $blnAkhir . '-01'));
    for ($i = 1; $i <= $lastTglAkhir; $i++)
        echo "<option value='$i' " . StringIsSelected($tglAkhir, $i) . ">$i</option>";
    echo "</select>";
    echo "</span>&nbsp;&nbsp;";

    echo "<span id='spHariAkhir' class='fg-secondary'>";
    $dt = "$thnAkhir-$blnAkhir-$tglAkhir";
    echo WeekdayNameFromPhp(date("w", strtotime($dt)));
    echo "</span>";
?>      
    </td>
</tr>  
<tr>
    <td>
        Hari Belajar:
    </td>
    <td>
<?php
    echo "<span id='spHariAktif'>";
    echo "<select id='hariaktif' class='inputbox' style='width: 50px'>";
    for ($i = $hariAktif; $i >= 1; $i--)
        echo "<option value='$i' " . StringIsSelected($hariAktif, $i) . ">$i</option>";
    echo "</select>";
    echo "</span>";
?>      
    <span class="fg-secondary fs-11 fst-italic">jumlah hari aktif belajar</span>
    </td>
</tr>  
</table>

<table width="100%" id="tableInput" class="tab tabShadow">
<tr>		
    <td width="5%" align="center" class="bg-table-header" rowspan="2">No</td>
    <td width="25%" align="center" class="bg-table-header" rowspan="2">Siswa</td>
    <td width="3%" align="center" class="bg-table-header" rowspan="2"><div class="rotate-90">Exclude</div></td>
    <td width="9%" align="center" class="bg-table-header">Hadir</td>
    <td width="9%" align="center" class="bg-table-header">Ijin</td>
    <td width="9%" align="center" class="bg-table-header">Sakit</td>
    <td width="9%" align="center" class="bg-table-header">Alpa</td>
    <td width="9%" align="center" class="bg-table-header">Cuti</td>
    <td width="15%" align="center" class="bg-table-header" rowspan="2">Keterangan</td>
</tr>
<tr>       	          
    <td align="center" class="bg-table-header"> 
       	<input type="text" name="defhadir" id="defhadir" class="inputbox" size="2" maxlength="3" value="0">
        <span class="cur-hand fs-20 fg-white" title="apply to all" onclick="applyAll('hadir');" >&triangledown;</span>
    </td>
    <td align="center" class="bg-table-header">
       	<input type="text" name="defijin" id="defijin" class="inputbox" size="2" maxlength="3" value="0">
        <span class="cur-hand fs-20 fg-white" title="apply to all" onclick="applyAll('ijin');" >&triangledown;</span>
    </td>
    <td align="center" class="bg-table-header">
        <input type="text" name="defsakit" id="defsakit" class="inputbox" size="2" maxlength="3" value="0">       
        <span class="cur-hand fs-20 fg-white" title="apply to all" onclick="applyAll('sakit');" >&triangledown;</span>
    </td>
    <td align="center" class="bg-table-header">
        <input type="text" name="defalpa" id="defalpa" class="inputbox" size="2" maxlength="3" value="0">
        <span class="cur-hand fs-20 fg-white" title="apply to all" onclick="applyAll('alpa');" >&triangledown;</span>
    </td>
    <td align="center" class="bg-table-header">
        <input type="text" name="defcuti" id="defcuti" class="inputbox" size="2" maxlength="3" value="0">
        <span class="cur-hand fs-20 fg-white" title="apply to all" onclick="applyAll('cuti');" >&triangledown;</span>
    </td>
</tr>
<?php
$res = $db->QueryDb($sqlSiswa);
$nSiswa = mysqli_num_rows($res);
echo "<input type='hidden' name='nsiswa' id='nsiswa' value='$nSiswa'>";

$cnt = 0;
while($row = mysqli_fetch_array($res))
{
    $cnt += 1;

    $nis = $row["nis"];
    $nama = $row["nama"];

    $idph = 0;
    $hadir = 0;
    $ijin = 0;
    $sakit = 0;
    $cuti = 0;
    $alpa = 0;
    $exclude = 0;
    $ket = "";
    if ($replid <> "") 
    {						
        $sql1 = "SELECT * 
                   FROM jbsakad.phsiswa 
                  WHERE idpresensi = '$replid' 
                    AND nis = '$nis'";
        $res1 = $db->QueryDb($sql1);
        if ($row1 = mysqli_fetch_array($res1)) 
        {
            $idph = $row1['replid'];
            $hadir = $row1['hadir'];
            $ijin = $row1['ijin'];
            $sakit = $row1['sakit'];
            $cuti = $row1['cuti'];
            $alpa = $row1['alpa'];
            $exclude = $row1['exclude'];
            $ket = $row1['keterangan'];
        } 
    } 

    echo "<tr>";
    echo "<td class='bg-table-number-column' align='center'>$cnt</td>";
    echo "<td style='position: relative;'>";
    echo "<b>$nama</b><br><span class='fg-secondary'>$nis</span>";
    echo "<span style='position:absolute; right: 10px; top: 8px;'>";
    echo "<img src='../../images/ico/lihat.png' title='profil' class='cur-hand hide-in-report' onclick='showInfoSiswa(\"$nis\")'>&nbsp;&nbsp;";
    echo "<img src='../../images/ico/stat01.png' title='dashboard' class='cur-hand hide-in-report' onclick='showDashboardSiswa(\"$nis\")'>";
    echo "</span>";
    echo "<input type='hidden' name='nis$cnt' id='nis$cnt' value='$nis'>";
    echo "<input type='hidden' name='idph$cnt' id='idph$cnt' value='$idph'>";
    echo "<input type='hidden' name='aktif$cnt' id='aktif$cnt' value='$aktif'>";
    echo "</td>";
    echo "<td align='center'>";
    $checked = $exclude == 1 ? "checked" : "";
    echo "<input type='checkbox' $checked class='inputbox' name='exclude$cnt' id='exclude$cnt' onchange='toggleExclude($cnt);' value='$exclude'>";
    echo "</td>";
    echo "<td align='center' id='tdhadir$cnt'>";
    $display = $exclude == 1 ? "none" : "block";
    echo "<input type='text' style='display: $display;' class='inputbox fs-14' name='hadir$cnt' id='hadir$cnt' size='2' maxlength='3' value='$hadir' onblur='applyRowColor($cnt);'>";
    echo "</td>";
    echo "<td align='center' id='tdijin$cnt'>";
    echo "<input type='text' style='display: $display;' class='inputbox fs-14' name='ijin$cnt' id='ijin$cnt' size='2' maxlength='3' value='$ijin' onblur='applyRowColor($cnt);'>";
    echo "</td>";
    echo "<td align='center' id='tdsakit$cnt'>";
    echo "<input type='text' style='display: $display;' class='inputbox fs-14' name='sakit$cnt' id='sakit$cnt' size='2' maxlength='3' value='$sakit' onblur='applyRowColor($cnt);'>";
    echo "</td>";
    echo "<td align='center' id='tdalpa$cnt'>";
    echo "<input type='text' style='display: $display;' class='inputbox fs-14' name='alpa$cnt' id='alpa$cnt' size='2' maxlength='3' value='$alpa' onblur='applyRowColor($cnt);'>";
    echo "</td>";
    echo "<td align='center' id='tdcuti$cnt'>";
    echo "<input type='text' style='display: $display;' class='inputbox fs-14' name='cuti$cnt' id='cuti$cnt' size='2' maxlength='3' value='$cuti' onblur='applyRowColor($cnt);'>";
    echo "</td>";
    echo "<td align='center'>";
    echo "<textarea style='display: $display;' class='inputbox fs-12' name='ket$cnt' id='ket$cnt' rows='1' cols='18' >$ket</textarea>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
echo "<br>";
echo "<input type='hidden' name='ndata' id='ndata' value='$cnt'>";

echo "<div style='width: 100%; position: relative'>";
echo "<span style='position: absolute; top: 0; right: 0;'>";
echo "<input type='button' id='btSimpan' class='dialogButtonPositive' style='width: 100px; margin-right: 10px;' value='Simpan' onclick='simpan()'>";
if ($replid != 0)
    echo "<input type='button' id='btHapus' class='dialogButtonNegative' style='width: 100px;' value='Hapus' onclick='hapus()'>";
echo "</span>";
echo "</div>";
?>
