var helpBox = null;

$(document).ready(function () 
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
    
    if ($("#table").length)
        Tables('table', 1, 0);
});

function tambah() 
{
    newWindow('kelompokpelajaran.dialog.php', 'TambahAspekNilai', '450', '300', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() 
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");

    $.ajax({
        url: "kelompokpelajaran.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response) 
        {
            $("#dvTableContent").html(response).hide().fadeIn(500);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function edit(replid) 
{
    newWindow('kelompokpelajaran.dialog.php?replid=' + replid, 'UbahAspekNilai', '450', '300', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) 
{
    if (!confirm("Hapus kelompok pelajaran ini?")) 
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "kelompokpelajaran.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response) 
        {
            let json = JSON.parse(response);
            if (json[0] == 1) 
            {
                refresh();
            }
            else 
            {
                showToastErrorBottom(json[1]);
                alert(json[1]);
            }
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function cetak() 
{
    newWindow('kelompokpelajaran.cetak.php', 'CetakKelompokPelajaran', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0')
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
        url: "../help/gp_kelompokpelajaran.html?r=" + Math.random(),
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