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
        url: "rapor.nilai.menu.ajax.php",
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
        url: "rapor.nilai.menu.ajax.php",
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
        url: "rapor.nilai.menu.ajax.php",
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
        url: "rapor.nilai.menu.ajax.php",
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

function showInputNilai()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kelas", "Kelas") &&
                  Vldr.IsHaveInput("nip", "Guru");

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
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    parent.content.location.href = "rapor.nilai.content.php?" + qsb.createQs();
}

function showSearchGuru()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.add("idpelajaran", 0);

    newWindow("../../library/daftarguru.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptGuru(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nip").val(data.NIP);
    $("#nama").val(data.Nama);

    parent.content.location.href = "../penilaian/data/blank.php";
}

function showHelp()
{
    newWindow('../../help/pn_nilairapor.html?r=' + Math.random(), 'NilaiRaporHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}