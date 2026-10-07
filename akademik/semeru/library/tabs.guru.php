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
require_once ("tabs.guru.func.php");

$divStyle = "";
if ($display == "dialog")
{
    $divStyle = "style='max-height: 450px; overflow: auto;'";
}
?>
<script language="JavaScript">
    var tabguru_acceptResult = null;

    $(document).ready(function ()
    {
        if ($("#tabguru_table_pilih").length)
            Tables("tabguru_table_pilih", 1, 0);
    });

    function tabguru_pilihGuru (kelompok, json64)
    {
        if (tabguru_acceptResult === null)
            return;

        tabguru_acceptResult(kelompok, json64);
    }

    function tabguru_setAcceptResult(acceptResult)
    {
        tabguru_acceptResult = acceptResult;
    }

    function tabguru_onDepartemenChange()
    {
        tabguru_reloadDaftarGuru("p.nama");
    }

    function tabguru_onChangePelajaran()
    {
        tabguru_reloadDaftarGuru("p.nama");
    }

    function tabguru_reloadDaftarGuru(urut)
    {
        var qsb = new QsBuilder();
        qsb.add("op", "daftar");
        qsb.add("departemen", $("#tabguru_departemen_pilih").val());
        qsb.add("idpelajaran", $("#tabguru_pelajaran_pilih").val());
        qsb.add("urut", urut);

        tabguru_fetchDaftarGuru(qsb.createQs());
    }

    function tabguru_fetchDaftarGuru(qs)
    {
        $("#tabguru_dvDaftar").html("memuat ..");

        var relPath = $("#tab_relPath").val();
        $.ajax({
            url: relPath + "tabs.guru.ajax.php",
            method: "POST",
            data: qs,
            success: function (result)
            {
                $("#tabguru_dvDaftar").html(result).hide().fadeIn(400);

                if ($("#tabguru_table_pilih").length)
                    Tables("tabguru_table_pilih", 1, 0);
            },
            error: function (xhr)
            {
                alert(xhr.responseText);
            }
        })
    }

    function tabguru_cari(e)
    {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode !== 13)
            return;

        var search = $.trim($("#tabguru_search").val());
        if (search.length < 3)
        {
            $("#tabguru_dvCari").html("<br><br>Panjang kata kunci minimal 3 karakter");
            return;
        }

        tabguru_reloadCariGuru(search, "p.nama");
    }

    function tabguru_reloadCariGuru(search, urut)
    {
        var qsb = new QsBuilder();
        qsb.add("op", "cari");
        qsb.add("search", search);
        qsb.add("urut", urut);
        qsb.addInput("searchby", "tabguru_searchby");
        qsb.addInput("departemen", "tabguru_departemen_cari");

        tabguru_fetchCariGuru(qsb.createQs());
    }

    function tabguru_fetchCariGuru(qs)
    {
        $("#tabguru_dvCari").html("memuat ..");

        var relPath = $("#tab_relPath").val();
        $.ajax({
            url: relPath + "tabs.guru.ajax.php",
            method: "POST",
            data: qs,
            success: function (result)
            {
                $("#tabguru_dvCari").html(result).hide().fadeIn(300);

                if ($("#tabguru_table_cari").length)
                    Tables("tabguru_table_cari", 1, 0);
            },
            error: function (xhr)
            {
                alert(xhr.responseText);
            }
        })
    }

    function tabguru_changeUrut(sumber, urut)
    {
        if (sumber === "daftar")
        {
            tabguru_reloadDaftarGuru(urut);
        }
        else
        {
            var search = $.trim($("#tabguru_search").val());
            tabguru_reloadCariGuru(search, urut);
        }
    }
</script>

<input type="hidden" id="tab_relPath" value="<?=$tab_relPath?>">
<div id="tabGuru">
    <ul>
        <li><a href="#tabs-1">Pilih Guru</a></li>
        <li><a href="#tabs-2">Cari Guru</a></li>
    </ul>
    <div id="tabs-1" style="padding: 2px">

        <table border="0" cellpadding="0" width="100%">
        <tr>
            <td width="15%">Departemen</td>
            <td width="*">
<?php           ShowSelectDepartemen("tabguru_departemen_pilih", "tabguru_onDepartemenPilihChange()"); ?>                    
            </td>
        </tr>
        <tr>
            <td>Pelajaran</td>
            <td width="*">
<?php           ShowSelectPelajaran(); ?>                    
            </td>
        </tr>
        <tr>
            <td colspan="2">

                <div id="tabguru_dvDaftar" <?= $divStyle ?>>
<?php               $urut = "p.nama";
                    ShowDaftarGuru(); ?>
                </div>

            </td>
        </tr>
        </table>

    </div>
    <div id="tabs-2" style="padding: 2px">

        <table border="0" cellpadding="0" width="100%">
        <tr>
            <td width="15%">Departemen</td>
            <td width="*">
<?php           ShowSelectDepartemen("tabguru_departemen_cari", "tabguru_onDepartemenCariChange()"); ?>                    
            </td>
        </tr>
        <tr>
            <td>Berdasarkan</td>
            <td>
                <select id="tabguru_searchby" class="inputbox" style="width: 100px">
                    <option value="p.nama">Nama</option>
                    <option value="p.nip">NIP</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Pencarian</td>
            <td>
                <input type="text" class="inputbox"
                       style="width: 170px;"
                       id="tabguru_search"
                       onkeyup="return tabguru_cari(event)">
            </td>
        </tr>
        <tr>
            <td colspan="2">

                <div id="tabguru_dvCari" <?= $divStyle ?>>

                </div>

            </td>
        </tr>
        </table>

    </div>
</div>