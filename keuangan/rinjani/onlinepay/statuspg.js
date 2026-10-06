var helpBox = null;

$(document).ready(function() 
{
    helpBox = new DialogBox("#divHelpDialog", 500, 500);
    
    refreshStatusPg();
});

function refreshStatusPg()
{
    let divContent = $("#divContent");
    
    divContent.css("color", "#CCC");
    divContent.html("memuat ..");

    setTimeout(fetchStatusPg(), 1000);
}

function fetchStatusPg()
{
    let divContent = $("#divContent");

    $.ajax({
        url: "statuspg.ajax.php",
        method: "POST",
        data: "op=fetchstatuspg",
        success: function (json)
        {
            console.log(json);                

            var lsResult = $.parseJSON(json);
            var value = parseInt(lsResult[0]);
            var message = lsResult[1];

            if (value < 0)
            {
                divContent.css("color", "#FF0000");
                divContent.html(message);
                showToast(message, 2000, 'error', 'top');
                return;
            }

            let table = atob(lsResult[2]);
            divContent.html(table).hide().fadeIn(500);
        },
        error: function(xhr)
        {
            showToast(xhr.responseText, 2000, 'error', 'top');
        }
    })
}

function showStatusPgHelp()
{
    $.ajax({
        url: "../help/op_statuspg.html",
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