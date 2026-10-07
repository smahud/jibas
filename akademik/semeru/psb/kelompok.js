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
        url: '../help/psb_kelompok.html?r=' + Math.random(),
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

function onDepartemenChange()
{
    fetchActiveProcess(acceptActiveProcess);
}

function fetchActiveProcess(callback)
{
    let qsb = new QsBuilder();
    qsb.add('op', 'proses');
    qsb.addInput('departemen', 'departemen');

    let spProses = $('#spProses');
    spProses.html('memuat ..');

    $("#dvTableContent").html("");
    $("#dvPageControl").html("");

    $.ajax({
        url: 'kelompok.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (response)
        {
            spProses.html(response).hide().fadeIn(400);

            if (callback)
                callback();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function acceptActiveProcess()
{
    fetchCountData(acceptCountData);
}

function fetchCountData(callback)
{
    $('#dvTableContent').html("");
    $('#dvPageControl').html("");
    
    let qsb = new QsBuilder();
    qsb.add('op', 'count');
    qsb.addInput('idprosespsb', 'idprosespsb');

    $.ajax({
        url: 'kelompok.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (response)
        {
            let ls = JSON.parse(response);
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
        let hint = HintInfo.ShowCenter("Belum ada data kelompok calon siswa<br>Silahkan klik ikon tambah untuk membuat kelompok calon siswa baru");
        $("#spCountInfo").html(hint).hide().fadeIn(300);
        return;
    }

    $("#spCountInfo").html("");
    
    fetchTableProcess(1);

    setTimeout(function() {
        fetchPageControl();
    }, 100);
}

function fetchTableProcess(page)
{
    let qsb = new QsBuilder();
    qsb.add('op', 'daftar');
    qsb.addInput('departemen', 'departemen');
    qsb.addInput('idprosespsb', 'idprosespsb');
    qsb.addInput('ndata', 'ndata');
    qsb.add('page', page);

    let dvTableContent = $('#dvTableContent');
    dvTableContent.html('memuat ..');

    $.ajax({
        url: 'kelompok.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (data)
        {
            dvTableContent.html(data).hide().fadeIn(400);

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
        url: 'kelompok.ajax.php',
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

function onPrevPage()
{
    let page = parseInt($('#page').val());
    if (page === 1)
        return;

    page -= 1;
    $('#page').val(page);

    onChangePage();
}

function onNextPage()
{
    let page = parseInt($('#page').val());
    let totalPage = parseInt($('#totalpage').val());
    if (page === totalPage)
        return;

    page += 1;
    $('#page').val(page);

    onChangePage();
}

function onChangePage()
{
    let page = parseInt($('#page').val());

    fetchTableProcess(page);
}

function onDataChanged()
{
    onChangePage();
}

function onNewData()
{
    onDepartemenChange();
}

function refresh()
{
    onChangePage();        
}

function tambah()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.InputText("prosespsb", "Proses Penerimaan");
    if (!isValid)
        return;                 

    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen")
    qsb.addInput("idprosespsb", "idprosespsb")
    qsb.addInput("prosespsb", "prosespsb")

    newWindow('kelompok.dialog.php?' + qsb.createQs(), 'TambahKelompokCalonSiswa', '500', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function edit(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    
    newWindow('kelompok.dialog.php?' + qsb.createQs(), 'UbahKelompokCalonSiswa', '500', '350', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm('HAPUS KELOMPOK CALON SISWA INI?'))
        return;

    let qsb = new QsBuilder();
    qsb.add('op', 'hapus');
    qsb.add('replid', replid);
    qsb.addInput('departemen', 'departemen');
    qsb.addInput('proses', 'proses');

    $.ajax({
        url: 'kelompok.ajax.php',
        method: 'POST',
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

function lihat(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");

    newWindow('kelompok.detail.php?' + qsb.createQs(), 'DaftarCalonSiswa', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput('departemen', 'departemen');
    qsb.addInput('proses', 'proses');

    let addr = 'kelompok.cetak.php?' + qsb.createQs();
    newWindow(addr, 'CetakKelompokCalonSiswa', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section === 'content')
    {
        if ($('#dvTableContent').length)
            return $('#dvTableContent').html();
        return '-';
    }
}
