function onChangeDept()
{
    parent.content.location.href = "blank.php";

    function acceptTahunAjaran()
    {
        fetchKalender();
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
        url: "kalendersusun.menu.ajax.php",
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

function fetchKalender()
{
    let spKalender = $("#spKalender");
    spKalender.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "kalender");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $.ajax({
        url: "kalendersusun.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKalender.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeTahunAjaran()
{
    parent.content.location.href = "blank.php";

    fetchKalender();
}

function onChangeKalender()
{
    parent.content.location.href = "blank.php";
}

function showManageKalender()
{
    parent.location.href = "kalender.php?showback=1";
}

function refreshKalender()
{
    fetchKalender();
}

function showKegiatanKalender()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") &&
                  Vldr.HasOption("kalender", "Kalender");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idkalender", "kalender");
    qsb.add("kalender", $("#kalender option:selected").text());

    parent.content.location.href = "kalendersusun.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/jk_susunkalender.html?r=' + Math.random(), 'JadwalSusunKalenderHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}