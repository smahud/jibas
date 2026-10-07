$(document).ready(function () {
    if ($("#tableSiswa").length)
        Tables("tableSiswa", 1, 0);
});

function showLaporanRapor(nis, nama)
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
    qsb.addInput("harian", "harian");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("dd1", "dd1");
    qsb.addInput("mm1", "mm1");
    qsb.addInput("yy1", "yy1");
    qsb.addInput("dd2", "dd2");
    qsb.addInput("mm2", "mm2");
    qsb.addInput("yy2", "yy2");

    parent.daftar.location.href = "rapor.siswa.laporan.php?" + qsb.createQs();
}

function showCetakRaporKelas()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    qsb.addInput("harian", "harian");
    qsb.addInput("pelajaran", "pelajaran");
    
    newWindow("rapor.kelas.laporan.word.php?" + qsb.createQs(), "CetakLaporanRaporKelasWord", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}