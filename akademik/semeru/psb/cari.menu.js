$(document).ready(function() 
{
    if ($("#cari").length)
        $("#cari").focus();
})

function onChangeDept()
{
    parent.content.location.href = "blank.php";

    $("#proses").empty();
    $("#kelompok").empty()

    function acceptProses()
    {
        fetchKelompok();
    }

    fetchProses(acceptProses);
}

function onChangeProses()
{
    parent.content.location.href = "blank.php";
    
    $("#kelompok").empty()

    fetchKelompok();
}

function onChangeKelompok()
{
    parent.content.location.href = "blank.php";
}

function fetchProses(callback)
{
    if ($("#departemen option").length == 0)
        return;

    $("#spProses").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "proses");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "cari.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spProses").html(data).hide().fadeIn(300);

            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchKelompok()
{
    if ($("#departemen option").length == 0)
        return;

    if ($("#proses option").length == 0)
        return;

    $("#spKelompok").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kelompok");
    qsb.addInput("idproses", "proses");

    $.ajax({
        url: "cari.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spKelompok").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
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

function showCariCalonSiswa()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("proses", "Proses Penerimaan") &&
                  Vldr.HasOption("kelompok", "Kelompok Calon Siswa");
    
    if (!isValid)
        return;

    let cari = $.trim($("#cari").val());
    if (cari.length == 0)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("jeniscari", "jeniscari");
    qsb.add("jeniscaritext", $("#jeniscari option:selected").text());
    qsb.addInput("cari", "cari");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "proses");
    qsb.add("proses", $("#proses option:selected").text())
    qsb.addInput("idkelompok", "kelompok");
    qsb.add("kelompok", $("#kelompok option:selected").text());
        
    parent.content.location.href = "cari.content.php?" + qsb.createQs();
}

function onChangeCari()
{
    parent.content.location.href = "blank.php";
}

function showHelp()
{
    newWindow('../help/psb_caricalonsiswa.html', 'CariCalonSiswaHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}