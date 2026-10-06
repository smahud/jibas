function cetakInfoSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "dashboard.info.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakLaporanInfoSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContentInfoSiswa(section)
{
    if (section === "content")
    {
        if ($("#dvInfoSiswa").length)
            return $("#dvInfoSiswa").html();

        return "-";
    }
}