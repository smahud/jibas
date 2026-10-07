function onChangeDept()
{
    clearContent();

    fetchSemester();

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
    
    function acceptKelas()
    {
        fetchPelajaran();        
    }

    function acceptTingkat()
    {
        fetchKelas(acceptKelas);
    }

    setTimeout(function() {
        fetchTingkat(acceptTingkat);
    }, 70)
}

function onChangePelajaran()
{
    clearContent();
}

function fetchPelajaran()
{
    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("idkelas", "kelas");
    qsb.addInput("idsemester", "semester");

    $.ajax({
        url: "legger.rapor.kelas.menu.ajax.php",
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

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function onChangeTahunAjaran()
{
    function acceptKelas()
    {
        fetchPelajaran();
    }

    function acceptTingkat()
    {
        fetchKelas(acceptKelas);
    }

    fetchTingkat(acceptTingkat);
}

function onChangeTingkat()
{
    clearContent();

    function acceptKelas()
    {
        fetchPelajaran();
    }

    fetchKelas(acceptKelas);
}

function onChangeKategori()
{
    clearContent();
}

function onChangeKelas()
{
    clearContent();

    fetchPelajaran();
}

function onChangeSemester()
{
    clearContent();
    
    fetchPelajaran();
}

function onChangeBulanTahun()
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
        url: "legger.rapor.kelas.menu.ajax.php",
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
        url: "legger.rapor.kelas.menu.ajax.php",
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

function fetchKelas(callback)
{
    if ($("#tingkat option").length === 0)
        return;

    let spKelas = $("#spKelas");
    spKelas.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");

    $.ajax({
        url: "legger.rapor.kelas.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKelas.html(data).hide().fadeIn(300);

            if (callback !== undefined) 
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
        url: "legger.rapor.kelas.menu.ajax.php",
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

function showLaporanLeggerNilaiRapor()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.HasOption("semester", "Semester") &&
                  Vldr.HasOption("pelajaran", "Pelajaran");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkelas", "kelas");
    qsb.add("kelas", $("#kelas option:selected").text());
    qsb.addInput("idsemester", "semester");
    qsb.add("semester", $("#semester option:selected").text());
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    
    parent.content.location.href = "legger.rapor.kelas.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_leggerraporkelas.html?r=' + Math.random(), 'LeggerRaporKelasHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}