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
require_once('../include/sessioninfo.php');
require_once('../include/sessionchecker.php');
require_once('../include/config.php');
require_once('../include/db.onfunc.php');
require_once('../library/msg.php');
require_once('../library/logger.php');
require_once('../library/common.func.php');
require_once('../util/peek.php');
require_once('penempatan.dialog.func.php');

/*
INSERT INTO jbsakad.siswa (nis, nisn, nik, noun, nama, panggilan, aktif, tahunmasuk, idangkatan, idkelas, suku, agama, status, kondisi, kelamin, tmplahir, tgllahir, warga, anakke, jsaudara, statusanak, jkandung, jtiri, bahasa, berat, tinggi, darah, foto, pinsiswa, alamatsiswa, jarak, kodepossiswa, telponsiswa, hpsiswa, emailsiswa, kesehatan, asalsekolah, noijasah, tglijasah, ketsekolah, namaayah, namaibu, statusayah, statusibu, tmplahirayah, tmplahiribu, tgllahirayah, tgllahiribu, almayah, almibu, pendidikanayah, pendidikanibu, pekerjaanayah, pekerjaanibu, wali, penghasilanayah, penghasilanibu, alamatortu, telponortu, hportu, emailayah, emailibu, alamatsurat, keterangan, hobi, pinortu, pinortuibu, info1, info2, info3)
SELECT TBD_NIS, nisn, nik, noun, nama, panggilan, aktif, TBD_TAHUNMASUK, TBD_IDANGKATAN, TBD_IDKELAS, suku, agama, status, kondisi, kelamin, tmplahir, tgllahir, warga, anakke, jsaudara, statusanak, jkandung, jtiri, bahasa, berat, tinggi, darah, foto, pinsiswa, alamatsiswa, jarak, kodepossiswa, telponsiswa, hpsiswa, emailsiswa, kesehatan, asalsekolah, noijasah, tglijasah, ketsekolah, namaayah, namaibu, statusayah, statusibu, tmplahirayah, tmplahiribu, tgllahirayah, tgllahiribu, almayah, almibu, pendidikanayah, pendidikanibu, pekerjaanayah, pekerjaanibu, wali, penghasilanayah, penghasilanibu, alamatortu, telponortu, hportu, emailayah, emailibu, alamatsurat, keterangan, hobi, TBD_pinortu, TBD_pinortuibu, info1, info2, info3 WHERE replid = TBD_replid
*/

$op = RequestData("op", "");
if ($op == "simpan")
{
    echo SimpanPenempatanSiswa();
}
?>
