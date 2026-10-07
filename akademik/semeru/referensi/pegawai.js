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
        url: "../help/rf_pegawai.html?r=" + Math.random(),
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
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");
    qsb.addInput("bagian", "bagian");
    qsb.addInput("page", "page");
    qsb.addInput("urut", "urut");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "pegawai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response)
        {
            dvTableContent.html(response);
            dvTableContent.hide().fadeIn(400);

            if ($("#table").length)
                Tables('table', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeBagian()
{
    refresh();
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.addInput("bagian", "bagian");

    document.location.href = "pegawai.php?" + qsb.createQs();
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "NON AKTIF KAN PEGAWAI INI?";
    else
        msg = "Aktifkan kembali pegawai ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);

    $.ajax({
        url: "pegawai.ajax.php",
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

function changePin(replid)
{
    if (!confirm("UBAH PIN PEGAWAI INI?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "changepin");
    qsb.add("replid", replid);

    $.ajax({
        url: "pegawai.ajax.php",
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

            $("#pin-" + replid).text(ls[1]);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function lihat(nip) 
{
    var qsb = new QsBuilder();
    qsb.add("nip", nip);

    newWindow('../library/infopegawai.dialog.php?'+qsb.createQs(), 'InformasiPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function detail(replid) 
{
	newWindow('pegawai.detail.php?replid='+replid, 'CetakDetailPegawai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah()
{
    newWindow('pegawai.dialog.php?replid=0', 'TambahPegawai','500','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function edit(replid)
{
    newWindow('pegawai.dialog.php?replid=' + replid, 'EditPegawai','500','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("Apakah anda yakin ingin menghapus pegawai ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "pegawai.ajax.php",
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
            showToastErrorBottom(xhr.responseText);
        }
    });
}

excel = function ()
{
    var qsb = new QsBuilder();
    qsb.addInput("bagian", "bagian");
    
    var addr = "pegawai.excel.php?" + qsb.createQs();
    newWindow(addr, 'ExcelPegawai', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

cetak = function ()
{
    var qsb = new QsBuilder();
    qsb.addInput("bagian", "bagian");
    
    var addr = "pegawai.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakPegawai', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();
        return "-";
    }

}
