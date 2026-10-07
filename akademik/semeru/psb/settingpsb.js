var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox('#divHelpDialog', 600, 500);

    applyTables();
});

function applyTables()
{
    if ($('#table').length)
        Tables('table', 1, 0);
}

function showHelp()
{
    $.ajax({
        url: "../help/psb_settingpsb.html?r=" + Math.random(),
        success: function (content)
        {
            helpBox.show(content);

            setTimeout(function () {
                $("#divHelpDialog").scrollTop(0);
            }, 750)
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })

}

function simpanSettingPsb()
{
    let isValid = Vldr.HasOption("proses", "Proses Penerimaan");

    if (!isValid)
        return;
    
    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add('op', 'simpan');
    qsb.addInput('idprosespsb', 'proses');
    qsb.addInput('departemen', 'departemen');

    for(let i = 1; i <= 2; i++)
    {
        qsb.addInput("kdsum" + i, "kdsum" + i);
        qsb.addInput("nmsum" + i, "nmsum" + i);
    }

    for(let i = 1; i <= 10; i++)
    {
        qsb.addInput("kdujian" + i, "kdujian" + i);
        qsb.addInput("nmujian" + i, "nmujian" + i);
    }

    let btSimpan = $("#btSimpan");
    btSimpan.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: 'settingpsb.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (json)    
        {
            let data = JSON.parse(json);
            if (parseInt(data[0]) < 0)
            {
                alert(data[1]);
                return;
            }

            showToastSuccessTop("Data berhasil disimpan");
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function (xhr)
        {
            btSimpan.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
}

function refresh()
{
    fetchSettingPsb();
}

function onProsesPenerimaanChange()
{
    fetchSettingPsb();
}

function onDepartemenChange()
{
    fetchProsesPsb(acceptProsesPsb);
}

function fetchProsesPsb(callback)
{
    $("#dvTableContent").html("");
    $("#spProsesPsb").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "prosespsb");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: 'settingpsb.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (data)    
        {
           $("#spProsesPsb").html(data).hide().fadeIn(300);

           if (callback)
              callback();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function acceptProsesPsb(data)
{
    fetchSettingPsb();
}

function fetchSettingPsb()
{
    $("#dvTableContent").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "settingpsb");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idprosespsb", "proses");

    $.ajax({
        url: 'settingpsb.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (data)    
        {
           $("#dvTableContent").html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}
