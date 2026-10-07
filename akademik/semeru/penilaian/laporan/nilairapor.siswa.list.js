$(document).ready(function () 
{
    applyTables();
});

function applyTables()
{
    if ($("#tablePelajaran").length)
        Tables("tablePelajaran", 1, 0);
}

function clearReport()
{
    parent.daftar.location.href = "blank.php";
}

function fetchKelas()
{
    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("nis", "nis");

    let dvKelas = $("#dvKelas");
    dvKelas.html("memuat ..");

    $("#divPelajaran").html("");

     $.ajax({
        url: "nilairapor.siswa.list.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvKelas.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeTahunAjaran()
{
    clearReport();

    fetchKelas();

    fetchRentangTanggal();
}

function fetchRentangTanggal()
{
    let dvRentangTanggal = $("#dvRentangTanggal");
    dvRentangTanggal.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "rentangtanggal");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $.ajax({
        url: "nilairapor.siswa.list.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvRentangTanggal.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeKelas()
{
    clearReport();
}

function showNilaiRaporSiswa()
{
    let isValid = Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("semester", "Semester") &&
                  Vldr.HasOption("kelas", "Kelas");
    
    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idsemester", "semester");
    qsb.add("semester", $("#semester option:selected").text());
    
    let data64 = $("#kelas").val();
    let lsData = JSON.parse(atob(data64));

    qsb.add("idkelas", lsData[0]);
    qsb.add("kelas", lsData[1]);
    qsb.add("idtingkat", lsData[2]);
    qsb.add("tingkat", lsData[3]);

    qsb.add("harian", $("#harian").is(":checked") ? 1 : 0);
    qsb.add("pelajaran", $("#pelajaran").is(":checked") ? 1 : 0);
    qsb.addInput("dd1", "dd1");
    qsb.addInput("mm1", "mm1");
    qsb.addInput("yy1", "yy1");
    qsb.addInput("dd2", "dd2");
    qsb.addInput("mm2", "mm2");
    qsb.addInput("yy2", "yy2");
    
    parent.daftar.location.href = "rapor.siswa.laporan.php?" + qsb.createQs();
}