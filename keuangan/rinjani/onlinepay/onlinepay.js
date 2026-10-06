var helpBox = null;

$(document).ready(function() 
{
    helpBox = new DialogBox("#divHelpDialog", 500, 500);

    let pgAvailable = parseInt($("#pgavailable").val());
    let dvSjsInfo = $("#dvSjsInfo");
    if (pgAvailable === 0)
    {
        dvSjsInfo.hide();
    }
    else 
    {
        dvSjsInfo.show();
        sendSjsTest();
    }
});

sendSjsTest = function()
{
    let dvSjsStatus = $("#dvSjsStatus");
    let spSjsMesssage = $("#spSjsMesssage");

    dvSjsStatus.css("background-color", "#ccc");
    spSjsMesssage.html("memuat .. ");

    $.ajax({
        url: "appserver.ajax.php",
        method: "POST",
        data: "op=5035739847524985",
        success: function (json)
        {
            var lsResult = $.parseJSON(json);
            var value = parseInt(lsResult[0]);
            var message = lsResult[1];

            if (value < 0)
            {
                dvSjsStatus.css("background-color", "#ff0000");
                spSjsMesssage.html(message);

                showToast(message, 2000, 'error', 'top');
            }
            else
            {
                dvSjsStatus.css("background-color", "#28a745");
                spSjsMesssage.html("");
            }
        },
        error: function(xhr)
        {
            showToast(xhr.responseText, 2000, 'error', 'top');
        }
    })
}

checkAllConfigReady = function (refParam)
{
    $.ajax({
        url: "onlinepay.ajax.php",
        method: "POST",
        data: "op=787458343894734",
        success: function (jsonResult)
        {
            //console.log(jsonResult);
            var lsResult = $.parseJSON(jsonResult);
            if (parseInt(lsResult[0]) !== 1)
            {
                alert(lsResult[1]);
                return;
            }

            var refNo = parseInt(refParam);
            switch (refNo)
            {
                case 1:
                    document.location.href = "tagihan.php"; break;
                case 2:
                    document.location.href = "tagihansiswa.php"; break;
                case 3:
                    document.location.href = "daftartagihan.php"; break;
                case 4:
                    document.location.href = "caritagihan.php"; break;
                case 5:
                    document.location.href = "riwayattrans.php"; break;
                case 6:
                    document.location.href = "lebihtrans.php"; break;
                case 7:
                    document.location.href = "statistik.php"; break;
                case 8:
                    document.location.href = "saldobank.php"; break;
                case 9:
                    document.location.href = "mutasibank.php"; break;
                case 10:
                    document.location.href = "lebihtrans.php"; break;
                default:
                    document.location.href = "onlinepay.php";
            }
        },
        error: function (xhr) {

        }
    })
};

function showOnlinePayHelp()
{
    $.ajax({
        url: "../help/op_onlinepay.html?r=" + Math.random(),
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

function showKoneksiPgHelp()
{
    $.ajax({
        url: "../help/op_koneksipg.html?" + Math.random(),
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