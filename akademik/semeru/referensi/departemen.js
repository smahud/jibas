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
        url: "../help/rf_departemen.html?r=" + Math.random(),
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

function refresh()
{
    document.location.href = "departemen.php";
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "NON AKTIF KAN DEPARTEMEN INI?";
    else
        msg = "Aktifkan kembali departemen ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);

    $.ajax({
        url: "departemen.ajax.php",
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
    newWindow('departemen.dialog.php?replid=' + replid, 'UbahDepartemen','505','308','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("HAPUS DEPARTEMEN INI?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "departemen.ajax.php",
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
    newWindow('departemen.dialog.php?replid=0', 'TambahDepartemen','505','308','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    newWindow('departemen.cetak.php', 'CetakDepartemen','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
