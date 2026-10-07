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

    function acceptPelajaran()
    {
        fetchJenisPengujian();
    }

    setTimeout(function() {
        fetchTingkat(acceptTingkat);
    }, 20)

    setTimeout(function() {
        fetchSemester();
    }, 70)

    setTimeout(function() {
        fetchPelajaran(acceptPelajaran);
    }, 100)
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
        url: "rpp.siswa.menu.ajax.php",
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

function onChangePelajaran()
{
    clearContent();

    fetchJenisPengujian();
}

function fetchJenisPengujian()
{
    let spJenisPengujian = $("#spJenisPengujian");
    spJenisPengujian.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "jenispengujian");
    qsb.addInput("idpelajaran", "pelajaran");

    $.ajax({
        url: "rpp.siswa.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spJenisPengujian.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchPelajaran(callback)
{
    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rpp.siswa.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spPelajaran.html(data).hide().fadeIn(300);

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

function onChangeKategori()
{
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
        url: "rpp.siswa.menu.ajax.php",
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
        url: "rpp.siswa.menu.ajax.php",
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
        url: "rpp.siswa.menu.ajax.php",
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

function showLaporanRerataRppSiswa()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.IsNotZero("idtahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("semester", "Semester") &&
                  Vldr.HasOption("pelajaran", "Pelajaran") &&
                  Vldr.HasOption("jenispengujian", "Jenis Pengujian");

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
    qsb.addInput("idsemester", "semester");
    qsb.add("semester", $("#semester option:selected").text());
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    qsb.addInput("idjenispengujian", "jenispengujian");
    qsb.add("jenispengujian", $("#jenispengujian option:selected").text());

    parent.content.location.href = "rpp.siswa.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_reratarppsiswa.html?r=' + Math.random(), 'RPPHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}