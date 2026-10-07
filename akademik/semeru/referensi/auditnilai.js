var helpBox = null;

$(document).ready(function () 
{
    applyTables();

    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});

function applyTables()
{
    if ($("#table").length)
        Tables('table', 1, 0);
}

function onKeySearch(e)
{
    if (e.keyCode === 13)
    {
        onChangeBulanTahun();
    }
}

function onChangeBulanTahun()
{
    let qsb = new QsBuilder();
    qsb.add("op", "count");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
    qsb.addInput("keyword", "keyword");

    $("#dvTableContent").html("");
    $("#dvPageControl").html("");
    $("#dvLoading").show();
    
    $.ajax({
        url: "auditnilai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let resp = JSON.parse(json);
            if (resp[0] < 0)
            {
                alert(resp[1]);
                return;
            }
            
            let totalData = parseInt(resp[1]);

            fetchDaftarAudit(totalData, 1);

            setTimeout(function() {
                fetchPageControl(totalData);
            }, 100);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function ()
        {
            $("#dvLoading").hide();
        }
    }); 
}

function fetchDaftarAudit(totalData, page)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
    qsb.addInput("keyword", "keyword");
    qsb.add("page", page);
    qsb.add("totaldata", totalData);
    
    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");
    
    $.ajax({
        url: "auditnilai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            dvTableContent.html(data).hide().fadeIn(300);
            applyTables();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    }); 
}

function fetchPageControl(totalData)
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.add("totaldata", totalData);

    let dvPageControl = $("#dvPageControl");
    dvPageControl.html("memuat ..");
    
    $.ajax({
        url: "auditnilai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            dvPageControl.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    }); 
}

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.add("bulan", $("#bulan option:selected").text());
    qsb.add("tahun", $("#tahun option:selected").text());

    newWindow('auditnilai.cetak.php?'+qsb.createQs(), 'CetakAuditNilai','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
    let page = $("#page").val();
    let totalData = $("#totaldata").val();
    
    fetchDaftarAudit(totalData, page);
}

function showHelp()
{
    $.ajax({
        url: "../help/rf_auditnilai.html?r=" + Math.random(),
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