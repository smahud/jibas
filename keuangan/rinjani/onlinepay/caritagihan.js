$(document).ready(function () {
    if ($("#tabTagihanInfo").length)
        Tables("tabTagihanInfo", 0, 0);
});

changeSearch = function ()
{
    var search = $("#search").val();

    var qs = "op=983479824798324723";
    qs += "&search=" + search;

    $("#dvSelection").html("");
    $("#dvTagihanInfo").html("");
    $("#dvTagihanData").html("");

    $.ajax({
        url: "caritagihan.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvSelection").html(result);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

SearchUser = function()
{
    /*
    var addr = "carisiswa.php?selectdept=1";
    newWindow(addr, 'CariSiswa', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
    */

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "deptsiswa");

    newWindow("../library/daftarsiswa.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

SearchTagihan = function ()
{
    var nomor = $.trim($("#nomor").val());
    if (nomor.length === 0)
        return;

    if (nomor.length < 5)
    {
        $("#statusCari").html("nomor tagihan minimal 5 digit");
        return;
    }

    var departemen = $("#deptnomor").val();
    var qs = "op=874368723462837468723";
    qs += "&nomor=" + nomor;
    qs += "&departemen=" + departemen;

    $.ajax({
        url: "caritagihan.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvTagihanInfo").html(result);

            if ($("#tabTagihanInfo").length)
                Tables("tabTagihanInfo", 0, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    });
};

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#kelompok").val(kelompok);
    $("#noid").val(data.NIS);
    $("#nama").val(data.Nama);
    $("#kelas").val(data.Kelas);

    var date = new Date();
    var qs = "op=9749873249832784";
    qs += "&nis=" + encodeURIComponent(data.NIS);
    qs += "&nama=" + encodeURIComponent(data.Nama);
    qs += "&bulan=" + (date.getMonth() + 1);
    qs += "&tahun=" + date.getFullYear();

    $("#dvTagihanData").html("");

    $.ajax({
        url: "caritagihan.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvTagihanInfo").html(result).hide().fadeIn(500);

            if ($("#tabTagihanInfo").length)
                Tables("tabTagihanInfo", 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}


AcceptSearch = function(data, noid, nama, kelas)
{
    $("#kelompok").val(data);
    $("#noid").val(noid);
    $("#nama").val(nama);
    $("#kelas").val(kelas);

    var date = new Date();
    var qs = "op=9749873249832784";
    qs += "&nis=" + encodeURIComponent(noid);
    qs += "&nama=" + encodeURIComponent(nama);
    qs += "&bulan=" + (date.getMonth() + 1);
    qs += "&tahun=" + date.getFullYear();

    $("#dvTagihanData").html("");

    $.ajax({
        url: "caritagihan.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            $("#dvTagihanInfo").html(result);

            if ($("#tabTagihanInfo").length)
                Tables("tabTagihanInfo", 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
};

changeTagihanSiswaSel = function ()
{
    var nis = $("#noid").val();
    var nama = $("#nama").val();
    var bulan = $("#bulan").val();
    var tahun = $("#tahun").val();

    var data = "op=785462837462834";
    data += "&nis=" + encodeURIComponent(nis);
    data += "&nama=" + encodeURIComponent(nama);
    data += "&bulan=" + bulan;
    data += "&tahun=" + tahun;

    $("#dvTagihanData").html("");

    $.ajax({
        url: "caritagihan.ajax.php",
        method: "POST",
        data: data,
        success: function (result)
        {
            $("#dvTagihanSiswa").html(result);

            if ($("#tabTagihanInfo").length)
                Tables("tabTagihanInfo", 0, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
};

function showCariTagihanHelp()
{
     $.ajax({    
        url: "../help/op_caritagihan.html?r=" + Math.random(),
        success: function (content)
        {
            helpBox.show(content);  
                
            setTimeout(function () {
                $("#divHelpDialog").scrollTop(0);
            }, 750)
        },
        error: function(xhr)
        {           
            alert(xhr.responseText);
        }          
    })  
}