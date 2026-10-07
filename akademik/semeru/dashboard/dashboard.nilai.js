$(document).ready(function() 
{
    applyTablesNilai();
})

function applyTablesNilai()
{
    if ($("#tablenilai").length)
        Tables('tablenilai', 1, 0);
}

function onChangeJumlahNilai()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporannilai");
    qsb.addInput("jumlahnilai", "jumlahnilai");
    qsb.addInput("nis", "nis");

    $("#dvLoading").show();
    $("#dvLaporanNilai").html("memuat ..");

    $.ajax({
        url: "dashboard.nilai.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (data) 
        {
            $("#dvLaporanNilai").html(data).hide().fadeIn(300);

            applyTablesNilai();
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

function cetakNilai()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
 
    let addr = "dashboard.nilai.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentNilai(section)
{
    if (section === "content")
    {
        if ($("#dvLaporanNilai").length)
            return $("#dvLaporanNilai").html();

        return "-";
    }
}

function refreshNilai()
{
    onChangeJumlahNilai();
}