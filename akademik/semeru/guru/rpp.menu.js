function onChangeDept()
{
    function acceptSemester()
    {
        fetchTingkat(acceptTingkat);
    }

    function acceptTingkat()
    {
        fetchPelajaran();
    }

    fetchSemester(acceptSemester);
}

function fetchPelajaran()
{
    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rpp.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spPelajaran.html(data).hide().fadeIn(500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchTingkat(callback)
{
    let spTingkat = $("#spTingkat");
    spTingkat.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tingkat");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rpp.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTingkat.html(data).hide().fadeIn(500);

            callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchSemester(callback)
{
    let spSemester = $("#spSemester");
    spSemester.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "semester");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rpp.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spSemester.html(data).hide().fadeIn(500);

            callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangePelajaran()
{
    showBlank();
}

function onChangeSemester()
{
    showBlank();
}

function onChangeTingkat()
{
    showBlank();
}

function showBlank()
{
    parent.content.location.href = "blank.php";
}

function showRpp()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("semester", "Semester") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("pelajaran", "Pelajaran");  

    if (!isValid)                  
        return;

    let qsb = new QsBuilder();
    qsb.add("page", 1);
    qsb.add("status", "1");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "semester");
    qsb.add("semester", $("#semester option:selected").text());
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());

    parent.content.location.href = "rpp.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/gp_rpp.html?r=' + Math.random(), 'RPPHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}