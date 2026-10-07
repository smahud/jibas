var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});

function showHelp()
{
    $.ajax({
        url: "../help/rf_kolomtambahan.html?r=" + Math.random(),
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

function tambah()
{
    let qsb = new QsBuilder();
    qsb.add("mode", "manage");
    qsb.addInput("departemen", "departemen");

    newWindow('kolomtambahan.dialog.php?' + qsb.createQs(), 'TambahKolomTambahan', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function onChangeDepartemen()
{
    refresh();
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refresh");
    qsb.addInput("departemen", "departemen");

    $("#dvLoading").show();

    $.ajax({
        url: "kolomtambahan.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function refreshPilihanData()
{
    refresh();
}

function refreshKolomTambahan()
{
    refresh();
}

function setNewAktif(replid, newAktif)
{
    let message = newAktif == 1 ?
        "Aktifkan kembali data tambahan ini?" :
        "Nonaktifkan data tambahan ini?";

    if (!confirm(message))
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: 'kolomtambahan.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(json) 
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }
            
            if (newAktif == 1)
            {
                $("#imAktif" + replid).prop("src", "../images/ico/aktif.png");
                $("#imAktif" + replid).prop("title", "aktif");
                $("#imAktif" + replid).attr("onclick", "setNewAktif(" + replid + ", 0)");
            }
            else
            {
                $("#imAktif" + replid).prop("src", "../images/ico/nonaktif.png");
                $("#imAktif" + replid).prop("title", "tidak aktif");
                $("#imAktif" + replid).attr("onclick", "setNewAktif(" + replid + ", 1)");
            }
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


function cetak()
{
    var qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    
    var addr = "kolomtambahan.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakKolomTambahan', '750', '700', 'resizable=1,scrollbars=1,status=0,toolbar=0');
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

function aturPilihanData(idTambahan, namaTambahan)
{
    let qsb = new QsBuilder();
    qsb.add("mode", "manage");
    qsb.add("idtambahan", idTambahan);
    qsb.add("namatambahan", namaTambahan);
    
    newWindow('kolomtambahan.pilihan.php?' + qsb.createQs(), 'PilihanData', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');      
}
