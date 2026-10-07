$(document).ready(function() {
    if ($("#table").length)
        Tables('table', 0, 0);
})

function showRincian(idPresensi)
{
    let qsb = new QsBuilder();
    qsb.add("idpresensi", idPresensi);

    newWindow('lapguru.content.rincian.php?'+qsb.createQs(), 'InfoSiswa','780','700','resizable=1,scrollbars=1,status=0,toolbar=0');
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
    qsb.addInput("guru", "namaguru");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");
    
    newWindow("lapguru.content.cetak.php?" + qsb.createQs(), "CetakLaporanGuru", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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