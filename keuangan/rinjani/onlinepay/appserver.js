var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 500, 500);
});

function showHelp()
{
    $.ajax({
        url: "../help/op_sjs.html?r=" + Math.random(),
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

function saveJsServerAddr()
{
    var ipAddr = $.trim($("#ipaddr").val());
    if (ipAddr.length === 0)
    {
        alert("Masukan alamat IP dari JIBAS Sinkronisasi Jendela Sekolah");
        return;
    }

    const ipRegex = /^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$/;
    if (!ipRegex.test(ipAddr))
    {
        alert("Format alamat IP tidak benar!")
        return;
    }

    var status = $("#status");
    var simpan = $("#simpan");

    status.css("color", "#0000FF");
    status.html("menghubungi aplikasi JIBAS Sinkronisasi Jendela Sekolah .. ");
    simpan.attr("disabled", true);

    $.ajax({
        url: "appserver.ajax.php",
        method: "POST",
        data: "op=0989032420394732&ipaddr=" + ipAddr,
        success: function (json)
        {
            var lsResult = $.parseJSON(json);
            var value = parseInt(lsResult[0]);
            var message = lsResult[1];

            if (value < 0)
            {
                status.css("color", "#FF0000");
                status.html(message);
                simpan.attr("disabled", false);

                showToast(message, 2000, 'error', 'top');
            }
            else
            {
                status.css("color", "#0000FF");
                status.html("tersimpan");
                simpan.attr("disabled", false);

                showToast("Berhasil", 2000, 'success', 'top');
            }
        },
        error: function(xhr)
        {
            showToast(xhr.responseText, 2000, 'error', 'top');
        }
    })
}

function sendToAppServer(op, data, info, extra)
{
    if (data === undefined) data = "";
    if (info === undefined) info = "";
    if (extra === undefined) extra = "";

    var r = Math.floor(Math.random() * (99999 - 10000) ) + 10000;

    var qs = "op=" + encodeURIComponent(op);
    qs += "&r=" + r;
    if (data !== "") qs += "&data=" + encodeURIComponent(data);
    if (info !== "") qs += "&info=" + encodeURIComponent(info);
    if (extra !== "") qs += "&extra=" + encodeURIComponent(extra);

    console.log("sending to appserver " + qs);

    $.ajax({
        url: "appserver.sender.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            console.log("sendToAppServer result " + json);
        },
        error: function(xhr)
        {
            console.log("sendToAppServer error  " + xhr.responseText);
        }
    })
}

function showAppServerSample()
{
    $("#imContoh").attr("src", "appserver.jpg").hide().fadeIn(500);
}