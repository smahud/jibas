function onChangeDept()
{
    clearContent();
    
    fetchKegiatan();
}

function onStatusDataChanged()
{
    clearContent();   
}

function fetchKegiatan()
{
    let qsb = new QsBuilder();
    qsb.add("op", "kegiatan");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("aktif", "aktif");

    let spKegiatan = $("#spKegiatan");
    spKegiatan.html("memuat ..");

    $.ajax({
        url: "lapkegiatan.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKegiatan.html(data).hide().fadeIn(300);
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

function showLaporanKegiatan()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("kegiatan", "Kegiatan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idkegiatan", "kegiatan");
    qsb.add("kegiatan", $("#kegiatan option:selected").text());
    qsb.addInput("statusdata", "statusdata");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    parent.content.location.href = "lapkegiatan.content.php?" + qsb.createQs();
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
        url: "lapkegiatan.menu.ajax.php",
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
        url: "lapkegiatan.menu.ajax.php",
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
    newWindow('../../help/pfw_lapkegiatan.html?r=' + Math.random(), 'LaporanPresensiKegiatan','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}