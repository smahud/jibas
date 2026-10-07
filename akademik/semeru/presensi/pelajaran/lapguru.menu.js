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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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

function showLaporanPresensiPelajaranGuru()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.HasOption("semester", "Semester") &&
                  Vldr.HasOption("pelajaran", "Pelajaran") &&
                  Vldr.IsHaveInput("nipguru", "NIP Guru");

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
    qsb.addInput("nipguru", "nipguru");
    qsb.addInput("namaguru", "namaguru");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    parent.content.location.href = "lapguru.content.php?" + qsb.createQs();
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
        url: "lapguru.menu.ajax.php",
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
        url: "lapguru.menu.ajax.php",
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

function showSearchGuru()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "pelajaran");

    newWindow("../../library/daftarguru.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptGuru(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nipguru").val(data.NIP);
    $("#namaguru").val(data.Nama);
}

function showHelp()
{
    newWindow('../../help/pp_lapguru.html?r=' + Math.random(), 'LaporanPresensiPelajaranGuru','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
