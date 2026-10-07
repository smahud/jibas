$(document).ready(function () {
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

function fetchKelas(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("nis", "nis");

    let dvKelas = $("#dvKelas");
    dvKelas.html("memuat ..");

    $("#divPelajaran").html("");

     $.ajax({
        url: "nilai.siswa.list.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvKelas.html(data).hide().fadeIn(300);

            callback();
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchPelajaran()
{
    if ($("#kelas option:selected").length == 0) 
        return;

    let data64 = $("#kelas").val();
    let lsData = JSON.parse(atob(data64));
    let idKelas = lsData[0];

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.add("idkelas", idKelas);
    qsb.add("nis", $("#nis").val());

    let dvPelajaran = $("#dvPelajaran");
    dvPelajaran.html("memuat ..");

    $.ajax({
        url: "nilai.siswa.list.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvPelajaran.html(data).hide().fadeIn(300);

            applyTables();
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

    function acceptKelas()
    {
        fetchPelajaran();        
    }

    fetchKelas(acceptKelas);
}

function onChangeKelas()
{
    fetchPelajaran();
}

function showNilaiSiswa(idPelajaran, pelajaran)
{
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
    qsb.add("idpelajaran", idPelajaran);
    qsb.add("pelajaran", pelajaran);
    
    parent.daftar.location.href = "nilai.siswa.laporan.php?" + qsb.createQs();
}