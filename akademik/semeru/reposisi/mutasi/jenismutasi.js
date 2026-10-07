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
    document.location.reload();
}

function edit(replid)
{
    newWindow('jenismutasi.dialog.php?replid=' + replid, 'UbahJenisMutasi','500','279','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("Hapus jenis mutasi ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "jenismutasi.ajax.php",
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

function tambah()
{
    newWindow('jenismutasi.dialog.php?replid=0', 'TambahJenisMutasi','500','279','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let addr = "jenismutasi.cetak.php";
    newWindow(addr, 'CetakJenisMutasi','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
