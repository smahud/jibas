function onChangeDept()
{
    clearContent();

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
        
        setTimeout(function(){
            fetchRentangTanggal();
        }, 50);
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

    setTimeout(function() {
        fetchPelajaran();
    }, 100)
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
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "lapkelas.menu.ajax.php",
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
    function acceptTingkat()
    {
        fetchKelas();

        setTimeout(function(){
            fetchRentangTanggal();
        }, 50);
    }

    fetchTingkat(acceptTingkat);
}

function onChangeTingkat()
{
    clearContent();

    fetchKelas();
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
        url: "lapkelas.menu.ajax.php",
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

function fetchRentangTanggal()
{
    let dvTanggal = $("#dvTanggal");
    dvTanggal.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "rentangtanggal");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $.ajax({
        url: "lapkelas.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTanggal.html(data).hide().fadeIn(300);
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
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");

    $.ajax({
        url: "lapkelas.menu.ajax.php",
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
        url: "lapkelas.menu.ajax.php",
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
        url: "lapkelas.menu.ajax.php",
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

function showLaporanPresensiPelajaranKelas()
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
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkelas", "kelas");        
    qsb.add("kelas", $("#kelas option:selected").text());
    qsb.addInput("idsemester", "semester");
    qsb.add("semester", $("#semester option:selected").text());
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    parent.content.location.href = "lapkelas.content.php?" + qsb.createQs();
}

function onChangeAwal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tanggalawal");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");

    clearContent();

    let spTanggalAwal = $("#spTanggalAwal");
    spTanggalAwal.html("memuat ..");

    $.ajax({
        url: "lapkelas.menu.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spTanggalAwal.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeAkhir()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tanggalakhir");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    clearContent();

    let spTanggalAkhir = $("#spTanggalAkhir");
    spTanggalAkhir.html("memuat ..");

    $.ajax({
        url: "lapkelas.menu.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spTanggalAkhir.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function showHelp()
{
    newWindow('../../help/pp_lapkelas.html?r=' + Math.random(), 'LaporanPresensiPelajaranKelas','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}