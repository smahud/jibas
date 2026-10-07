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
        url: "../help/rf_semester.html?r=" + Math.random(),
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

    document.location.href = "semester.php?" + qsb.createQs();
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "Apakah anda yakin ingin menonaktifkan semester ini?";
    else
        msg = "Apakah anda yakin ingin mengaktifkan semester ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "semester.ajax.php",
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
    newWindow('semester.dialog.php?replid=' + replid, 'UbahSemester','500','280','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("Apakah anda yakin ingin menghapus semester ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "semester.ajax.php",
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
    let departemen = $("#departemen").val();
    newWindow('semester.dialog.php?departemen=' + encodeURIComponent(departemen), 'TambahSemester','500','280','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "semester.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakSemester','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
