$(document).ready(function() {
    if ($("#tableUjian").length)
        Tables('tableUjian', 1, 0);
});

function showHasilUjian(no)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("data", "data" + no);

    parent.daftar.location.href = "nilaipel.laporan.php?" + qsb.createQs();
}