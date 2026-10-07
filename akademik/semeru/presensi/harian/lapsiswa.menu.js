function openSearchSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    newWindow("../../library/daftarsiswa.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nis").val(data.NIS);
    $("#nama").val(data.Nama);
    $("#kelompok").val(kelompok);

    clearReport();
}

function clearReport()
{
    parent.content.location.href = "blank.php";
}

function onChangeAwal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tanggalawal");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");

    clearReport();

    let spTanggalAwal = $("#spTanggalAwal");
    spTanggalAwal.html("memuat ..");

    $.ajax({
        url: "lapsiswa.menu.ajax.php",
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

    clearReport();

    let spTanggalAkhir = $("#spTanggalAkhir");
    spTanggalAkhir.html("memuat ..");

    $.ajax({
        url: "lapsiswa.menu.ajax.php",
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

function showLapSiswa()
{
    if ($("#departemen option:selected").val() == 0)
        return;

    if ($("#nis").val() == "")
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "showlapsiswa");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    parent.content.location.href = "lapsiswa.content.php?" + qsb.createQs();
}

function onChangeDept()
{
    clearReport();
    $("#nis").val("");
    $("#nama").val("");

    let dvTanggal = $("#dvTanggal");
    dvTanggal.html("memuat ..");
    
    let qsb = new QsBuilder();
    qsb.add("op", "rentangtanggal");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "lapsiswa.menu.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            dvTanggal.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function showHelp()
{
    newWindow('../../help/ph_lapsiswa.html?r=' + Math.random(), 'LapSiswaHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}