$(document).ready(function()
{
    if ($("#table").length)
        Tables('table', 1, 0);
})

function showRincian(idkegiatan, kegiatan, nip, nama)
{
    let qsb = new QsBuilder();
    qsb.add("idkegiatan", idkegiatan);
    qsb.add("kegiatan", kegiatan);
    qsb.add("nip", nip);
    qsb.add("nama", nama);
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");

    newWindow('lapkegiatanpegawai.rincian.php?'+qsb.createQs(), 'RincianKegiatanPegawai','780','620','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    
    newWindow("lapkegiatanpegawai.report.cetak.php?" + qsb.createQs(), "CetakLaporanKegiatanPegawai", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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

function showInfoPegawai()
{
    let qsb = new QsBuilder();
    qsb.addInput("nip", "nip");

    newWindow('../../library/infopegawai.dialog.php?'+qsb.createQs(), 'InformasiPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}
