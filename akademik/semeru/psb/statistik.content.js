$(document).ready(function() 
{
    if ($("#tableRekap").length)
        Tables("tableRekap", 1, 0);

    if ($("#tableSiswa").length)
        Tables("tableSiswa", 1, 0);
});

function saveExcel()
{
    let qsb = new QsBuilder();
    qsb.addInput("jenisstatistik", "jenisstatistik");
    qsb.addInput("jenisstatistiktext", "jenisstatistiktext");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("label", "activelabel");
    qsb.addInput("streplid", "streplid");

    let addr = "statistik.content.excel.php?" + qsb.createQs();
    newWindow(addr, 'ExcelStatistikDetail','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function showStatistikTableDetail(label)
{
    let qsb = new QsBuilder();
    qsb.add("op", "prepare");
    qsb.addInput("jenisstatistik", "jenisstatistik");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.add("label", label);
    qsb.add("page", 1);

    $("#dvLoading").show();
    $("#dvStatistikDetail").html("memuat ..");

    $.ajax({
        url: "statistik.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }

            if (parseInt(arr[0]) == 0)
            {
                $("#dvStatistikDetail").html(arr[1]);
                return;
            }

            $("#ndata").val(arr[2]);
            $("#npage").val(arr[3]);
            $("#lsidpage").val(JSON.stringify(arr[4]));
            $("#streplid").val(arr[5]);
            $("#activelabel").val(label);

            fetchListSiswa(1);
            fetchPageControl();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("npage", "npage");
    qsb.addInput("ndata", "ndata");

    $("#dvPageControl").html("memuat ..");

    $.ajax({
        url: "statistik.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvPageControl").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
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

function onChangePage()
{
    let page = parseInt($("#page").val());
    fetchListSiswa(page);
}

function fetchListSiswa(page)
{
    let lsIdPage = JSON.parse($("#lsidpage").val());
    let stReplid = lsIdPage[page - 1].join(",");

    let qsb = new QsBuilder();
    qsb.add("op", "siswa");
    qsb.add("page", page);
    qsb.add("streplid", stReplid);

    $("#dvLoading").show();
    $("#dvStatistikDetail").html("memuat ..");

    $.ajax({
        url: "statistik.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvStatistikDetail").html(data).hide().fadeIn(300);
            
            if ($("#tableSiswa").length)
                Tables("tableSiswa", 1, 0);

            let dvStatistikList = $('#dvStatistikList');
            dvStatistikList.scrollTop(dvStatistikList[0].scrollHeight);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function detailSiswa(replid) 
{
	newWindow('pendataan.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("jenisstatistiktext", "jenisstatistiktext");

    let addr = "statistik.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakDaftarSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section === "chart")
    {
        if ($("#dvStatistikChart").length)
            return $("#dvStatistikChart").html();

        return "-";
    }
    else if (section == "table")
    {
        if ($("#dvStatistikTable").length)
            return $("#dvStatistikTable").html();

        return "-";
    }
}

function dashboardCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../dashboard/dashboardcs.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function profilCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../library/infocalonsiswa.dialog.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '620','520', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}