$(document).ready(function() 
{
    applyTablesNilaiCs();
})

function applyTablesNilaiCs()
{
    if ($("#tabNilaiCs").length)
        Tables('tabNilaiCs', 0, 0);

    if ($("#tabSumbanganCs").length)
        Tables('tabSumbanganCs', 0, 0);
}

function cetakNilaiSumbangan()
{
    let qsb = new QsBuilder();
    qsb.add("tahunbuku", $("#tahunbuku option").text());
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nic", "nic");
    qsb.addInput("nama", "nama");
 
    let addr = "dashboardcs.nilai.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanNilaiSumbanganCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentNilai(section)
{
    if (section === "content")
    {
        if ($("#dvLaporanNilaiSumbangan").length)
            return $("#dvLaporanNilaiSumbangan").html();

        return "-";
    }
}

function refreshNilaiSumbangan()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refresh");
    qsb.addInput("idcalon", "idcalon");
    qsb.addInput("idproses", "idproses");

    $("#dvLoading").show();
    $("#dvLaporanNilaiSumbangan").html("memuat ..");

    $.ajax({
        url: "dashboardcs.nilai.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            $("#dvLaporanNilaiSumbangan").html(data).hide().fadeIn(300);

            applyTablesNilaiCs();
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