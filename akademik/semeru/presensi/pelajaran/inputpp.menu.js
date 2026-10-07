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

    setTimeout(function () {
        fetchTingkat(acceptTingkat);
    }, 10);

    setTimeout(function () {
        fetchSemester();
    }, 50);

    setTimeout(function () {
        fetchPelajaran();
    }, 100);
}

function fetchPelajaran()
{
    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "inputpp.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spPelajaran.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeTingkat()
{
    clearContent();

    fetchKelas();
}

function onChangeKelas()
{
    clearContent();
}

function onChangeSemester()
{
    clearContent();
}

function onChangeJamMenit()
{
    clearContent();
}

function onChangePelajaran()
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
        url: "inputpp.menu.ajax.php",
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
        url: "inputpp.menu.ajax.php",
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
        url: "inputpp.menu.ajax.php",
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
        url: "inputpp.menu.ajax.php",
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

function showPilihTanggal(tanggal)
{
    let ls = tanggal.split("-");

    let qsb = new QsBuilder();
    qsb.add("tahun", ls[0]);
    qsb.add("bulan", ls[1]);
    qsb.add("pilih", tanggal);

    newWindow("../../library/calendar.dialog.php?" + qsb.createQs(), 'Kalender3','550','400','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptCalendar(tanggal)
{
    var ftanggal = dateutil_formatInaDate(tanggal);
    $("#ftanggal").val(ftanggal);
    $("#tanggal").val(tanggal);

    clearContent();
}

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function showInputPresensiPelajaran()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.HasOption("pelajaran", "Pelajaran") &&
                  Vldr.IsNotZero("idtahunajaran", "Tahun Ajaran") && 
                  Vldr.IsNotZero("idsemester", "Semester");

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
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    qsb.addInput("jam", "jam");
    qsb.addInput("menit", "menit");
    qsb.addInput("tanggal", "tanggal");

    parent.content.location.href = "inputpp.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pp_input.html?r=' + Math.random(), 'PPInputPresensiHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}