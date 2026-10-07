var helpBox = null;

$(document).ready(function()
{

    helpBox = new DialogBox("#divHelpDialog", 600, 500);

    if ($("#table").length) 
        Tables("table", 1, 0);

});

function onChangeDepartemen()
{
    let qsb = new QsBuilder();
    qsb.add("op", "content");
    qsb.addInput("departemen", "departemen");

    setGui("wait");
    
    $.ajax({
        url: "jam.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (result)
        {
            setGui("ready");

            $("#dvTableContent").html(result).hide().fadeIn(300);

            if ($("#table").length) 
                Tables("table", 1, 0);
        },
        error: function (xhr)
        {
            setGui("ready");
            alert(xhr.responseText);
        }
    });
}

function tambahJam()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");

    newWindow("jam.dialog.php?" + qsb.createQs(), "TambahJam", "500", "350", "resizable=1,scrollbars=1,status=0,toolbar=0");
}

function editJam(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");

    newWindow("jam.dialog.php?" + qsb.createQs(), "EditJam", "500", "350", "resizable=1,scrollbars=1,status=0,toolbar=0");
}

function hapusJam(replid)
{
    if (!confirm("Hapus jam ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    setGui("wait");

    $.ajax({
        url: "jam.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            setGui("ready");

            let response = JSON.parse(json);
            if (parseInt(response[0]) <= 0)
            {
                showToastErrorTop(response[1]);
                return;
            }

            onDataChanged();
        },
        error: function (xhr)
        {
            setGui("ready");
            alert(xhr.responseText);
        }
    });
}

function setGui(state)
{
    switch(state)
    {
        case "wait":
            $("#dvLoading").show();
            break;
        case "ready":
            $("#dvLoading").hide();
            break;
    }
}

function onDataChanged()
{
    onChangeDepartemen();
}

function refresh()
{
    onChangeDepartemen();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    
    newWindow('jam.cetak.php?'+qsb.createQs(), 'CetakJam', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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

function showHelp()
{
    $.ajax({
        url: "../help/jk_jam.html?r=" + Math.random(),
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