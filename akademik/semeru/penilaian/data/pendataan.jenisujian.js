$(document).ready(function() 
{
    applyTables();
});

function applyTables()
{
    let nTable = parseInt($("#nTable").val());
    for(let i = 1; i <= nTable; i++)
    {
        let table = "table" + i;
        if ($("#" + table))
            Tables(table, 1, 0);
    }
}

function showDaftarNilai(idJenisUjian,jenisUjian,idAturanNhb,aspek,namaAspek)
{
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
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    qsb.add("idaturannhb", idAturanNhb);
    qsb.add("idjenisujian", idJenisUjian);
    qsb.add("jenisujian", jenisUjian);
    qsb.add("aspek", aspek);
    qsb.add("namaaspek", namaAspek);

    parent.daftar.location.href = "pendataan.daftar.php?" + qsb.createQs();
}

function clearDaftar()
{
    parent.daftar.location.href = "blank.php";
}

function refresh()
{
    onChangePelajaran(false);
}

function onChangePelajaran(clearContent = true)
{
    let qsb = new QsBuilder();
    qsb.add("op", "jenisujian");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("nip", "nip");
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idsemester", "idsemester");

    let dvJenisUjian = $("#dvJenisUjian");
    dvJenisUjian.html("memuat ..");

    if (clearContent)
        clearDaftar();

    $.ajax({
        url: "pendataan.jenisujian.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvJenisUjian.html(data).hide().fadeIn(300);

            applyTables();
        },
        error: function(xhr, err)
        {
            alert("ERROR : " + xhr.responseText);   
        }
    });
    
}