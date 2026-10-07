$(document).ready(function() {
    if ($("#cari").length)
        $("#cari").focus();
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
        url: "cari.menu.ajax.php",
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
        url: "cari.menu.ajax.php",
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
        url: "cari.menu.ajax.php",
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

function showPendataanSiswa()
{
    let isValid = Vldr.HasOption("departemen") && 
                  Vldr.HasOption("tingkat") && 
                  Vldr.HasOption("kelas");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text())
    qsb.addInput("idkelas", "kelas");
    qsb.add("kelas", $("#kelas option:selected").text());

    parent.content.location.href = "cari.content.php?" + qsb.createQs();
}

function onChangeJenisCari()
{
    parent.content.location.href = "blank.php";

    let qsb = new QsBuilder();
    qsb.add("op", "fetchcari");
    qsb.addInput("jeniscari", "jeniscari");

    $("#spCari").html("memuat ..");

    $.ajax({
        url: "cari.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spCari").html(data).hide().fadeIn(300);

            if ($("#cari").length)
                $("#cari").focus();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showCariSiswa()
{
    let cari = $.trim($("#cari").val());
    if (cari.length == 0)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("jeniscari", "jeniscari");
    qsb.add("jeniscaritext", $("#jeniscari option:selected").text());
    qsb.addInput("cari", "cari");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text())
    qsb.addInput("idkelas", "kelas");
    qsb.add("kelas", $("#kelas option:selected").text());
        
    parent.content.location.href = "cari.content.php?" + qsb.createQs();
}

function onChangeCari()
{
    parent.content.location.href = "blank.php";
}

function showHelp()
{
    newWindow('../help/sis_carisiswa.html?r=' + Math.random(), 'CariSiswaHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
