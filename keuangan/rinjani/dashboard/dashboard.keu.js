$(document).ready(function() 
{
    applyTablesKeu();
})

function applyTablesKeu()
{
    if ($("#tablejtt").length)
        Tables('tablejtt', 1, 0);

    if ($("#tableskr").length)
        Tables('tableskr', 1, 0);
}

function showRiwayatTabungan(idtabungan, namatabungan)
{
    let qsb = new QsBuilder();
    qsb.add("op", "riwayattabungan");
    qsb.add("idtabungan", idtabungan);
    qsb.add("namatabungan", namatabungan);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("idtahunbuku", "tahunbuku");

    $("#dvLoading").show();

    $.ajax({
        url: "dashboard.keu.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            dialogBox.show(data);
        },
        error: function (xhr) 
        {
            alert(xhr.responseText)
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });

    //let addr = "laporansiswa.laporan.riwayat.php?" + qsb.createQs();
    //newWindow(addr, 'RiwayatTransaksiTabungan2','790','630','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function showRiwayatJtt(idpenerimaan, namapenerimaan)
{
    let qsb = new QsBuilder();
    qsb.add("op", "riwayatjtt");
    qsb.add("idpenerimaan", idpenerimaan);
    qsb.add("namapenerimaan", namapenerimaan);
    qsb.addInput("userid", "nis");
    qsb.addInput("username", "nama");
    qsb.addInput("idtahunbuku", "tahunbuku");

    $("#dvLoading").show();

    $.ajax({
        url: "dashboard.keu.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            dialogBox.show(data);

            let sisa = $("#sisapembayaran").val();
            $("#spSisaPembayaran").html(sisa);
        },
        error: function (xhr) 
        {
            alert(xhr.responseText)
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });

    //newWindow('bayarsiswa.riwayat.jtt.php?'+qsb.createQs(), 'RiwayatJttSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function showRiwayatSkr(idpenerimaan, namapenerimaan)
{
    let qsb = new QsBuilder();
    qsb.add("op", "riwayatskr");
    qsb.add("idpenerimaan", idpenerimaan);
    qsb.add("namapenerimaan", namapenerimaan);
    qsb.addInput("userid", "nis");
    qsb.addInput("username", "nama");
    qsb.addInput("idtahunbuku", "tahunbuku");

    $("#dvLoading").show();

    $.ajax({
        url: "dashboard.keu.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            dialogBox.show(data);
        },
        error: function (xhr) 
        {
            alert(xhr.responseText)
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });

    //newWindow('bayarsiswa.riwayat.skr.php?'+qsb.createQs(), 'RiwayatSkrSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function onChangeTahunBuku()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporanpembayaran");
    qsb.addInput("idtahunbuku", "tahunbuku");
    qsb.addInput("nis", "nis");

    $("#dvLoading").show();
    $("#dvLaporanPembayaran").html("memuat ..");

    $.ajax({
        url: "dashboard.keu.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            $("#dvLaporanPembayaran").html(data).hide().fadeIn(300);

            applyTablesKeu();
        },
        error: function (xhr) 
        {
            alert(xhr.responseText)
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function cetakKeu()
{
    let qsb = new QsBuilder();
    qsb.add("tahunbuku", $("#tahunbuku option").text());
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
 
    let addr = "dashboard.keu.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanKeuSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentKeu(section)
{
    if (section === "content")
    {
        if ($("#dvLaporanPembayaran").length)
            return $("#dvLaporanPembayaran").html();

        return "-";
    }
}

function refreshKeu()
{
    onChangeTahunBuku();
}