$(document).ready(function () {
    if ($("#tableNilai").length)
        Tables("tableNilai", 1, 0);
});

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
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("jenispengujian", "jenispengujian");
    qsb.addInput("rpp", "rpp");
    qsb.addInput("koderpp", "koderpp");
    
    newWindow("rpp.kelas.laporan.cetak.php?" + qsb.createQs(), "CetakLaporanRerataNilaiRPP", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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