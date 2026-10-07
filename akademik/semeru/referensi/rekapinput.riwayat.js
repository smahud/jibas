$(document).ready(function() {
    applyTables();
});

function applyTables()
{
    if ($("#table").length)
        Tables('table', 1, 0);
}

function refresh()
{
    onChangePage();
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
    qsb.add("op", "riwayat");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("page", "page");
    qsb.addInput("rentang", "rentang");
    qsb.addInput("keyword", "keyword");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "rekapinput.riwayat.ajax.php",
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

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    let addr = "rekapinput.riwayat.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakRiwayat','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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