function clearContent()
{
    parent.content.location.href = "blank.php";
}

function onChangeDept()
{
    clearContent();

    $("#tingkat").empty();

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
    }

    function acceptTingkat()
    {
        
    }

    fetchTahunAjaran(acceptTahunAjaran);
}

function onChangeTingkat()
{
    clearContent();
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
        url: "alumni.menu.ajax.php",
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
        url: "alumni.menu.ajax.php",
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

function showLulusSiswa()
{
    let isValid = Vldr.HasOption("departemen") && 
                  Vldr.HasOption("tingkat");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text())

    parent.content.location.href = "alumni.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/rs_alumni.html?r=' + Math.random(), 'AlumniHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
