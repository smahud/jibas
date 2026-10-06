function cetakInfoCalonSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "dashboardcs.info.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanInfoCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentInfoCalonSiswa(section)
{
    if (section === "content")
    {
        if ($("#dvInfoCalonSiswa").length)
            return $("#dvInfoCalonSiswa").html();

        return "-";
    }
}