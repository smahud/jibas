$(document).ready(function () {
    applyTables();
});

function applyTables()
{
    let nJenisUjian = parseInt($("#njenisujian").val());
    for(let i = 0; i < nJenisUjian; i++)
    {
        let tableId = "tableUjian" + i;
        if ($("#" + tableId).length)
            Tables(tableId, 1, 0);
    }
}

function refresh() 
{
    document.location.reload();    
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.add("aspek", $("#aspek option:selected").text());
    
    newWindow("nilai.siswa.laporan.cetak.php?" + qsb.createQs(), "CetakLaporanRiwayatNilaiSiswa", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvContent").length)
            return $("#dvContent").html();

        return "-";
    }
}

function onChangeAspek()
{
    let qsb = new QsBuilder();
    qsb.add("op", "laporan");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("nis", "nis");
    qsb.addInput("kodeaspek", "aspek");

    let dvContent = $("#dvContent");
    dvContent.html("memuat ..");
    $("#dvLoading").show();

    $.ajax({
        url: "nilai.siswa.laporan.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvContent.html(data).hide().fadeIn(300);

            applyTables();
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function profilSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
