$(document).ready(function() 
{

})

function onChangeDept()
{
    parent.content.location.href = "blank.php";

    $("#tingkat").empty();
    $("#kelas").empty()

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
    }

    function acceptTingkat()
    {
        fetchKelas();
    }

    fetchTahunAjaran(acceptTahunAjaran);
}

function onChangeTingkat()
{
    parent.content.location.href = "blank.php";
    
    $("#kelas").empty()

    fetchKelas();
}

function onChangeKelas()
{
    parent.content.location.href = "blank.php";
}

function fetchKelas()
{
    if ($("#departemen option").length == 0)
        return;

    if ($("#tingkat option").length == 0)
        return;

    $("#spKelas").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idtingkat", "tingkat");

    $.ajax({
        url: "statistik.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spKelas").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchTingkat(callback)
{
    if ($("#departemen option").length == 0)
        return;

    $("#spTingkat").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tingkat");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");

    $.ajax({
        url: "statistik.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spTingkat").html(data).hide().fadeIn(300);

            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchTahunAjaran(callback)
{
    if ($("#departemen option").length == 0)
        return;

    $("#spTahunAjaran").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tahunajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "statistik.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spTahunAjaran").html(data).hide().fadeIn(300);

            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showStatistikSiswa()
{
    let isValid = Vldr.HasOption("departemen") && 
                  Vldr.HasOption("tingkat") && 
                  Vldr.HasOption("kelas");

    if (!isValid)
        return;

    let idTahunAjaran = parseInt($("#idtahunajaran").val());
    if (idTahunAjaran == 0)
    {
        alert("Belum ada data tahun ajaran");
        return;
    }

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text())
    qsb.addInput("idkelas", "kelas");
    qsb.add("kelas", $("#kelas option:selected").text());
    qsb.addInput("jenisstatistik", "jenisstatistik");
    qsb.add("jenisstatistiktext", $("#jenisstatistik option:selected").text());

    parent.content.location.href = "statistik.content.php?" + qsb.createQs();
}

function onChangeJenisStatistik()
{
    parent.content.location.href = "blank.php";
}

function onChangeCari()
{
    parent.content.location.href = "blank.php";
}

function showHelp()
{
    newWindow('../help/sis_statistik.html?r=' + Math.random(), 'StatistikSiswaHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
