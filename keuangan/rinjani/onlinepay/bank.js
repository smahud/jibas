var helpBox = null;

$(document).ready(function ()
{
    if ($("#table").length)
        Tables("table", 1, 0);

    helpBox = new DialogBox("#divHelpDialog", 500, 500);
});

function showHelp()
{
    $.ajax({
        url: "../help/op_bank.html",
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

function tambahBank()
{
    if ($("#departemen option").length === 0)
        return;

    let qsb = new QsBuilder();
    qsb.add("idbank", "0");
    qsb.addInput("departemen", "departemen");

    var addr = "bank.dialog.php?" + qsb.createQs();
    newWindow(addr, 'AddBank', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function editBank(idBank)
{
    if ($("#departemen option").length === 0)
        return;

    let qsb = new QsBuilder();
    qsb.add("idbank", idBank);
    qsb.addInput("departemen", "departemen");

    var addr = "bank.dialog.php?" + qsb.createQs();
    newWindow(addr, 'EditBank', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapusBank(idBank)
{
    if (!confirm("Hapus data bank ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("idbank", idBank);

    $.ajax({
        url: "bank.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            sendToAppServer("datasync");

            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
   
}

function showHelpBank()
{
    $.ajax({
        url: "../help/op_bank.html?r=" + Math.random(),

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

function refresh()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    document.location.href = "bank.php?" + qsb.createQs();
}

function setBankAktif(idBank, newAktif)
{
    let message = (newAktif === 0) ? "Apakah anda akan NON AKTIF kan Bank ini?" : "Apakah anda akan mengaktifkan Bank ini?";
    if (!confirm(message))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("idbank", idBank);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: "bank.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }
            
            if (newAktif === 1)
                $("#spAktif" + idBank).html("<a href='#' onclick='setBankAktif(" + idBank + ", 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>");
            else
                $("#spAktif" + idBank).html("<a href='#' onclick='setBankAktif(" + idBank + ", 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>");

            sendToAppServer("datasync");
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function dataSync()
{
    sendToAppServer("datasync");
}

function showQris(idBank)
{
    let qsb = new QsBuilder();
    qsb.add("idbank", idBank);

    var addr = "bank.qris.php?" + qsb.createQs();
    newWindow(addr, 'ShowQrisBank', '650', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');   
}

function hapusQris(idBank)
{
    if (!confirm("Anda yakin akan menghapus QRIS ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapusqris");
    qsb.add("idbank", idBank);

    $.ajax({
        url: "bank.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            $("#spQris" + idBank).html("<i>(belum tersedia)</i>");

            sendToAppServer("datasync");
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });

}

function onChangeDept()
{
    let qsb = new QsBuilder();
    qsb.add("op", "banklist");
    qsb.add("departemen", $("#departemen").val());

    $("#divBankList").html("memuat ..");
    
    $.ajax({
        url: "bank.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            $("#divBankList").html(json).hide().fadeIn(500);

            if ($("#table").length)
                Tables("table", 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}