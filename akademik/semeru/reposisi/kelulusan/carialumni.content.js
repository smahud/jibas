var dialogBox = null;

$(document).ready(function()
{
    dialogBox = new DialogBox("#divDialog", 500, 500);

    if ($("#table").length)
        Tables('table', 1, 0);
});

function showImageSiswa(replid)
{
    let nama = $("#nama" + replid).html();
    let nis = $("#nis" + replid).html();
    let panggilan = $("#panggilan" + replid).html();
    let foto = $("#img" + replid).attr("src");

    let content = "<div style='text-align: center;'>";
    content += "<img  src='" + foto + "'><br><br>";
    content += "<span class='fs-14 fst-bold'>" + nama + "</span><br>";
    content += "<span class='ff-consolas fs-13'>" + nis + "</span><br>";
    content += "<span class='fst-italic fs-12 fg-secondary'>" + panggilan + "</span>";
    content += "</div>";
    
    dialogBox.show(content);
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
    let lsIdPage = JSON.parse($("#lsidpage").val());
    let stReplid = lsIdPage[page - 1].join(",");

    let qsb = new QsBuilder();
    qsb.add("op", "changepage");
    qsb.add("streplid", stReplid);
    qsb.addInput("page", "page");
    qsb.addInput("jeniscari", "jeniscari");
    qsb.addInput("jeniscaritext", "jeniscaritext");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: "carialumni.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function (data)
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
    })
}

function detailSiswa(replid) 
{
	newWindow('../../siswa/siswa.detail.php?replid='+replid, 'DetailAlumniSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("jeniscaritext", "jeniscaritext");
    qsb.addInput("cari", "cari");

    let addr = "carialumni.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakPendataanAlumni','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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