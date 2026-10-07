$(document).ready(function() {
    applyTables();
});

function applyTables()
{
    if ($("#table").length)
        Tables('table', 1, 0);
}

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "rekapinput.rekap.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakRiwayat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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