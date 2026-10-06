var blankMessage = "<br><span style='font-size: 14px; color: #999; font-weight: bold;'>Klik tombol \"Lihat Statistik\" di panel kiri untuk menampilkan Laporan Statistik Pembayaran</span>";

$(document).ready(function ()
{
    $("#dvContent").html(blankMessage);
});

clearContent = function ()
{
    $("#dvContent").html(blankMessage);
};

changeLaporan = function ()
{
    var laporan = parseInt($("#laporan").val());
    if (laporan === 0)
    {
        $("#trHarian").css("display", "table-row");
        $("#trBulanan").css("display", "none");
    }
    else
    {
        $("#trHarian").css("display", "none");
        $("#trBulanan").css("display", "table-row");
    }
};

showDatePicker1 = function ()
{
    var selDate = $("#dttanggal1").val();
    $("#tanggal1").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal1").val(date);
            $("#tanggal1").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

showDatePicker2 = function ()
{
    var selDate = $("#dttanggal2").val();
    $("#tanggal2").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal2").val(date);
            $("#tanggal2").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

cetakHarian = function()
{
    var departemen = encodeURIComponent($("#departemen").val());
    var addr = "statistik.harian.cetak.php?departemen=" + departemen;
    newWindow(addr, 'StatistikHarianCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelHarian = function()
{
    var qs = "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
    qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());
    qs += "&metode=" + $("#metode").val();
    qs += "&bankno=" + encodeURIComponent($("#bank").val());
    qs += "&bankname=" + encodeURIComponent($("#bank option:selected").text());
    qs += "&idpetugas=" + encodeURIComponent($("#idpetugas").val());

    var addr = "statistik.harian.excel.php?" + qs;
    newWindow(addr, 'StatistikHarianExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetakBulanan = function()
{
    var departemen = encodeURIComponent($("#departemen").val());
    var addr = "statistik.bulanan.cetak.php?departemen=" + departemen;
    newWindow(addr, 'StatistikBulanannCetak', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

excelBulanan = function()
{
    var qs = "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&bulan1=" + $("#bulan1").val();
    qs += "&tahun1=" + $("#tahun1").val();
    qs += "&bulan2=" + $("#bulan2").val();
    qs += "&tahun2=" + $("#tahun2").val();
    qs += "&metode=" + $("#metode").val();
    qs += "&bankno=" + encodeURIComponent($("#bank").val());
    qs += "&bankname=" + encodeURIComponent($("#bank option:selected").text());
    qs += "&idpetugas=" + encodeURIComponent($("#idpetugas").val());

    var addr = "statistik.bulanan.excel.php?" + qs;
    newWindow(addr, 'StatistikBulananExcel', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

getStatistikContent = function ()
{
    if ($("#dvStatistik").length)
        return $("#dvStatistik").html();
    return "-";
};

getDepartemenText = function ()
{
    return $("#departemen option:selected").text();
};

getTanggal = function ()
{
    return $("#tanggal1").val() + " s/d " + $("#tanggal2").val();
};

getBulan = function ()
{
    return $("#bulan1 option:selected").text() + " " + $("#tahun1").val() + " s/d " +
           $("#bulan2 option:selected").text() + " " + $("#tahun2").val();
};

getBank = function ()
{
    return $("#bank option:selected").text();
};

getPetugas = function ()
{
    return $("#idpetugas option:selected").text();
};

getMetodeText = function ()
{
    return $("#metode option:selected").text();
};

showStatistik = function ()
{
    let bankNo = $("#bank").val();
    if (bankNo === "-1")
        return;

    var laporan = parseInt($("#laporan").val());
    var qs = "op=4898482934839204";
    qs += "&laporan=" + laporan;
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    if (laporan === 0)
    {
        qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
        qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());
    }
    else
    {
        qs += "&bulan1=" + $("#bulan1").val();
        qs += "&tahun1=" + $("#tahun1").val();
        qs += "&bulan2=" + $("#bulan2").val();
        qs += "&tahun2=" + $("#tahun2").val();
    }
    qs += "&metode=" + $("#metode").val();
    qs += "&bankno=" + encodeURIComponent($("#bank").val());
    qs += "&bankname=" + encodeURIComponent($("#bank option:selected").text());
    qs += "&idpetugas=" + encodeURIComponent($("#idpetugas").val());

    var dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    $.ajax({
        url: "statistik.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvContent.html(result).hide().fadeIn(500);

            if ($("#tabStatistik").length)
                Tables('tabStatistik', 1, 0);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

function changeDept()
{
    var qs = "op=3745745374564";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());

    var dvBank = $("#dvBank");
    dvBank.html("memuat ..");

    $.ajax({
        url: "statistik.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvBank.html(result);
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
}

showRincianHarian = function (tanggal, departemen, bankno, bankname, idPetugas, metode)
{
    var qs = "tanggal=" + encodeURIComponent(tanggal);
    qs += "&departemen=" + encodeURIComponent(departemen);
    qs += "&bankno=" + encodeURIComponent(bankno);
    qs += "&bankname=" + encodeURIComponent(bankname);
    qs += "&idpetugas=" + encodeURIComponent(idPetugas);
    qs += "&metode=" + encodeURIComponent(metode);
    qs += "&page=1";

    var addr = "statistik.rincian.harian.php?" + qs;
    newWindow(addr, 'StatistikRincianHarian', '900', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showRincianBulanan = function (bulan, tahun, departemen, bankno, bankname, idPetugas, metode)
{
    var qs = "bulan=" + encodeURIComponent(bulan);
    qs += "&tahun=" + encodeURIComponent(tahun);
    qs += "&departemen=" + encodeURIComponent(departemen);
    qs += "&bankno=" + encodeURIComponent(bankno);
    qs += "&bankname=" + encodeURIComponent(bankname);
    qs += "&idpetugas=" + encodeURIComponent(idPetugas);
    qs += "&metode=" + encodeURIComponent(metode);
    qs += "&page=1";

    var addr = "statistik.rincian.bulanan.php?" + qs;
    newWindow(addr, 'StatistikRincianBulanan', '900', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

function showStatistikHelp()
{
     $.ajax({    
        url: "../help/op_statistik.html?r=" + Math.random(),
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