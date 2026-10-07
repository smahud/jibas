$(document).ready(function ()
{
    initUi();
    tabsiswa_setAcceptResult(acceptSiswa);
});

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    qsb.add("nis", data.NIS);
    qsb.add("nama", data.Nama);

    parent.content.location.href = "laphariansiswa.report.php?" + qsb.createQs();
}

function initUi()
{
    if ($("#tabSiswa").length)
        $("#tabSiswa").tabs();
}