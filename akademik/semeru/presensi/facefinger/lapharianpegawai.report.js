$(document).ready(function()
{
    if ($("#tabContent").length)
        Tables('tabContent', 1, 0);
})

function refresh()
{
    document.location.reload();
}

function showInfoPegawai()
{
    let qsb = new QsBuilder();
    qsb.addInput("nip", "nip");

    newWindow('../../library/infopegawai.dialog.php?'+qsb.createQs(), 'InformasiPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    
    newWindow("laphariansiswa.report.cetak.php?" + qsb.createQs(), "CetakLaporanHarianSiswa", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();

        return "-";
    }
}