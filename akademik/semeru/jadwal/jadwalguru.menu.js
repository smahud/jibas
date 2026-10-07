function cariPegawai()
{
    newWindow("../library/daftarpegawai.dialog.php", 350, 550, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nip").val(data.NIP);
    $("#nama").val(data.Nama);

    parent.content.location.href = "../jadwal/blank.php";
}

function onChangeDept()
{
    parent.content.location.href = "blank.php";

    function acceptTahunAjaran()
    {
        fetchKategori();
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
}

function fetchTahunAjaran(callback)
{
    let spTahunAjaran = $("#spTahunAjaran");
    spTahunAjaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tahunajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "jadwalguru.menu.ajax.php",
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
        url: "jadwalguru.menu.ajax.php",
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

function showJadwalGuru()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("kategori", "Kategori") &&
                  Vldr.IsNotEmpty("nip", "Guru");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idkategori", "kategori");
    qsb.add("kategori", $("#kategori option:selected").text());
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    
    parent.content.location.href = "jadwalguru.content.php?" + qsb.createQs();
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
    newWindow('../help/jk_jadwalguru.html?r=' + Math.random(), 'JadwalGuruHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}