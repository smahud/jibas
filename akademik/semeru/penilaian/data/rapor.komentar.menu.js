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
    }, 70);
}

function fetchPelajaran()
{
    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rapor.komentar.menu.ajax.php",
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

function onChangePelajaran()
{
    clearContent();
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

function fetchSemester()
{
    let spSemester = $("#spSemester");
    spSemester.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "semester");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rapor.komentar.menu.ajax.php",
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
        url: "rapor.komentar.menu.ajax.php",
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
        url: "rapor.komentar.menu.ajax.php",
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
        url: "rapor.komentar.menu.ajax.php",
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

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function showInputKomentar(bagian)
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.HasOption("pelajaran", "Pelajaran");

    if (!isValid)
        return;

    let idTahunAjaran = parseInt($("#tahunajaran").val());
    let idSemester = parseInt($("#semester").val());

    if (idTahunAjaran == 0)
    {
        alert("Tahun Ajaran belum tersedia");
        return;
    }

    if (idSemester == 0)
    {
        alert("Semester belum tersedia");
        return;
    }

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
    qsb.add("bagian", bagian);
    
    parent.content.location.href = "rapor.komentar.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_komentarrapor.html?r=' + Math.random(), 'KomentarRaporHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}