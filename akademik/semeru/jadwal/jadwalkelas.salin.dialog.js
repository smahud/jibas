$( document ).ready(function() 
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function onChangeTahunAjaran()
{
    function acceptTingkat()
    {
        fetchKelas();
    }
    
    fetchTingkat(acceptTingkat);

    fetchKategori();
}

function fetchTingkat(callback)
{
    let spTingkat = $("#spTingkat");
    spTingkat.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tingkat");
    qsb.addInput("departemen", "departemenref");

    $.ajax({
        url: "jadwalkelas.salin.dialog.ajax.php",
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
    qsb.addInput("idkelasref", "idkelasref");

    $.ajax({
        url: "jadwalkelas.salin.dialog.ajax.php",
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

function fetchKategori()
{
    if ($("#tahunajaran option").length === 0)
        return;

    let spKategori = $("#spKategori");
    spKategori.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kategori");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.addInput("idkategoriref", "idkategoriref");

    $.ajax({
        url: "jadwalkelas.salin.dialog.ajax.php",
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

function onChangeTingkat()
{
    fetchKelas();
}

function simpan()
{
    let isValid = Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kategori", "Kategori") &&
                  Vldr.HasOption("kelas", "Kelas");

    if (!isValid)
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "salin");
    qsb.addInput("departemenref", "departemenref");
    qsb.addInput("idkelasref", "idkelasref");        
    qsb.addInput("kelasref", "kelasref");
    qsb.addInput("idkategoriref", "idkategoriref");        
    qsb.addInput("kategoriref", "kategoriref");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkategori", "kategori");
    qsb.add("kategori", $("#kategori option:selected").text());
    qsb.addInput("idkelas", "kelas");        
    qsb.add("kelas", $("#kelas option:selected").text());

    $.ajax({
        url: "jadwalkelas.salin.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let resp = JSON.parse(json);
            if (parseInt(resp[0]) < 0)
            {
                alert(resp[1]);
                return;
            }

            let nBentrok = parseInt(resp[1]);
            let data64 = resp[2];

            window.close();
            opener.onSalinJadwal(nBentrok, data64);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });

    
}

function lihatJadwal()
{
    let isValid = Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("tingkat", "Tingkat") &&
                  Vldr.HasOption("kategori", "Kategori") &&
                  Vldr.HasOption("kelas", "Kelas");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemenref");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());
    qsb.addInput("idkategori", "kategori");
    qsb.add("kategori", $("#kategori option:selected").text());
    qsb.addInput("idkelas", "kelas");        
    qsb.add("kelas", $("#kelas option:selected").text());

    newWindow('jadwalkelas.salin.lihat.php?'+qsb.createQs(), 'JadwalKelasSalinLihat', '900', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}