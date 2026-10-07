$(document).ready(function(){
    
    if ($("#table").length)    
        Tables("table", 0, 0);
    
});

function showInfoPegawai(nip)
{
    let qsb = new QsBuilder();
    qsb.add("nip", nip);

    newWindow('../library/infopegawai.dialog.php?'+qsb.createQs(), 'InformasiPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("tahunajaran", "tahunajaran");

    
    newWindow('rekap.content.cetak.php?'+qsb.createQs(), 'CetakRekapJadwal', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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