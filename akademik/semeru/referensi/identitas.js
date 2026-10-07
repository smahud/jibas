var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});

function showHelp()
{
    $.ajax({
        url: "../help/rf_identitas.html?r=" + Math.random(),
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

function tambah()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    
    newWindow("identitas.dialog.php?" + qsb.createQs(), "TambahIdentitas",'720','488','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh()
{
    onDepartemenChange();
}

function ubah()
{
    let qsb = new QsBuilder();
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    
    newWindow("identitas.dialog.php?" + qsb.createQs(), "UbahIdentitas",'720','488','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus()
{
    if (!confirm("HAPUS IDENTITAS SEKOLAH?"))  
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.addInput("replid", "replid");
    
    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "identitas.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let lsResp = JSON.parse(json);
            if (parseInt(lsResp[0]) < 0)
            {
                alert(lsResp[1]);
                return;
            }
            
            onDepartemenChange();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onDepartemenChange()
{
    let qsb = new QsBuilder();
    qsb.add("op", "identitas");
    qsb.addInput("departemen", "departemen");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "identitas.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            dvTableContent.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}