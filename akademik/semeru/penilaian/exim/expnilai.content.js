function cetakExcel()
{
    var filename = document.getElementById("filename").value + "";
    if (filename.trim().length == 0)
    {
        alert("Nama file Excel belum ditentukan!")
        document.getElementById("filename").focus();
        return;
    }

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.add("filename", filename);

    document.location.href = "expnilai.content.excel.php?" + qsb.createQs();
}