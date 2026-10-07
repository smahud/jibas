function onChangeDept()
{
    clearContent();

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
    
    function acceptTingkat()
    {
        fetchKelas();
    }

    setTimeout(function() {
        fetchTingkat(acceptTingkat);
    }, 20)

    setTimeout(function() {
        fetchSemester();
    }, 70)
}

function fetchKelas()
{
    if ($("#tingkat option").length === 0)
        return;

    let spKelas = $("#spKelas");
    spKelas.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idtingkat", "tingkat");

    $.ajax({
        url: "expnilai.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKelas.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function onChangeTahunAjaran()
{
    function acceptTingkat()
    {
        fetchKelas();
    }

    fetchTingkat(acceptTingkat);
}

function onChangeTingkat()
{
    fetchKelas();

    clearContent();
}

function onChangeKelas()
{
    clearContent();
}

function onChangeSemester()
{
    clearContent();
}

function fetchSemester()
{
    let spSemester = $("#spSemester");
    spSemester.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "semester");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "expnilai.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spSemester.html(data).hide().fadeIn(300);
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
        url: "expnilai.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTingkat.html(data).hide().fadeIn(300);

            callback();
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchTahunAjaran(callback)
{
    let spTahunAjaran = $("#spTahunAjaran");
    spTahunAjaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tahunajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "expnilai.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTahunAjaran.html(data).hide().fadeIn(300);

            callback();
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function showExportNilai()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.IsNotZero("idtahunajaran", "Tahun Ajaran") &&
                  Vldr.IsNotZero("idsemester", "Semester") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkelas", "kelas");
    qsb.add("kelas", $("#kelas option:selected").text());
    
    parent.content.location.href = "expnilai.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_formexim.html?r=' + Math.random(), 'ExportNilaiHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}