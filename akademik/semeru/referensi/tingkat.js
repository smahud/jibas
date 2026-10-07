var helpBox = null;

$(document).ready(function ()
{
    if ($("#table").length)
        Tables("table", 1, 0);

    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});

function showHelp()
{
    $.ajax({
        url: "../help/rf_tingkat.html",
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


function onChangeDepartemen()
{
    refresh();
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    document.location.href = "tingkat.php?" + qsb.createQs();
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "NON AKTIF KAN TINGKAT INI?";
    else
        msg = "Aktifkan kembali tingkat ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);

    $.ajax({
        url: "tingkat.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            refresh();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    }); 
}

function edit(replid)
{
    newWindow('tingkat.dialog.php?replid=' + replid, 'UbahTingkat','500','279','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("HAPUS TINGKAT INI?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "tingkat.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorBottom(ls[1]);
                return;
            }

            refresh();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function tambah()
{
    let departemen = $("#departemen").val();
    newWindow('tingkat.dialog.php?departemen=' + encodeURIComponent(departemen), 'TambahTingkat','500','320','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "tingkat.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakTingkat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();
        return "-";
    }
}
