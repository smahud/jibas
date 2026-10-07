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
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("jumlah", "jumlah");
    qsb.addInput("jenis", "jenis");
    qsb.add("nis", data.NIS);
    qsb.add("nama", data.Nama);
    qsb.add("idsiswa", data.Replid);

    parent.daftar.location.href = "nilaisiswa.report.php?" + qsb.createQs();
}

function initUi()
{
    if ($("#tabSiswa").length)
        $("#tabSiswa").tabs();
}