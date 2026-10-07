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

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    
    newWindow("rapor.siswa.laporan.cetak.php?" + qsb.createQs(), "CetakLaporanRaporSiswa", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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

function cetakWord()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    qsb.addInput("harian", "harian");
    qsb.addInput("pelajaran", "pelajaran");
    
    newWindow("rapor.siswa.laporan.word.php?" + qsb.createQs(), "CetakLaporanRaporSiswaWord", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}