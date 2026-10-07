$(document).ready(function () {
    if ($("#tableRpp").length)
        Tables("tableRpp", 1, 0);
});

function showRerateRpp(idRpp, kodeRpp, rpp)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenispengujian", "idjenispengujian");
    qsb.addInput("jenispengujian", "jenispengujian");
    qsb.add("idrpp", idRpp);
    qsb.add("koderpp", kodeRpp);
    qsb.add("rpp", rpp);

    parent.daftar.location.href = "rpp.kelas.laporan.php?" + qsb.createQs();
}