function showInfoSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InfoSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
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
    else if (section === "barchart")
    {
        if ($("#dvBarChart").length)
            return $("#dvBarChart").html();

        return "-";
    }
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