function onChangeDept()
{
    parent.content.location.href = "blank.php";

    function acceptTahunAjaran()
    {
        fetchTingkat(acceptTingkat);
        fetchKategori();
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
    
    function acceptTingkat()
    {
        fetchKelas();
    }

    fetchTingkat(acceptTingkat);
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

function onChangeTahunAjaran()
{
    parent.content.location.href = "blank.php";
    
    function acceptTingkat()
    {
        fetchKelas();
    }
    
    fetchTingkat(acceptTingkat);

    fetchKategori();
}

function fetchKategori()
{
    if ($("#tahunajaran option").length === 0)
        return;

    let spKategori = $("#spKategori");
    spKategori.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kategori");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $.ajax({
        url: "jadwalkelas.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKategori.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchKelas()
{
    if ($("#tahunajaran option").length === 0)
        return;

    if ($("#tingkat option").length === 0)
        return;

    let spKelas = $("#spKelas");
    spKelas.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kelas");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "tingkat");

    $.ajax({
        url: "jadwalkelas.menu.ajax.php",
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
        url: "jadwalkelas.menu.ajax.php",
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
        url: "jadwalkelas.menu.ajax.php",
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

function showJadwalKelas()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kategori", "Kategori") &&
                  Vldr.HasOption("kelas", "Kelas");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkategori", "kategori");
    qsb.add("kategori", $("#kategori option:selected").text());
    qsb.addInput("idkelas", "kelas");        
    qsb.add("kelas", $("#kelas option:selected").text());

    parent.content.location.href = "jadwalkelas.content.php?" + qsb.createQs();
}

function showManageKategori()
{
    parent.location.href = "kategorijadwal.php?showback=1";
}

function refreshKategori()
{
    fetchKategori();
}

function showHelp()
{
    newWindow('../help/jk_jadwalkelas.html?r=' + Math.random(), 'JadwalKelasHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}