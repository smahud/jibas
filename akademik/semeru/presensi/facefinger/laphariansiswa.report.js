$(document).ready(function()
{
    if ($("#tabContent").length)
        Tables('tabContent', 1, 0);
})

function refresh()
{
    document.location.reload();
}

function showInfoSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("nis", "nis");

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function showDashboardSiswa(idSiswa)
{
    let qsb = new QsBuilder();
    qsb.add('replid', idSiswa);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + idSiswa, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');

    //document.location.href = "../../dashboard/dashboard.php?" + qsb.createQs();
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