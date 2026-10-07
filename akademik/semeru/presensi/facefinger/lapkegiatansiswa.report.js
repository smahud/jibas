$(document).ready(function()
{
    if ($("#table").length)
        Tables('table', 1, 0);
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


function showRincian(idkegiatan, kegiatan, nis, nama)
{
    let qsb = new QsBuilder();
    qsb.add("idkegiatan", idkegiatan);
    qsb.add("kegiatan", kegiatan);
    qsb.add("nis", nis);
    qsb.add("nama", nama);
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");

    newWindow('lapkegiatansiswa.rincian.php?'+qsb.createQs(), 'RincianKegiatanSiswa','780','620','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    
    newWindow("lapkegiatansiswa.report.cetak.php?" + qsb.createQs(), "CetakLaporanKegiatanSiswa", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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