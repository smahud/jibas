var dialogBox = null;

$(document).ready(function()
{
    dialogBox = new DialogBox("#divDialog", 500, 500);

    if ($("#table").length)
        Tables('table', 1, 0);
});

function showDaftarCalonSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("page", "page");
    
    document.location.href = "pendataan.content.php?" + qsb.createQs();
}

function onPrevPage()
{
    let page = parseInt($("#page").val());
    if (page === 1)
        return;

    $("#page").val(page - 1);
    onChangePage();    
}

function onNextPage()
{
    let page = parseInt($("#page").val());
    let npage = parseInt($("#npage").val());

    if (page === npage)
        return;

    $("#page").val(page + 1);
    onChangePage();
}

function refresh()
{
    onChangePage();
}

function onChangeOrderBy()
{
    let qsb = new QsBuilder();
    qsb.add("op", "changepage");
    qsb.add("page", "1");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("idkelompok", "idkelompok");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'pendataan.infonilai.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(data) 
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);

            if ($("#table").length)
                Tables('table', 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function onChangePage()
{
    let qsb = new QsBuilder();
    qsb.add("op", "changepage");
    qsb.addInput("page", "page");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("idproses", "idproses");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'pendataan.infonilai.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(data) 
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);

            if ($("#table").length)
                Tables('table', 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}


function showInfoCalonSiswa(nopendaftaran)
{
    var qsb = new QsBuilder();
    qsb.add("nic", nopendaftaran);

    newWindow('../library/infocalonsiswa.dialog.php?'+qsb.createQs(), 'InformasiCalonSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");

    let addr = "pendataan.infonilai.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakNilaiCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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

function excel()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("orderby", "orderby");

    let addr = "pendataan.content.excel.php?" + qsb.createQs();
    newWindow(addr, 'ExcelDaftarSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}