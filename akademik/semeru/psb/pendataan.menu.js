function onChangeDept()
{
    function acceptProses()
    {
        fetchKelompok();
    }

    fetchProses(acceptProses);

    showBlank();
}

function fetchKelompok()
{
    let qsb = new QsBuilder();

    qsb.add("op", "fetchkelompok");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");

    $("#spKelompok").html("memuat ..");

    $.ajax({
        url: "pendataan.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spKelompok").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchProses(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "fetchproses");
    qsb.addInput("departemen", "departemen");

    $("#spProses").html("memuat ..");
    $("#spKelompok").html("");
    
    $.ajax({
        url: "pendataan.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spProses").html(data).hide().fadeIn(300);

            callback();
        },
        error: function(xhr)
        {
            $("#spProses").html("Error ");
            alert(xhr.responseText);
        }
    })
}

function onChangeKelompok()
{
    showBlank();
}

function showBlank()
{
    parent.content.location.href = "blank.php";
}

function showPendataanCalonSiswa()
{
    if ($("#departemen option").length == 0)
        return;

    if ($("#kelompok option").length == 0)
        return;

    let jsonKey = JSON.parse(atob($("#kelompok").val()));
    let kapasitas = parseInt(jsonKey[2]);
    let terisi = parseInt(jsonKey[3]);

    if (terisi >= kapasitas) 
    {
        alert("Kapasitas " + jsonKey[1] + " sudah terisi penuh!");
        return;
    }

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.add("idkelompok", jsonKey[0]);
    qsb.add("kelompok", jsonKey[1]);

    parent.content.location.href = "pendataan.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/psb_pendataan.html?r=' + Math.random(), 'PendataanHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}