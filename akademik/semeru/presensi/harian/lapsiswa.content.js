$(document).ready(function()
{
    if ($("#table").length)
        Tables('table', 1, 0);
});

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
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");
    
    newWindow("lapsiswa.content.cetak.php?" + qsb.createQs(), "CetakLaporan", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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