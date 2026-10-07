<form id="inputform" enctype="multipart/form-data">
<input type="hidden" id="replid" name="replid" value="<?=$replid?>">
<input type="hidden" id="departemen" name="departemen" value="<?=$departemen?>">
<input type="hidden" id="idproses" name="idproses" value="<?=$idProses?>">
<input type="hidden" id="idkelompok" name="idkelompok" value="<?=$idKelompok?>">
<table border="0" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Data Pribadi Calon Siswa</font>
        <br><br>
    </td>
</tr>
<tr id='row_nopendaftaran'>
    <td align="right">
        No Pendaftaran <?= $tag_mandatory ?>
    </td>
    <td align="left">
        <input type="text" name="nopendaftaran" id="nopendaftaran" size="40" maxlength="20" class="inputbox inputbox-readonly" readonly value="<?=$row['nopendaftaran']?>">
    </td>
</tr>    
<tr>
    <td width="10%" align="right">
        Nama <?= $tag_mandatory ?>
    </td>
    <td align="left">
        <input type="text" name="nama" id="nama" size="70" maxlength="255" class="inputbox" value="<?=$row['nama']?>">
    </td>
</tr>
<tr>
    <td align="right">
        NISN
    </td>
    <td align="left">
        <input type="text" name="nisn" id="nisn" size="40" maxlength="100" class="inputbox" value="<?=$row['nisn']?>">
    </td>
</tr>
<tr>
    <td align="right">
        NIK
    </td>
    <td align="left">
        <input type="text" name="nik" id="nik" size="40" maxlength="100" class="inputbox" value="<?=$row['nik']?>">
    </td>
</tr>
<tr>
    <td align="right">
        No UN Sebelumnya
    </td>
    <td align="left">
        <input type="text" name="noun" id="noun" size="40" maxlength="100" class="inputbox" value="<?=$row['noun']?>">
    </td>
</tr>

<tr>
    <td align="right">
        Panggilan
    </td>
    <td align="left">
        <input type="text" name="panggilan" id="panggilan" size="40" maxlength="100" class="inputbox" value="<?=$row['panggilan']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Jenis Kelamin
    </td>
    <td align="left">
        <input type="radio" name="kelamin" id="kelamin" value="l" class="inputbox" <?= StringIsChecked($row['kelamin'], "l") ?> />&nbsp;Laki-laki&nbsp;&nbsp;
        <input type="radio" name="kelamin" id="kelamin" value="p" class="inputbox" <?= StringIsChecked($row['kelamin'], "p") ?> />&nbsp;Perempuan
    </td>
</tr>
<tr>
    <td align="right">
        Tempat Lahir
    </td>
    <td align="left">
        <input type="text" name="tmplahir" id="tmplahir" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahir']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Tanggal Lahir
    </td>
    <td align="left">
<?php       
        $temp = ArrayData($row, "tgllahir", "");
        if ($temp != "")
        {
            $ls = explode("-", $temp);
            $tglLahir = $ls[2];
            $blnLahir = $ls[1];
            $thnLahir = $ls[0];
        }
    ?>    
        <input type='text' class='inputbox' name='tgllahir' id='tgllahir' placeholder="tgl" maxlength="2" style="width: 30px" value="<?= $tglLahir ?>">
        <input type='text' class='inputbox' name='blnlahir' id='blnlahir' placeholder="bln" maxlength="2" style="width: 30px" value="<?= $blnLahir ?>">
        <input type='text' class='inputbox' name='thnlahir' id='thnlahir' placeholder="thn" maxlength="4" style="width: 50px" value="<?= $thnLahir ?>">
    </td>
</tr>
<tr>
    <td align="right">
        Agama
    </td>
    <td align="left">
        <span id='spAgama'>
<?php       $agama = ArrayData($row, "agama", "");
            ShowSelectAgama($db)  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshAgama()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showAgamaDialog()'>
    </td>
</tr>
<tr>
    <td align="right">
        Suku
    </td>
    <td align="left">
        <span id='spSuku'>
<?php       $suku = ArrayData($row, "suku", "");
            ShowSelectSuku($db)  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshSuku()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showSukuDialog()'>
    </td>
</tr>
<tr>
    <td align="right">
        Status Siswa
    </td>
    <td align="left">
        <span id='spStatusSiswa'>
<?php       $statusSiswa = ArrayData($row, "status", "");
            ShowSelectStatusSiswa($db)  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshStatusSiswa()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showStatusSiswaDialog()'>
    </td>
</tr>
<tr>
    <td align="right">
        Kondisi Siswa
    </td>
    <td align="left">
        <span id='spKondisiSiswa'>
<?php       $kondisiSiswa = ArrayData($row, "kondisi", "");
            ShowSelectKondisiSiswa($db)  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshKondisiSiswa()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showKondisiSiswaDialog()'>
    </td>
</tr>
<tr>
    <td align="right">
        Kewarganegaraan
    </td>
    <td align="left">
        <input type="radio" name="warga" id="warga" value="WNI" <?= StringIsChecked($row['warga'], "WNI") ?> />&nbsp;WNI&nbsp;&nbsp;
        <input type="radio" name="warga" id="warga" value="WNA" <?= StringIsChecked($row['warga'], "WNA") ?>/>&nbsp;WNA
    </td>
</tr>
<tr>
    <td align="right">
        Anak ke
    </td>
    <td align="left">
        <input type="text" name="urutananak" id="urutananak" size="3" maxlength="3" class="inputbox" value="<?=$row['anakke']?>">&nbsp;dari&nbsp;
        <input type="text" name="jumlahanak" id="jumlahanak" size="3" maxlength="3" class="inputbox" value="<?=$row['jsaudara']?>">&nbsp;bersaudara
    </td>
</tr>
<tr>
    <td align="right">
        Status Anak
    </td>
    <td align="left">
<?php   $statusAnak = ArrayData($row, "statusanak", "");
        ShowSelectStatusAnak()  ?>
    </td>
</tr>
<tr>
    <td align="right">
        Jumlah Saudara Kandung
    </td>
    <td align="left">
        <input type="text" name="jkandung" id="jkandung" size="3" maxlength="3" class="inputbox" value="<?=$row['jkandung']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td align="right">
        Jumlah Saudara Tiri
    </td>
    <td align="left">
        <input type="text" name="jtiri" id="jtiri" size="3" maxlength="3" class="inputbox" value="<?=$row['jtiri']?>">&nbsp;orang
    </td>
</tr>
<tr>
    <td align="right">
        Bahasa
    </td>
    <td align="left">
        <input type="text" name="bahasa" id="bahasa" size="40" maxlength="100" class="inputbox" value="<?=$row['bahasa']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Alamat
    </td>
    <td align="left">
        <textarea name="alamatsiswa" id="alamatsiswa" rows="2" cols="40" class="inputbox" ><?= $row['alamatsiswa'] ?></textarea>
    </td>
</tr>
<tr>
    <td align="right">
        Kode Pos
    </td>
    <td align="left">
        <input type="text" name="kodepos" id="kodepos" size="5" maxlength="8" class="inputbox" value="<?=$row['kodepossiswa']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Jarak ke Sekolah
    </td>
    <td align="left">
        <input type="text" name="jarak" id="jarak" size="4" maxlength="4" class="inputbox" value="<?=$row['jarak']?>">&nbsp;km
    </td>
</tr>
<tr>
    <td align="right">
        Telpon
    </td>
    <td align="left">
        <input type="text" name="telponsiswa" id="telponsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['telponsiswa']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Handphone
    </td>
    <td align="left">
        <input type="text" name="hpsiswa" id="hpsiswa" size="20" maxlength="20" class="inputbox" value="<?=$row['hpsiswa']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Email
    </td>
    <td align="left">
        <input type="text" name="emailsiswa" id="emailsiswa" size="40" maxlength="100" class="inputbox" value="<?=$row['emailsiswa']?>">
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Data Sekolah Siswa</font>
        <br><br>
    </td>
</tr>    
<tr>
    <td align="right" valign='top'>
        Asal Sekolah
    </td>
    <td align="left">
        <span id='spJenjangSekolah'>
<?php       $sql = "SELECT departemen
                      FROM jbsakad.asalsekolah
                     WHERE sekolah = '$row[asalsekolah]'";
            $res2 = $db->QueryDb($sql);
            $jenjangSekolah = "0";
            if ($row2 = mysqli_fetch_assoc($res2))
                $jenjangSekolah = $row2['departemen'];
            ShowSelectJenjangSekolah($db) ?>        
        </span>
        <span id='spAsalSekolah'>
<?php       $asalSekolah = ArrayData($row, "asalsekolah", "0");
            ShowSelectAsalSekolah($db) ?>        
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshAsalSekolah()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showAsalSekolahDialog()'>
    </td>
</tr>
<tr>
    <td align="right">
        No Ijasah
    </td>
    <td align="left">
        <input type="text" name="noijasah" id="noijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['noijasah']?>">
    </td>
</tr>
<tr>
    <td align="right">
        Tgl Ijasah
    </td>
    <td align="left">
        <input type="text" name="tglijasah" id="tglijasah" size="40" maxlength="100" class="inputbox" value="<?=$row['tglijasah']?>">
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Keterangan
    </td>
    <td align="left">
        <textarea name="ketsekolah" id="ketsekolah" rows="2" cols="40" class="inputbox"><?=$row['ketsekolah']?></textarea>
    </td>
</tr>
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Riwayat Kesehatan Siswa</font>
        <br><br>
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Golongan Darah
    </td>
    <td width="*" align="left">
        <input type="radio" name="gol" id="gol" value="A" <?= StringIsChecked($row['darah'], "A") ?>/>&nbsp;A&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="AB" <?= StringIsChecked($row['darah'], "AB") ?>/>&nbsp;AB&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="B" <?= StringIsChecked($row['darah'], "B") ?>/>&nbsp;B&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value="O" <?= StringIsChecked($row['darah'], "O") ?>/>&nbsp;O&nbsp;&nbsp;
        <input type="radio" name="gol" id="gol" value=""  <?= StringIsChecked($row['darah'], "") ?>/>&nbsp;<em>(belum ada data)</em>
    </td>
</tr>
<tr>
    <td align="right" valign="top">Berat</td>
    <td colspan="2">
        <input type="text" name="berat" id="berat" size="4" maxlength="4" class="inputbox" value="<?=$row['berat']?>">&nbsp;kg        	
    </td>
</tr>
<tr>
    <td align="right" valign="top">Tinggi</td>
    <td colspan="2">
        <input type="text" name="tinggi" id="tinggi" size="4" maxlength="4" class="inputbox" value="<?=$row['tinggi']?>">&nbsp;cm      	
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Riwayat Penyakit
    </td>
    <td align="left">
        <textarea name="kesehatan" id="kesehatan" rows="3" cols="40" class="inputbox"><?= $row['kesehatan'] ?></textarea>
    </td>
</tr>
</table>
<table border="0" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="4" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Data Orangtua Siswa</font>
        <br><br>
    </td>
</tr>
<tr height='25'>
    <td width="10%" align="right" valign="top">
        &nbsp;
    </td>
    <td width="20%" align="center" valign="middle" bgcolor="#DBD8F3">
        <strong>Ayah</strong>
    </td>
    <td width="20%" align="center" valign="middle" bgcolor="#E9AFCF">
        <strong>Ibu</strong>
    </td>
    <td width="*" align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Nama
    </td>
    <td align="left" bgcolor="#DBD8F3">
        <input type="text" name="namaayah" id="namaayah" size="40" maxlength="100" class="inputbox" value="<?=$row['namaayah']?>"><br>
        <input type="checkbox" name="almayah" id="almayah" value="1" title="Klik disini jika Ayah Almarhum" <?= IntIsChecked($row['almayah'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhum)</font>
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <input type="text" name="namaibu" id="namaibu" size="40" maxlength="100" class="inputbox" value="<?=$row['namaibu']?>"><br>
        <input type="checkbox" name="almibu" id="almibu" value="1" title="Klik disini jika Ayah Almarhumah" <?= IntIsChecked($row['almibu'], 1) ?>/>&nbsp;&nbsp;<font color="#990000" size="1">(Almarhumah)</font>
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Status Orangtua
    </td>
    <td align="left" bgcolor="#DBD8F3">
<?php   ShowSelectStatusOrtu("statusayah", $row["statusayah"]) ?>
    </td>
    <td align="left" bgcolor="#E9AFCF">
<?php   ShowSelectStatusOrtu("statusibu", $row["statusibu"]) ?>
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Tempat Lahir
    </td>
    <td align="left" bgcolor="#DBD8F3">
        <input type="text" name="tmplahirayah" id="tmplahirayah" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahirayah']?>">
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <input type="text" name="tmplahiribu" id="tmplahiribu" size="40" maxlength="100" class="inputbox" value="<?=$row['tmplahiribu']?>">
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Tanggal Lahir
    </td>
    <td align="left" bgcolor="#DBD8F3">
<?php
        $temp = ArrayData($row, "tgllahirayah", "");
        if ($temp != "")
        {
            $ltemp = explode("-", $temp);
            $tglLahirAyah = $ltemp[2];
            $blnLahirAyah = $ltemp[1];
            $thnLahirAyah = $ltemp[0];
        }   
?>    
        <input type='text' class='inputbox' name='tgllahirayah' id='tgllahirayah' placeholder="tgl" maxlength="2" style="width: 30px" value="<?= $tglLahirAyah ?>">
        <input type='text' class='inputbox' name='blnlahirayah' id='blnlahirayah' placeholder="bln" maxlength="2" style="width: 30px" value="<?= $blnLahirAyah ?>">
        <input type='text' class='inputbox' name='thnlahirayah' id='thnlahirayah' placeholder="thn" maxlength="4" style="width: 50px" value="<?= $thnLahirAyah ?>">
    </td>
    <td align="left" bgcolor="#E9AFCF">
<?php
        $temp = ArrayData($row, "tgllahiribu", "");
        if ($temp != "")
        {
            $ltemp = explode("-", $temp);
            $tglLahirIbu = $ltemp[2];
            $blnLahirIbu = $ltemp[1];
            $thnLahirIbu = $ltemp[0];
        }   
?>    
        <input type='text' class='inputbox' name='tgllahiribu' id='tgllahiribu' placeholder="tgl" maxlength="2" style="width: 30px" value="<?= $tglLahirIbu ?>">  
        <input type='text' class='inputbox' name='blnlahiribu' id='blnlahiribu' placeholder="bln" maxlength="2" style="width: 30px" value="<?= $blnLahirIbu ?>">
        <input type='text' class='inputbox' name='thnlahiribu' id='thnlahiribu' placeholder="thn" maxlength="4" style="width: 50px" value="<?= $thnLahirIbu ?>">
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Pendidikan
    </td>
    <td align="left" bgcolor="#DBD8F3">
    <span id='spPendidikanAyah'>
<?php       ShowSelectPendidikanOrtu($db, 'pendidikanayah', $row['pendidikanayah'])  ?>
        </span>
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <span id='spPendidikanIbu'>
<?php       ShowSelectPendidikanOrtu($db, 'pendidikanibu', $row['pendidikanibu'])  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshPendidikanOrtu()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showPendidikanOrtuDialog()'>    
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Pekerjaan
    </td>
    <td align="left" bgcolor="#DBD8F3">
    <span id='spPekerjaanAyah'>
<?php       ShowSelectPekerjaanOrtu($db, 'pekerjaanayah', $row['pekerjaanayah'])  ?>
        </span>
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <span id='spPekerjaanIbu'>
<?php       ShowSelectPekerjaanOrtu($db, 'pekerjaanibu', $row['pekerjaanibu'])  ?>
        </span>
        <img src='../images/ico/refreshbw.png' class='cur-hand' onclick='refreshPekerjaanOrtu()' title='muat ulang'>
        <input type='button' class='dialogButtonGray' style='min-height: 20px' value='..' onclick='showPekerjaanOrtuDialog()'>    
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Penghasilan
    </td>
    <td align="left" bgcolor="#DBD8F3">
        <input type="text" name="penghasilanayah" id="penghasilanayah" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('penghasilanayah')" onfocus="unformatRupiah('penghasilanayah')" value="<?= FormatRupiah($row['penghasilanayah']) ?>" >
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <input type="text" name="penghasilanibu" id="penghasilanibu" size="40" maxlength="100" class="inputbox"
               onblur="formatRupiah('penghasilanibu')" onfocus="unformatRupiah('penghasilanibu')" value="<?= FormatRupiah($row['penghasilanibu']) ?>" >
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Email
    </td>
    <td align="left" bgcolor="#DBD8F3">
        <input type="text" name="emailayah" id="emailayah" size="40" maxlength="100" class="inputbox" value="<?=$row['emailayah']?>">
    </td>
    <td align="left" bgcolor="#E9AFCF">
        <input type="text" name="emailibu" id="emailibu" size="40" maxlength="100" class="inputbox" value="<?=$row['emailibu']?>">
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Nama Wali
    </td>
    <td align="left">
        <input type="text" name="namawali" id="namawali" size="40" maxlength="100" class="inputbox" value="<?=$row['wali']?>">
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Alamat Orangtua
    </td>
    <td align="left">
        <textarea name="alamatortu" id="alamatortu" rows="2" cols="30" class="inputbox"><?= $row['alamatortu'] ?></textarea>
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        Telepon
    </td>
    <td align="left">
        <input type="text" name="telponortu" id="telponortu" size="40" maxlength="100" class="inputbox" value="<?=$row['telponortu']?>">
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        HP Ortu #1
    </td>
    <td align="left">
        <input type="text" name="hportu" id="hportu" size="40" maxlength="100" class="inputbox" value="<?=$row['hportu']?>">
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        HP Ortu #2
    </td>
    <td align="left">
        <input type="text" name="hportu2" id="hportu2" size="40" maxlength="100" class="inputbox" value="<?=$row['info1']?>">
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
<tr>
    <td align="right" valign="top">
        HP Ortu #3
    </td>
    <td align="left">
        <input type="text" name="hportu3" id="hportu3" size="40" maxlength="100" class="inputbox" value="<?=$row['info2']?>">
    </td>
    <td align="left">
        &nbsp;
    </td>
    <td align="right" valign="top">
        &nbsp;
    </td>
</tr>
</table>
<br>
<table border="0" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Informasi Tambahan</font>
        <br><br>
    </td>
</tr>
<tr>
    <td width="10%" align="right" valign="top">Hobi</td>
    <td align="left" valign="top">
        <textarea name="hobi" id="hobi" rows="2" cols="40" class="inputbox"><?= $row['hobi'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td align="right" valign="top">Alamat Surat</td>
    <td align="left" valign="top">
        <textarea name="alamatsurat" id="alamatsurat" rows="2" cols="40" class="inputbox"><?= $row['alamatsurat'] ?></textarea>    	
    </td>
</tr>
<tr>
    <td align="right" valign="top">Keterangan</td>
    <td align="left" valign="top">
        <textarea name="keterangan" id="keterangan" rows="2" cols="40" class="inputbox"><?= $row['keterangan'] ?></textarea>    	
    </td>
</tr>
</table>
<br>



<table border="0" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Data Tambahan</font>
        <br><br>
    </td>
</tr>
<?php
    $sql = "SELECT replid, kolom, jenis
              FROM jbsakad.tambahandata 
             WHERE aktif = 1
               AND departemen = '$departemen'
             ORDER BY urutan";
    $resv = $db->QueryDb($sql);
    $idtambahan = "";
    while($rowv = mysqli_fetch_row($resv))
    {
        $replid = $rowv[0];
        $kolom = $rowv[1];
        $jenis = $rowv[2];

        if ($idtambahan != "") $idtambahan .= ",";
        $idtambahan .= $replid;

        $replid_data = 0;
        $data = "";
        if ($jenis == 1)
        {
            $sql = "SELECT replid, teks 
                      FROM jbsakad.tambahandatacalon 
                     WHERE nopendaftaran = '$noPendaftaran' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }
        }
        else if ($jenis == 2)
        {
            $sql = "SELECT replid, filename 
                      FROM jbsakad.tambahandatacalon 
                     WHERE nopendaftaran = '$noPendaftaran' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $filename = $row2[1];
                $data = "<a href='tambahandata.file.php?replid=$replid_data'>$filename</a>";
            }
            else
            {
                $replid_data = 0;
                $data = "";
            }
        }
        else if ($jenis == 3)
        {
            $sql = "SELECT replid, teks 
                      FROM jbsakad.tambahandatacalon 
                     WHERE nopendaftaran = '$noPendaftaran' 
                       AND idtambahan = '$replid'";
            $res2 = $db->QueryDb($sql);
            if ($row2 = mysqli_fetch_row($res2))
            {
                $replid_data = $row2[0];
                $data = $row2[1];
            }

            $sql = "SELECT pilihan 
                      FROM jbsakad.pilihandata 
                     WHERE idtambahan = '$replid'
                       AND aktif = 1
                     ORDER BY urutan";
            $res2 = $db->QueryDb($sql);

            $arrList = array();
            if (mysqli_num_rows($res2) == 0)
                $arrList[] = "-";

            while($row2 = mysqli_fetch_row($res2))
            {
                $arrList[] = $row2[0];
            }

            $opt = "";
            for($i = 0; $i < count($arrList); $i++)
            {
                $pilihan = $arrList[$i];
                $sel = $pilihan == $data ? "selected" : "";
                $opt .= "<option value='$pilihan' $sel>$pilihan</option>";
            }
        }

        ?>
        <tr style="height: 24px;">
            <td width="10%" align="right" valign="top"><?=$kolom?></td>
            <td colspan="2">
                <?php if ($jenis == 1) { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="1">
                    <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="text" class="inputbox" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="40" maxlength="1000" value="<?=$data?>">
                <?php } else if ($jenis == 2) { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="2">
                    <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <input type="file" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" size="25" style="width:215px">
                    <i><?=$data?></i>
                <?php } else { ?>
                    <input type="hidden" id="jenisdata-<?=$replid?>" name="jenisdata-<?=$replid?>" value="3">
                    <input type="hidden" id="repliddata-<?=$replid?>" name="repliddata-<?=$replid?>" value="<?=$replid_data?>">
                    <select class="inputbox" name="tambahandata-<?=$replid?>" id="tambahandata-<?=$replid?>" style="width:215px">
                        <?= $opt ?>
                    </select>
                <?php } ?>
            </td>
        </tr>
        <?php
    }
    ?>
    <input type="hidden" id="idtambahan" name="idtambahan" value="<?=$idtambahan?>">
</table>



<table border="0" width="100%" cellpadding="5" cellspacing="0">
<tr>
    <td colspan="2" align="left">
        <br>
        <font style="color: #800000; font-size: 16px;">Data Sumbangan &amp; Nilai</font>
        <br><br>
    </td>
</tr>
<?php
    $sql = "SELECT COUNT(replid) 
              FROM jbsakad.settingpsb 
             WHERE idproses = $idProses";
	$res2 = $db->QueryDb($sql);
	$row2 = mysqli_fetch_row($res2);
	$ndata = $row2[0];
	
	if ($ndata > 0)
	{
		$sql = "SELECT *
                  FROM jbsakad.settingpsb
                 WHERE idproses = $idProses";
		$res2 = $db->QueryDb($sql);
		$row2 = mysqli_fetch_array($res2);
		
		$kdsum1 = $row2['kdsum1']; 
		$kdsum2 = $row2['kdsum2']; 
		$kdujian1 = $row2['kdujian1'];
		$kdujian2 = $row2['kdujian2'];
		$kdujian3 = $row2['kdujian3'];
		$kdujian4 = $row2['kdujian4'];
		$kdujian5 = $row2['kdujian5'];
		$kdujian6 = $row2['kdujian6'];
		$kdujian7 = $row2['kdujian7'];
		$kdujian8 = $row2['kdujian8'];
		$kdujian9 = $row2['kdujian9'];
		$kdujian10 = $row2['kdujian10'];
	}
?>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Sumbangan #1</span><br><b><?=$kdsum1?></b></td>
        <td colspan="2">
            <input type="text" name="sum1" id="sum1" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;" onblur="formatRupiah('sum1')" onfocus="unformatRupiah('sum1')" 
                   value="<?php if ($row['sum1'] == 0) echo ''; else echo FormatRupiah($row['sum1']); ?>" />
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Sumbangan #2</span><br><b><?=$kdsum2?></b></td>
        <td colspan="2">
            <input type="text" name="sum2" id="sum2" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;" onblur="formatRupiah('sum2')" onfocus="unformatRupiah('sum2')"
                   value="<?php if ($row['sum2'] == 0) echo ''; else echo FormatRupiah($row['sum2']); ?>" />
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #1</span><br><b><?=$kdujian1?></b></td>
        <td colspan="2">
            <input type="text" name="ujian1" id="ujian1" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian1'] == 0) echo ''; else echo $row['ujian1']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #2</span><br><b><?=$kdujian2?></b></td>
        <td colspan="2">
            <input type="text" name="ujian2" id="ujian2" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian2'] == 0) echo ''; else echo $row['ujian2']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #3</span><br><b><?=$kdujian3?></b></td>
        <td colspan="2">
            <input type="text" name="ujian3" id="ujian3" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian3'] == 0) echo ''; else echo $row['ujian3']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #4</span><br><b><?=$kdujian4?></b></td>
        <td colspan="2">
            <input type="text" name="ujian4" id="ujian4" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian4'] == 0) echo ''; else echo $row['ujian4']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #5</span><br><b><?=$kdujian5?></b></td>
        <td colspan="2">
            <input type="text" name="ujian5" id="ujian5" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian5'] == 0) echo ''; else echo $row['ujian5']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #6</span><br><b><?=$kdujian6?></b></td>
        <td colspan="2">
            <input type="text" name="ujian6" id="ujian6" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian6'] == 0) echo ''; else echo $row['ujian6']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #7</span><br><b><?=$kdujian7?></b></td>
        <td colspan="2">
            <input type="text" name="ujian7" id="ujian7" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian7'] == 0) echo ''; else echo $row['ujian7']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #8</span><br><b><?=$kdujian8?></b></td>
        <td colspan="2">
            <input type="text" name="ujian8" id="ujian8" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian8'] == 0) echo ''; else echo $row['ujian8']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #9</span><br><b><?=$kdujian9?></b></td>
        <td colspan="2">
            <input type="text" name="ujian9" id="ujian9" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian9'] == 0) echo ''; else echo $row['ujian9']; ?>">
        </td>
    </tr>
    <tr style="height: 24px;">
        <td width="10%" align="right" valign="top"><span class='fg-secondary'>Ujian #10</span><br><b><?=$kdujian10?></b></td>
        <td colspan="2">
            <input type="text" name="ujian10" id="ujian10" size="10" maxlength="15" class="inputbox"
                   style="width: 150px;"
                   value="<?php if ($row['ujian10'] == 0) echo ''; else echo $row['ujian10']; ?>">
        </td>
    </tr>
</table>
</form>