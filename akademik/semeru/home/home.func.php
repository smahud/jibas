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
$nRowPerPage = 2;
$nColPerRow = 3;

function ShowUserInfo($db)
{
    global $SI_USER_LANDLORD;

    if (SI_USER_LEVEL() == $SI_USER_LANDLORD)
    {
        ShowUserAdminInfo($db);
    }
    else 
    {
        ShowUserStafInfo($db);
    }
}

function ShowUserAdminInfo($db)
{
    echo "<table border='0' width='100%'>";
    echo "<tr>";
    echo "<td width='130'>";
    $userFoto = UserInfo::$DefaultFoto;
    echo "<img style='width: 100px; height: 100px;' class='avatar-circle'";
    echo "src='data:image/jpg;base64," .  $userFoto . "'>";
    echo "</td>";
    echo "<td>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 11px; color: #666;\">Selamat Datang</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 24px; color: #333; font-weight: bold\">";
    echo "Administrator JIBAS";
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 18px; color: #333;\">";
    echo "jibas";
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 12px; color: #666;\">";
    echo "Administrator";
    echo "</span>&nbsp;&nbsp;";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
}

function ShowUserStafInfo($db)
{
    $nip = SI_USER_ID();
    $sql = "SELECT p.nip, p.nama, p.bagian, p.gelarawal, p.gelarakhir,
                   IF(p.foto IS NULL, 0, 1) AS fotoexist, IF(p.foto IS NULL, '', TO_BASE64(p.foto)) as foto64
              FROM jbssdm.pegawai p
             WHERE p.nip = '$nip'";
    $res = $db->QueryDb($sql);
    if (mysqli_num_rows($res) == 0)
        return "";

    $row = mysqli_fetch_array($res);

    echo "<table border='0' width='100%'>";
    echo "<tr>";
    echo "<td width='130'>";
    $userFoto = $row['fotoexist'] == 1 ? $row['foto64'] : UserInfo::$DefaultFoto;
    echo "<img style='width: 100px; height: 100px;' class='avatar-circle'";
    echo "src='data:image/jpg;base64," .  $userFoto . "'>";
    echo "</td>";
    echo "<td>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 11px; color: #666;\">Selamat Datang</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 24px; color: #333; font-weight: bold\">";
    echo $row['nama'];
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 18px; color: #333;\">";
    echo $row['nip'];
    echo "</span><br>";
    echo "<span style=\"font-family: 'Segoe UI', serif; font-size: 12px; color: #666;\">";
    echo $row['bagian'];
    echo "</span>&nbsp;&nbsp;";
    echo "</td>";
    echo "</tr>";
    echo "</table>";

}

function ShowSelectDepartemen($db)
{
    global $departemen;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departemen' onchange='onChangeDept()' style='width:250px' class='inputbox'>";
        foreach ($dep as $value)
        {
            if (!$departemen == "")
                $departemen = $value;
            
            $sel = $departemen == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo $ex->getMessage();
    }
}

function ShowSelectDepartemenRekap($db)
{
    global $departemenRekap, $SI_USER_STAFF;

    try
    {
        $dep = getDepartemen($db, SI_USER_ACCESS());
     
        echo "<select id='departemenrekap' onchange='onChangeDeptRekap()' style='width:250px' class='inputbox'>";
        if (SI_USER_LEVEL() != $SI_USER_STAFF)
        {
            $departemenRekap = '---';
            echo "<option value='---'>Semua Departemen</option>";
        }
            
        foreach ($dep as $value)
        {
            if ($departemenRekap == "")
                $departemenRekap = $value;

            $sel = $departemenRekap == $value ? "selected" : "";
            echo "<option value='$value' $sel>$value</option>";
        }
        echo "</select>";
    }
    catch (Exception $ex) 
    {
        echo $ex->getMessage();
    }
}

function ShowSelectBagianNota($db)
{
    global $bagianNota;

    try
    {
        echo "<select id='bagiannota' onchange='onChangeBagianNota()' class='inputbox' style='width:250px'>";
        $sql = "SELECT bagian, urutan
                  FROM jbsumum.bagiannota
                 ORDER BY urutan";
        $res = $db->QueryDb($sql);

        echo "<option value='---'>Semua Bagian</option>";
        while ($row = mysqli_fetch_row($res))
        {
            $sel = ($bagianNota == $row[0]) ? "selected" : "";
            echo "<option value='$row[0]' $sel>$row[0]</option>";
        }
        echo "</select>";
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
}

function ShowSelectBulan()
{
    echo "<select id='bulan' onchange='onChangeBulanTahun()' disabled class='inputbox' style='width: 150px'>";
    for($i = 1; $i <= 12; $i++)
    {
        $sel = date('n') == $i ? "selected" : "";
        echo "<option value='$i' $sel>" . NamaBulan($i) . "</option>";
    }
    echo "</select>";
}

function ShowSelectTahun()
{
    echo "<select id='tahun' onchange='onChangeBulanTahun()' disabled class='inputbox' style='width: 80px'>";
    $thn = date('Y');
    for($i = $thn - 3; $i <= $thn + 1; $i++)
    {
        $sel = date('Y') == $i ? "selected" : "";
        echo "<option value='$i' $sel>$i</option>";
    }
    echo "</select>";
}

function ShowSelectPenulis($db)
{
    try
    {
        $sql = "SELECT DISTINCT IFNULL(n.pemilik, 'jibas') AS pemilik, IFNULL(p.nama, 'Administrator JIBAS') AS nama
                  FROM jbsumum.nota n
                  LEFT JOIN jbssdm.pegawai p ON n.pemilik = p.nip
                 ORDER BY nama";
        
        $res = $db->QueryDb($sql);
        
        echo "<select id='penulis' onchange='onChangePenulis()' disabled class='inputbox' style='width: 200px'>";
        while ($row = mysqli_fetch_row($res))
        {
            echo "<option value='$row[0]'>$row[1]</option>";
        }
        echo "</select>";
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
}

function FetchListNota()
{
    global $nRowPerPage, $nColPerRow;

    $db = new Db();
    try
    {
        $db->Open();

        $page = RequestData("page", 1);
        $departemen = RequestData("departemen", "");
        $bagianNota = RequestData("bagiannota", "---");
        $userId = RequestData("userid", "");
        $kelompok = RequestData("kelompok", "");
        $bulan = RequestData("bulan", 0);
        $tahun = RequestData("tahun", 0);
        $penulis = RequestData("penulis", "");
        $keyword = RequestData("keyword", "");

        $userCol = "";
        if ($kelompok == "siswa")
            $userCol = "nis";
        else if ($kelompok == "calonsiswa")
            $userCol = "nic";
        else if ($kelompok == "pegawai")
            $userCol = "nip";

        
        //$startIndex = ($page - 1) * $nItem;
        
        $sql = "SELECT id
                  FROM jbsumum.nota
                 WHERE departemen = '$departemen'";

        if ($bagianNota != "---")                 
            $sql .= " AND bagian = '$bagianNota'";

        if ($userId != "")
            $sql .= " AND $userCol = '$userId'";

        if ($bulan != 0 && $tahun != 0)
            $sql .= " AND (month(tanggal) = $bulan AND year(tanggal) = $tahun) ";

        if ($keyword != "")            
        {
            $kw = "";
            $kataKunci = explode(" ", $keyword);
            foreach ($kataKunci as $kata)
            {
                if (strlen($kata) >= 3)
                    $kw .= "+$kata ";
            }
            
            $sql .= " AND MATCH(judul, nota) AGAINST('$kw' IN BOOLEAN MODE)";
        }
        
        if ($penulis != "")
        {
            if ($penulis == "jibas")
                $sql .= " AND pemilik IS NULL";
            else 
                $sql .= " AND pemilik = '$penulis'";
        }

        $sql .= " ORDER BY id DESC
                  LIMIT 1000";      

        $res = $db->QueryDb($sql);
        $nData = mysqli_num_rows($res);
        if ($nData == 0)
            return json_encode([0, "belum ada data", ""]);
        
        $nItemPerPage = $nRowPerPage * $nColPerRow;
        $lsIdNota = array(); 
        $stIdNota = "";
        $cnt = 0;
        while($row = mysqli_fetch_row($res))
        {
            if ($cnt == 0)
                $stIdNota = "";
            
            if ($stIdNota != "") $stIdNota .= ",";
            $stIdNota .= $row[0];
            $cnt += 1;

            if ($cnt == $nItemPerPage)
            {
                $lsIdNota[] = $stIdNota;
                $cnt = 0;
            }
        }

        if ($stIdNota != "")
        {
            $lsIdNota[] = $stIdNota;
        }

        return json_encode([1, $nData, $lsIdNota]);
    }
    catch (Exception $e)
    {
        return json_encode([-1, $e->getMessage(), ""]);
    }
    finally
    {
        $db->Close();
    }
}

function ShowTableNota()
{
    global $SI_USER_LANDLORD;
    global $nColPerRow;

    $db = new Db();
    try 
    {
        $db->Open();

        $stIdNota = RequestData("stidnota", "");

        $sql = "SELECT n.id, n.judul, n.nota, n.bagian, 
                       IFNULL(n.pemilik, 'jibas') AS pemilik,
                       IFNULL(pg.nama, 'Administrator JIBAS') AS namapemilik,     
                       DATE_FORMAT(n.waktu, '%d %M %Y %H:%i') AS fwaktu,
                       IFNULL(n.nis, '') AS nis, IFNULL(s.nama, '') AS namasiswa,     
                       IFNULL(n.nic, '') AS nic, IFNULL(cs.nama, '') AS namacalon,
                       IFNULL(n.nip, '') AS nip, IFNULL(p.nama, '') AS namapegawai
                  FROM jbsumum.nota n
                  LEFT JOIN jbsakad.siswa s ON n.nis = s.nis
                  LEFT JOIN jbsakad.calonsiswa cs ON n.nic = cs.nopendaftaran
                  LEFT JOIN jbssdm.pegawai p ON n.nip = p.nip
                  LEFT JOIN jbssdm.pegawai pg ON n.pemilik = pg.nip
                 WHERE n.id IN ($stIdNota)
                 ORDER BY n.id DESC";
        $res = $db->QueryDb($sql);
        if (mysqli_num_rows($res) == 0)
        {
            echo "<br><br><br><span class='fg-secondary'><i>Belum ada data</i></span>";
            return;
        }

        echo "<table border='0' cellpadding='5' cellspacing='5' width='100%'>";

        $cnt = 0;
        while ($row = mysqli_fetch_array($res))
        {
            $cnt += 1;

            $idNota = $row['id'];
            $judul = $row['judul'];
            $nota = $row['nota'];
            $bagian = $row['bagian'];
            $pemilik = $row['pemilik'];
            $namaPemilik = $row['namapemilik'];
            $fwaktu = $row['fwaktu'];

            if ($cnt == 1)
                echo "<tr style='height: 350px'>";

            if ($pemilik == "landlord")
                $userInfo = "Administrator JIBAS";
            else 
                $userInfo = "$namaPemilik ($pemilik)";

            $kelompok = "Umum";
            $personId = "";
            $personName = "";

            if ($row["nis"] != "")
            {
                $kelompok = "Siswa";
                $personId = $row["nis"];
                $personName = $row["namasiswa"];
            }
            else if ($row["nic"] != "")
            {
                $kelompok = "Calon Siswa";
                $personId = $row["nic"];
                $personName = $row["namacalon"];
            }
            else if ($row["nip"] != "")
            {
                $kelompok = "Pegawai";
                $personId = $row["nip"];
                $personName = $row["namapegawai"];
            }

            $kelompokInfo = "";
            if ($kelompok == "Umum")
                $kelompokInfo = "";
            else 
                $kelompokInfo = "$kelompok | $personName ($personId)";
                        
            echo "<td width='33%' align='left' valign='top' onmouseover='onHoverNota($idNota)' onmouseleave='onLeaveNota($idNota)'>";
            echo "<div id='nota-$idNota' style='overflow: auto; width: 100%; height: 250px'>";

            echo "<span class='fs-11' style='display: inline-block; border-radius: 5px; padding: 5px 5px; background-color: #efefef'>&nbsp;$bagian&nbsp;</span>&nbsp;&nbsp;";
            echo "<span class='fs-11 fg-maroon'>$kelompokInfo</span><br>";

            echo "<span class='fs-14 fst-bold fg-black'>$judul</span><br>";
            echo "<span class='fs-11 fg-secondary fst-italic'>oleh $userInfo - $fwaktu</span><br><br>";
            
            echo "<span class='fs-13' style='line-height: 25px;'>";
            echo $nota;
            echo "</span>";
            echo "</div>";
            echo "<div id='menu-nota-$idNota' style='width: 100%; height: 30px; text-align: left; padding-left: 3px; visibility: hidden; '>";
            echo "<img src='../images/ico/lihat.png' onclick='view($idNota)' class='cur-hand' title='lihat'>&nbsp;&nbsp;&nbsp;";

            if ((SI_USER_LEVEL() == $SI_USER_LANDLORD && $pemilik == "jibas") || SI_USER_ID() == $pemilik)
            {
                echo "<img src='../images/ico/ubah.png' onclick='edit($idNota)' class='cur-hand' title='edit'>&nbsp;&nbsp;&nbsp;";
                echo "<img src='../images/ico/hapus.png' onclick='hapus($idNota)' class='cur-hand' title='hapus'>";
            }

            echo "</div>";
            echo "</td>";

            if ($cnt == $nColPerRow)
            {
                echo "</tr>";
                $cnt = 0;
            }
        }

        if ($cnt > 0)
        {
            while ($cnt < $nColPerRow)
            {
                echo "<td width='33%' align='left'></td>";
                $cnt += 1;
            }
            echo "</tr>";
        }
        echo "</table>";
        
    }
    catch(Exception $ex)
    {
        $db->LogLastErrorIfExist();
        echo $ex->getMessage();
    }
    finally
    {
        $db->Close();
    }
}

function ShowPageControl()
{
    global $nData, $nRowPerPage, $nColPerRow;
    
    $nData = RequestData("ndata", 0);

    $nPage = 1;
    $nItem = $nRowPerPage * $nColPerRow;
    if ($nData > $nItem)
        $nPage = ceil($nData / $nItem);

    echo "Halaman ";
    echo "<select class='inputbox' id='page' style='width: 50px' onchange='onChangePage()'>";
    for ($i = 1; $i <= $nPage; $i++)
    {   
        echo "<option value='$i'>$i</option>";
    }
    echo "</select>";
    echo " dari <span id='spNPage'>$nPage</span>, jumlah <span id='spNData'>$nData</span> data";
    
}

function HapusNota()
{
    $db = new Db();
    try
    {
        $db->Open();

        $id = RequestData("id", 0);

        $sql = "DELETE FROM jbsumum.nota
                 WHERE id = $id";
        $db->QueryDb($sql);

        return json_encode([1, "OK"]);
    }
    catch (Exception $e)
    {
        return json_encode([-1, $e->getMessage()]);
    }
    finally
    {
        $db->Close();
    }   
}

function ShowRekapJumlah($db)
{
    global $departemenRekap;

    if ($departemenRekap == "---")
    {
        $sqlAktif = "SELECT COUNT(replid)
                       FROM jbsakad.siswa 
                      WHERE aktif = 1";
        $sqlNonAktif = "SELECT COUNT(replid)
                          FROM jbsakad.siswa 
                         WHERE aktif = 0
                           AND alumni = 0";
        $sqlAlumni = "SELECT COUNT(replid)
                        FROM jbsakad.siswa 
                       WHERE aktif = 0
                         AND alumni = 1";
    }
    else 
    {
        $sqlAktif = "SELECT COUNT(s.replid)
                       FROM jbsakad.siswa s, jbsakad.angkatan a
                      WHERE s.idangkatan = a.replid
                        AND s.aktif = 1
                        AND a.departemen = '$departemenRekap'";
        $sqlNonAktif = "SELECT COUNT(s.replid)
                          FROM jbsakad.siswa s, jbsakad.angkatan a
                         WHERE s.idangkatan = a.replid
                           AND s.aktif = 0
                           AND s.alumni = 0
                           AND a.departemen = '$departemenRekap'";
        $sqlAlumni = "SELECT COUNT(s.replid)
                        FROM jbsakad.siswa s, jbsakad.angkatan a
                       WHERE s.idangkatan = a.replid
                         AND s.aktif = 0
                         AND s.alumni = 1
                         AND a.departemen = '$departemenRekap'";
    }
    $resAktif = $db->QueryDb($sqlAktif);
    $row = mysqli_fetch_row($resAktif);
    $nSiswaAktif = $row[0];
    
    $resNonAktif = $db->QueryDb($sqlNonAktif);
    $row = mysqli_fetch_row($resNonAktif);
    $nSiswaNonAktif = $row[0];
    
    $resAlumni = $db->QueryDb($sqlAlumni);
    $row = mysqli_fetch_row($resAlumni);
    $nSiswaAlumni = $row[0];
    
    echo "<div style='box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); border-radius: 10px; width: 220px; height: 90px; text-align: center; padding: 10px; display: inline-block; color: white; background-color: #08810eff'>
                <span class='fs-12' style='color: yellow'>Jumlah Siswa</span><br>
                <div style='margin-top: 10px; position: relative; '>
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Aktif</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nSiswaAktif</span>
                    </span>                    
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Non-Aktif</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nSiswaNonAktif</span>
                    </span>                    
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Alumni</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nSiswaAlumni</span>
                    </span>                    
                </div>
            </div>";
        
    $sqlAktif = "SELECT COUNT(replid)
                   FROM jbssdm.pegawai 
                  WHERE aktif = 1";
    $resAktif = $db->QueryDb($sqlAktif);
    $row = mysqli_fetch_row($resAktif);
    $nPegawaiAktif = $row[0];
        
    $sqlNonAktif = "SELECT COUNT(replid)
                      FROM jbssdm.pegawai 
                     WHERE aktif = 0";
    $resNonAktif = $db->QueryDb($sqlNonAktif);
    $row = mysqli_fetch_row($resNonAktif);
    $nPegawaiNonAktif = $row[0];
    
    echo "<div style='box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); border-radius: 10px; margin-left: 20px; width: 220px; height: 90px; text-align: center; padding: 10px; display: inline-block; color: white; background-color: #085181ff'>
                <span class='fs-12' style='color: yellow'>Jumlah Pegawai</span><br>
                <div style='margin-top: 10px; position: relative; '>
                    <span style='float: left; text-align: center; width: 100px'>
                        <span class='fs-11' style='color: #ccc'>Aktif</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nPegawaiAktif</span>
                    </span>                    
                    <span style='float: left; text-align: center; width: 100px'>
                        <span class='fs-11' style='color: #ccc'>Non-Aktif</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nPegawaiNonAktif</span>
                    </span>                  
                </div>
            </div>";

    if ($departemenRekap == "---")            
    {
        $sqlGuru = "SELECT COUNT(DISTINCT nip)
                     FROM jbsakad.guru
                    WHERE aktif = 1";

        $sqlPelajaran = "SELECT COUNT(replid)
                           FROM jbsakad.pelajaran
                          WHERE aktif = 1";                    

        $sqlKelas = "SELECT COUNT(replid)
                       FROM jbsakad.kelas k
                      WHERE aktif = 1";
    }
    else
    {
        $sqlGuru = "SELECT COUNT(DISTINCT g.nip)
                      FROM jbsakad.guru g, jbsakad.pelajaran p
                     WHERE g.idpelajaran = p.replid
                       AND p.departemen = '$departemenRekap'
                       AND g.aktif = 1
                       AND p.aktif = 1";

        $sqlPelajaran = "SELECT COUNT(replid)
                           FROM jbsakad.pelajaran
                          WHERE departemen = '$departemenRekap'
                            AND aktif = 1";

        $sqlKelas = "SELECT COUNT(DISTINCT k.replid)
                       FROM jbsakad.kelas k, jbsakad.tahunajaran ta
                      WHERE k.idtahunajaran = ta.replid
                        AND ta.departemen = '$departemenRekap'
                        AND k.aktif = 1
                        AND ta.aktif = 1";                            
    }
    
    $resGuru = $db->QueryDb($sqlGuru);
    $row = mysqli_fetch_row($resGuru);
    $nGuru = $row[0];

    $resPelajaran = $db->QueryDb($sqlPelajaran);
    $row = mysqli_fetch_row($resPelajaran);
    $nPelajaran = $row[0];

    $resKelas = $db->QueryDb($sqlKelas);
    $row = mysqli_fetch_row($resKelas);
    $nKelas = $row[0];

    echo "<div style='box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); border-radius: 10px; margin-left: 20px; width: 220px; height: 90px; text-align: center; padding: 10px; display: inline-block; color: white; background-color: #810840ff'>
                <span class='fs-12' style='color: yellow'>Guru, Pelajaran, Kelas</span><br>
                <div style='margin-top: 10px; position: relative; '>
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Guru</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nGuru</span>
                    </span>                  
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Pelajaran</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nPelajaran</span>
                    </span>
                    <span style='float: left; text-align: center; width: 70px'>
                        <span class='fs-11' style='color: #ccc'>Kelas</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nKelas</span>
                    </span>
                </div>
            </div>";       
        
    $sqlNilai = "SELECT COUNT(u.replid)
                       FROM jbsakad.ujian u, jbsakad.kelas k, jbsakad.tahunajaran ta
                      WHERE u.idkelas = k.replid
                        AND k.idtahunajaran = ta.replid
                        AND ta.aktif = 1";             
    if ($departemenRekap != "---")       
    {
        $sqlNilai .= " AND ta.departemen = '$departemenRekap'";
    }

    $sqlLastNilai = "SELECT u.tanggal, k.kelas, p.nama, u.deskripsi
                       FROM jbsakad.ujian u, jbsakad.kelas k, jbsakad.tahunajaran ta, jbsakad.pelajaran p
                      WHERE u.idkelas = k.replid
                        AND k.idtahunajaran = ta.replid
                        AND u.idpelajaran = p.replid
                        AND ta.aktif = 1";
    if ($departemenRekap != "---")       
    {
        $sqlLastNilai .= " AND ta.departemen = '$departemenRekap'";
    }           
    $sqlLastNilai .= " ORDER BY u.replid DESC LIMIT 1";           

    $resNilai = $db->QueryDb($sqlNilai);
    $row = mysqli_fetch_row($resNilai);
    $nNilai = $row[0];

    $resLastNilai = $db->QueryDb($sqlLastNilai);
    if ($row = mysqli_fetch_assoc($resLastNilai))
    {
        $tglNilai = date("d-m-Y", strtotime($row['tanggal']));
        $kelasNilai = $row['kelas'];
        $pelajaranNilai = $row['nama'];
        $deskripsiNilai = $row['deskripsi'];
    }
    else
    {
        $tglNilai = "";
        $kelasNilai = "";
        $pelajaranNilai = "";
        $deskripsiNilai = "";
    }

    echo "<div style='box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); border-radius: 10px; margin-left: 20px; width: 280px; height: 90px; text-align: center; padding: 10px; display: inline-block; color: white; background-color: #945e17ff'>
                <span class='fs-12' style='color: yellow'>Penilaian</span><br>
                <div style='margin-top: 10px; position: relative; '>
                    <span style='float: left; text-align: left; width: 100px'>
                        <span class='fs-11' style='color: #ccc'>Jumlah</span><br>
                        <span class='fs-24 ff-courier fst-bold'>$nNilai</span>
                    </span>                  
                    <span style='float: left; text-align: left; width: 170px;'>
                        <span class='fs-11' style='color: #ccc'>Terakhir</span><br>
                        <span class='fs-11 ff-courier fst-bold'>$pelajaranNilai<br>$deskripsiNilai<br>$tglNilai $kelasNilai<br></span>
                    </span>
                </div>
            </div>";

    $sqlHadirSiswa = "SELECT COUNT(replid)
                        FROM jbssat.frpresence
                        WHERE date_in = CURDATE()";
    if ($departemenRekap != "---")                        
        $sqlHadirSiswa .= " AND departemen = '$departemenRekap'";
    $sqlHadirSiswa .= " AND nis IS NOT NULL";
    $resHadirSiswa = $db->QueryDb($sqlHadirSiswa);
    $row = mysqli_fetch_row($resHadirSiswa);
    $nHadirSiswa = $row[0];

    $sqlHadirPegawai = "SELECT COUNT(replid)
                        FROM jbssat.frpresence
                        WHERE date_in = CURDATE()
                          AND nip IS NOT NULL";
    $resHadirPegawai = $db->QueryDb($sqlHadirPegawai);
    $row = mysqli_fetch_row($resHadirPegawai);
    $nHadirPegawai = $row[0];

    

    echo "<div style='box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.75); border-radius: 10px; margin-left: 20px; width: 280px; height: 90px; text-align: center; padding: 10px; display: inline-block; color: white; background-color: #178594ff'>
                <span class='fs-12' style='color: yellow'>Presensi Wajah dan Fingerprint</span><br>
                <span class='fs-10 fg-white'>(hari ini)</span>
                <div style='margin-top: 10px; position: relative; '>
                    <span style='float: left; text-align: center; width: 120px'>
                        <span class='fs-11' style='color: #ccc'>Siswa</span><br>
                        <span class='fs-24 ff-courier fst-bold fg-white'>$nHadirSiswa</span>
                        <span class='fs-12 fg-white'>&nbsp;|&nbsp;</span>
                        <span class='fs-12 ff-courier fst-bold fg-white'>$nSiswaAktif</span>
                    </span>                  
                    <span style='float: left; text-align: center; width: 120px'>
                        <span class='fs-11' style='color: #ccc'>Pegawai</span><br>
                        <span class='fs-24 ff-courier fst-bold fg-white'>$nHadirPegawai</span>
                        <span class='fs-12 fg-white'>&nbsp;|&nbsp;</span>
                        <span class='fs-12 ff-courier fst-bold fg-white'>$nPegawaiAktif</span>
                    </span>
                </div>
            </div>";

}

?>