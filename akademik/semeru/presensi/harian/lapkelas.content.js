$(document).ready(function()
{
    if ($("#table").length)
        Tables('table', 1, 0);
});

function showInfoSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InfoSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function showRincian(nis, nama)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);
    qsb.add("nama", nama);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");

    newWindow('lapkelas.content.detail.php?'+qsb.createQs(), 'Rincian','780','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");
    
    newWindow("lapkelas.content.cetak.php?" + qsb.createQs(), "CetakLaporanKelas", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();

        return "-";
    }

    if (section === "barchart")
    {
        if ($("#dvBarChart").length)
            return $("#dvBarChart").html();

        return "-";
    }
}

function refresh()
{
    document.location.reload();
}

function showDashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}