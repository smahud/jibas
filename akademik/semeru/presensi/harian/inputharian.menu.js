function onChangeDept()
{
    parent.content.location.href = "blank.php";

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
        fetchBulanTahun();
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
    
    function acceptTingkat()
    {
        fetchKelas();
    }

    fetchTingkat(acceptTingkat);
    fetchSemester();
}

function onChangeTingkat()
{
    parent.content.location.href = "blank.php";

    fetchKelas();
}

function onChangeKategori()
{
    parent.content.location.href = "blank.php";
}

function onChangeKelas()
{
    parent.content.location.href = "blank.php";
}

function onChangeSemester()
{
    parent.content.location.href = "blank.php";
}

function onChangeBulanTahun()
{
    parent.content.location.href = "blank.php";
}

function fetchSemester()
{
    let spSemester = $("#spSemester");
    spSemester.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "semester");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "inputharian.menu.ajax.php",
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

function fetchBulanTahun()
{
    let spBulanTahun = $("#spBulanTahun");
    spBulanTahun.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "bulantahun");
    qsb.addInput("idtahunajaran", "idtahunajaran");

    $.ajax({
        url: "inputharian.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spBulanTahun.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
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
        url: "inputharian.menu.ajax.php",
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

function fetchTingkat(callback)
{
    let spTingkat = $("#spTingkat");
    spTingkat.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tingkat");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "inputharian.menu.ajax.php",
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
        url: "inputharian.menu.ajax.php",
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

function showInputPresensiHarian()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.HasOption("bulan", "Bulan") && 
                  Vldr.HasOption("tahun", "Tahun") &&
                  Vldr.IsNotZero("idtahunajaran", "Tahun Ajaran");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkelas", "kelas");        
    qsb.add("kelas", $("#kelas option:selected").text());
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");

    parent.content.location.href = "inputharian.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/ph_inputharian.html?r=' + Math.random(), 'PresensiHarianHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}