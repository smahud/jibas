var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
    applyTables();
});

function applyTables()
{
    if ($("#table").length)
        Tables('table', 1, 0);
}

function showHelp()
{
    $.ajax({
        url: "../help/rf_tahunajaran.html?r=" + Math.random(),
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

function onPrevPage()
{
    let page = parseInt($("#page").val());
    if (page === 1)
        return;

    page -= 1;
    $("#page").val(page);

    onChangePage();
}

function onNextPage()
{
    let page = parseInt($("#page").val());
    let totalPage = parseInt($("#totalpage").val());
    if (page === totalPage)
        return;

    page += 1;
    $("#page").val(page);

    onChangePage();
}

function onChangePage()
{
    let page = parseInt($("#page").val());
    fetchTableTahunAjaran(page);
}

function onChangeDepartemen()
{
    fetchCountData(acceptCountData);
}

function fetchCountData(callback)
{
    $('#dvTableContent').html("");
    $('#dvPageControl').html("");

    let qsb = new QsBuilder();
    qsb.add('op', 'count');
    qsb.addInput('departemen', 'departemen');

    $.ajax({
        url: 'tahunajaran.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            $('#ndata').val(ls[1]);

            if (callback)
                callback();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}


function acceptCountData()
{
    let nData = parseInt($('#ndata').val());
    if (nData == 0)
    {
        let hint = HintInfo.ShowCenter("Belum tersedia data tahun ajaran<br>Silahkan klik ikon tambah untuk menambahkan data tahun ajaran.");
        $("#spCountInfo").html(hint).hide().fadeIn(300);
        return;
    }

    $("#spCountInfo").html("");
    
    fetchTableTahunAjaran(1);

    setTimeout(function() {
        fetchPageControl();
    }, 100);
}

function fetchTableTahunAjaran(page)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("ndata", "ndata");
    qsb.add("page", page);

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "tahunajaran.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response)
        {
            dvTableContent.html(response).hide().fadeIn(400);
            applyTables();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add('op', 'pagecontrol');
    qsb.addInput('ndata', 'ndata');

    let dvPageControl = $('#dvPageControl');
    dvPageControl.html('memuat ..');

    $.ajax({
        url: 'tahunajaran.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (response)
        {
            dvPageControl.html(response).hide().fadeIn(400);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onNewData()
{
    onChangeDepartemen();
}

function onDataChanged()
{
    onChangePage();
}

function refresh()
{
    onChangePage();
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "NON AKTIF KAN TAHUN AJARAN INI?";
    else
        msg = "Aktifkan kembali tahun ajaran ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "tahunajaran.ajax.php",
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

            onChangePage();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    }); 
}

function edit(replid)
{
    newWindow('tahunajaran.dialog.php?replid=' + replid, 'UbahTahunAjaran','500','340','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("HAPUS TAHUN AJARAN INI?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "tahunajaran.ajax.php",
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

            onChangePage();
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
    newWindow('tahunajaran.dialog.php?departemen=' + encodeURIComponent(departemen), 'TambahTahunAjaran','500','340','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "tahunajaran.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakTahunAjaran','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
