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
        url: "daftarpin.menu.ajax.php",
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
        url: "daftarpin.menu.ajax.php",
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
        url: "daftarpin.menu.ajax.php",
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

function showPindahKelas()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") && 
                  Vldr.HasOption("tingkat", "Tingkat") && 
                  Vldr.HasOption("kelas", "Kelas");

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

    parent.content.location.href = "daftarpin.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/sis_daftarpin.html?r=' + Math.random(), 'DaftarPINHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
