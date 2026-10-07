function onChangeDept()
{
    clearContent();
}

function onStatusDataChanged()
{
    clearContent();   
}

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function showLaporanKegiatanSiswa()
{
    let isValid = Vldr.HasOption("departemen", "Departemen");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    parent.content.location.href = "lapkegiatansiswa.content.php?" + qsb.createQs();
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
        url: "lapkegiatansiswa.menu.ajax.php",
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
        url: "lapkegiatansiswa.menu.ajax.php",
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
    newWindow('../../help/pfw_lapkegiatansiswa.html?r=' + Math.random(), 'LaporanPresensiKegiatanSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}