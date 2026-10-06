$(document).ready(function() 
{
    applyTablesPresensi();
})

function applyTablesPresensi()
{
    if ($("#tablepresensi").length)
        Tables('tablepresensi', 1, 0);
}       

function showDetailPresensiHarian(nis, bulan, tahun)
{
    let qsb = new QsBuilder();
    qsb.add("op", "detailpresensiharian");
    qsb.add("nis", nis);
    qsb.add("tahun", tahun);
    qsb.add("bulan", bulan);

    $.ajax({
        url: "dashboard.presensi.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dialogBox.show(data);
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeLaporanPresensi()
{
    let laporanPresensi = parseInt($("#laporanpresensi").val());

    if (laporanPresensi == 0)
    {
        $("#spBulanTahun").hide();
        fetchLaporanPresensiHarian();
    }
    else if (laporanPresensi == 1)
    {
        $("#spBulanTahun").show();
        fetchLaporanPresensiKegiatan();
    }
    else if (laporanPresensi == 2)
    {
        $("#spBulanTahun").show();
        fetchRekapPresensiHarian();
    }
}

function fetchRekapPresensiHarian()
{
    let qsb = new QsBuilder();
    qsb.add("op", "rekappresensiharian");
    qsb.add("nis", $("#nis").val());
    qsb.add("tahun", $("#tahun").val());
    qsb.add("bulan", $("#bulan").val());

    $("#dvLoading").show();
    $("#dvLaporanPresensi").html("memuat .. ");

    $.ajax({
        url: "dashboard.presensi.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvLaporanPresensi").html(data).hide().fadeIn(300);
            applyTablesPresensi();
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function fetchLaporanPresensiHarian()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporanpresensiharian");
    qsb.add("nis", $("#nis").val());
    
    $("#dvLoading").show();
    $("#dvLaporanPresensi").html("memuat .. ");

    $.ajax({
        url: "dashboard.presensi.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvLaporanPresensi").html(data).hide().fadeIn(300);
            applyTablesPresensi();
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function fetchLaporanPresensiKegiatan()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporanpresensikegiatan");
    qsb.add("nis", $("#nis").val());
    qsb.add("tahun", $("#tahun").val());
    qsb.add("bulan", $("#bulan").val());

    $("#dvLoading").show();
    $("#dvLaporanPresensi").html("memuat .. ");

    $.ajax({
        url: "dashboard.presensi.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvLaporanPresensi").html(data).hide().fadeIn(300);
            applyTablesPresensi();
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function showDetailPresensiKegiatan(nis, bulan, tahun, idKegiatan, kegiatan)
{
    let qsb = new QsBuilder();
    qsb.add("op", "detailpresensikegiatan");
    qsb.add("nis", nis);
    qsb.add("bulan", bulan);
    qsb.add("tahun", tahun);
    qsb.add("idKegiatan", idKegiatan);
    qsb.add("kegiatan", kegiatan);

    $("#dvLoading").show();

    $.ajax({
        url: "dashboard.presensi.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dialogBox.show(data);
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function onChangeBulan()
{
    onChangeLaporanPresensi();
}

function cetakPresensi()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("laporan", "laporanpresensi");
    qsb.add("namalaporan", $("#laporanpresensi option:selected").text());
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
 
    let addr = "dashboard.presensi.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanPresensi','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentPresensi(section)
{
    if (section === "content")
    {
        if ($("#dvLaporanPresensi").length)
            return $("#dvLaporanPresensi").html();

        return "-";
    }
}

function refreshPresensi()
{
    onChangeLaporanPresensi();
}