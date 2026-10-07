var helpBox = null;

$(document).ready(function () 
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);

    if ($("#table").length)
        Tables('table', 1, 0);
});

function tambah() 
{
    newWindow('aspeknilai.dialog.php', 'TambahAspekNilai', '500', '270', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh() 
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");

    $.ajax({
        url: "aspeknilai.ajax.php",
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
    newWindow('aspeknilai.dialog.php?replid=' + replid, 'UbahAspekNilai', '500', '270', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(replid) 
{
    if (!confirm("Hapus aspek penilaian ini?")) 
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "aspeknilai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response) 
        {
            let json = JSON.parse(response);
            if (json[0] == 1) 
                refresh();
            else 
                showToastErrorBottom(json[1]);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function cetak() 
{
    newWindow('aspeknilai.cetak.php', 'CetakAspekPenilaian', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0')
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

function setNewAktif(replid, newAktif)
{
    let message = newAktif == 1 ? "Aktifkan kembali data ini?" : "Non aktifkan data ini?";
    if (!confirm(message))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: "aspeknilai.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response) 
        {
            let lsResponse = JSON.parse(response);
            if (parseInt(lsResponse[0]) < 0) 
            {
                alert(lsResponse[1]);
                return;
            }
                
            if (newAktif == 0)                        
            {
                $('#aktif'+replid).prop('src', '../images/ico/nonaktif.png');
                $('#aktif'+replid).prop('title', 'tidak aktif');
                $('#aktif'+replid).attr('onclick', 'setNewAktif('+replid+', 1)');
            }   
            else
            {
                $('#aktif'+replid).prop('src', '../images/ico/aktif.png');
                $('#aktif'+replid).prop('title', 'aktif');
                $('#aktif'+replid).attr('onclick', 'setNewAktif('+replid+', 0)');
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
    newWindow('aspeknilai.cetak.php', 'CetakAspekPenilaian', '790', '650', 'resizable=1,scrollbars=1,status=0,toolbar=0')
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
        url: "../help/gp_aspekpenilaian.html?r=" + Math.random(),
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