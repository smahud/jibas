$(document).ready(function ()
{
    initUi();
    tabpegawai_setAcceptResult(acceptPegawai);
});

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    qsb.add("nip", data.NIP);
    qsb.add("nama", data.Nama);

    parent.content.location.href = "lapkegiatanpegawai.report.php?" + qsb.createQs();
}

function initUi()
{
    if ($("#tabPegawai").length)
        $("#tabPegawai").tabs();
}