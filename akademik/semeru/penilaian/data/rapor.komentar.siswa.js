$(document).ready(function () {
    if ($("#tableSiswa").length)
        Tables("tableSiswa", 1, 0);
});

function showInputKomentar(nis, nama)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);
    qsb.add("nama", nama);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");

    let bagian = $("#bagian").val();
    if (bagian == "nilai")
        parent.daftar.location.href = "rapor.komentar.form.php?" + qsb.createQs();
    else if (bagian == "sikap")
        parent.daftar.location.href = "rapor.komentar.sikap.php?" + qsb.createQs();
}

function refresb()
{
    document.location.reload();
}

function showDaftarKomentar()
{
    let bagian = $("#bagian").val();

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    
    if (bagian == "nilai")
        parent.daftar.location.href = "rapor.komentar.form.list.php?" + qsb.createQs();
    else if (bagian == "sikap")
        parent.daftar.location.href = "rapor.komentar.sikap.list.php?" + qsb.createQs();
}
