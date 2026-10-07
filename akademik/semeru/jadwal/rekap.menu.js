function onChangeDept()
{
    parent.content.location.href = "blank.php";

    function acceptTahunAjaran()
    {
        fetchKategori();
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
}

function onChangeKategori()
{
    parent.content.location.href = "blank.php";
}

function onChangeTahunAjaran()
{
    parent.content.location.href = "blank.php";

    fetchKategori();
}

function fetchTahunAjaran(callback)
{
    let spTahunAjaran = $("#spTahunAjaran");
    spTahunAjaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tahunajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rekap.menu.ajax.php",
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
        url: "rekap.menu.ajax.php",
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


function showRekap()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("kategori", "Kategori");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idkategori", "kategori");
    qsb.add("kategori", $("#kategori option:selected").text());

    parent.content.location.href = "rekap.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/jk_rekap.html?r=' + Math.random(), 'RekapJadwalHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}