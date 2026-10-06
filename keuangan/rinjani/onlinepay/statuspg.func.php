<?php
/**[N]**
 * JIBAS Education Community
 * Jaringan Informasi Bersama Antar Sekolah
 *
 * @version: 35.5 (August 10, 2026)
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
function ProcessFetchStatusPg()
{
    global $SJS_ADDR,$SJS_PORT;
    
    try
    {
        if ($SJS_ADDR == "" || $SJS_PORT == "")
        {
            echo "[-1,\"Pengaturan Sinkronisasi Jendela Sekolah belum dilakukan\"]";
            return;
        }

        $sjsIpAddr = "$SJS_ADDR:$SJS_PORT";
        
        $http = new HttpManager($sjsIpAddr);
        $http->setData("op=statuspg");
        $sendGr = $http->send();
        if ($sendGr->Value < 0)
        {
            $msg = $sendGr->Text;
            echo "[-1,\"$msg\",\"\"]";
            return;
        }

        $respGr = GenericReturn::fromJson($sendGr->Data);
        if ($respGr->Value < 0)
        {
            $msg = $respGr->Text;
            echo "[-1,\"$msg\",\"\"]";
            return;
        }

        $jsonData = $respGr->Data;
        $lsData = json_decode($jsonData);
        $regis = $lsData[0];
        $trialCount = $lsData[1];
        $saldo = $lsData[2];
        $aktif = $lsData[3];
        $statusDesc = $lsData[4];
        $dbId = $lsData[5];
        $maxTrial = $lsData[6];

        $regisInfo = $regis == 0 ? "TRIAL" : "REGISTER";
        $aktifInfo = $aktif == 1 ? "AKTIF" : "NON AKTIF";

        $table  = "<a href='#' style='color: blue; font-weight: normal; text-decoration: underline;' onclick='refreshStatusPg()'>muat ulang</a><br><br>";
        $table .= "<table class='tab' cellpadding='5' cellspacing='0'>";
        $table .= "<tr>";
        $table .= "<td width='120'>Registrasi</td>";
        $table .= "<td width='250'><b>$regisInfo</b></td>";
        $table .= "</tr>";
        $table .= "<tr>";
        $table .= "<td>Database Id</td>";
        $table .= "<td><b>$dbId</b></td>";
        $table .= "</tr>";
        $table .= "<tr>";
        $table .= "<td>Status</td>";
        $table .= "<td><b>$aktifInfo</b></td>";
        $table .= "</tr>";

        if ($regis == 0)
        {
            $table .= "<tr>";
            $table .= "<td>Trial Count</td>";
            $table .= "<td><b>$trialCount dari $maxTrial</b></td>";
            $table .= "</tr>";
        }
        else 
        {
            $rpSaldo = FormatRupiah($saldo);
            $table .= "<tr>";
            $table .= "<td>Saldo</td>";
            $table .= "<td><b>$rpSaldo</b></td>";
            $table .= "</tr>";
        }
        $table .= "<tr>";
        $table .= "<td>Deskripsi</td>";
        $table .= "<td>$statusDesc</td>";
        $table .= "</tr>";
        $table .= "</table>";

        $table64 = base64_encode($table);
        echo "[1,\"OK\",\"$table64\"]";
    }
    catch (Exception $ex)
    {
        $msg = $ex->getMessage();
        echo "[-1,\"$msg\",\"\"]";
    }
}
?>