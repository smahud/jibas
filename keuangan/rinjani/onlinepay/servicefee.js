var dialogBox = null;
var helpBox = null;

$(document).ready(function() {

    dialogBox = new DialogBox("#divDialog", 500, 350);
    helpBox = new DialogBox("#divHelpDialog", 500, 500);

    Tables('table', 1, 0);
    $("#table").hide().fadeIn(500);    
});

showServiceFeeHelp = function()
{
   $.ajax({
        url: "../help/op_servicefee.html?r=" + Math.random(),

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

changeDept = function () 
{
    location.href = "servicefee.php?dept=" + $("#dept").val();
};

tambahBiayaLayanan = function ()
{
    if ($("#dept option").length === 0)
        return;

    var addr = "servicefee.dialog.php?id=0&dept=" + $("#dept").val();
    newWindow(addr, 'AddServiceFee', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

setBiayaLayananAktif = function(id, newAktif)
{
    let msg = (newAktif === 0) ? 
              "Apakah anda akan menonaktifkan biaya layanan ini?" :
              "Apakah anda akan mengaktifkan biaya layanan ini?";

    if (!confirm(msg))
        return;

    $.ajax({
        url: "servicefee.ajax.php",
        data: "op=895723984732984732&id=" + id + "&newaktif=" + newAktif + "&dept=" + $("#dept").val(),
        success: function (json)
        {
            var lsResult = JSON.parse(json);
            if (parseInt(lsResult) < 0)
            {
                alert(lsResult[1]);
                return;
            }

            if (newAktif === 1)
                $("#spAktif" + id).html("<a href='#' onclick='setBiayaLayananAktif(" + id + ", 0)'><img src='../images/ico/aktif.png' border='0' title='set non aktif'></a>");
            else
                $("#spAktif" + id).html("<a href='#' onclick='setBiayaLayananAktif(" + id + ", 1)'><img src='../images/ico/nonaktif.png' border='0' title='set aktif'></a>");

            $("#dvTotal").html(lsResult[1]);

            sendToAppServer("datasync");
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

editBiayaLayanan = function(id)
{
    var addr = "servicefee.dialog.php?id=" + id;
    newWindow(addr, 'EditBiayaLayanan', '550', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

hapusBiayaLayanan = function (id)
{
    if (!confirm("Hapus biaya layanan ini?"))
        return;

    $.ajax({
        url: "servicefee.ajax.php",
        method: "POST",
        data: "op=847293847324&id=" + id,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result) < 0)
            {
                showToast(result[1], 2000, 'error', 'top');
                return;
            }

            showToast("Terhapus", 2000, 'success', 'top');
            sendToAppServer("datasync");

            setTimeout(function () {
                location.reload();
            }, 500)
        },
        error: function(xhr)
        {
            showToast(xhr.responseText, 2000, 'error', 'top');
        }
    })
};

updateTagihan = function ()
{
    if (!confirm("Update biaya layanan di semua tagihan yang sudah disiapkan?\nNOTE: Tagihan yang sudah checkout tidak termasuk"))
        return;

    $.ajax({
        url: "servicefee.ajax.php",
        data: "op=98759843758934758&dept=" + $("#dept").val(),
        success: function (jsonResult)
        {
            var lsResult = JSON.parse(jsonResult);
            if (parseInt(lsResult[0]) < 0)
            {
                showToast(lsResult[1], 2000, 'error', 'top');
                alert("KESALAHAN:\r\n" + lsResult[1]);
                return;
            }

            showToast(lsResult[1], 2000, 'success', 'bottom');
        },
        error: function (xhr) {
            alert(xhr.responseText);
        }
    })
};

function showServiceFeeHelp()
{
     $.ajax({
        url: "../help/op_servicefee.html?r=" + Math.random(),
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