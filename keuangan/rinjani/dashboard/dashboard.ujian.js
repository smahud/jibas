$(document).ready(function() 
{
    applyTablesUjianCbe();
})

function applyTablesUjianCbe()
{
    if ($("#tableujiancbe").length)
        Tables('tableujiancbe', 1, 0);
}       

function onChangeJumlahDataCbe()
{
    onChangePelajaranCbe();
}

function onChangePelajaranCbe()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporanujiancbe");
    qsb.addInput("nis", "nis");
    qsb.addInput("idpelajaran", "pelajarancbe");
    qsb.addInput("jumlahdata", "jumlahdata");

    $("#dvLoading").show();
    $("#dvLaporanUjianCbe").html("memuat .. ");

    $.ajax({
        url: "dashboard.ujian.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvLaporanUjianCbe").html(data).hide().fadeIn(300);
            applyTablesUjianCbe();
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

function cetakUjianCbe()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.add("namapelajaran", $("#pelajarancbe option:selected").text());
 
    let addr = "dashboard.ujian.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanPresensi','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentUjianCbe(section)
{
    if (section === "content")
    {
        if ($("#dvLaporanUjianCbe").length)
            return $("#dvLaporanUjianCbe").html();

        return "-";
    }
}

function refreshUjianCbe()
{
    onChangePelajaranCbe();
}