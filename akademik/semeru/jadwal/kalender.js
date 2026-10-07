var helpBox = null;

$(document).ready(function() 
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);

    if ($("#table").length)
        Tables("table", 1, 0);
})

function onChangeDept()
{
    function acceptTahunAjaran()
    {
        refresh();
    }
    
    fetchTahunAjaran(acceptTahunAjaran);
}

function fetchTahunAjaran(callback)
{
    let spTahunAjaran = $("#spTahunAjaran");
    spTahunAjaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "tahunajaran");
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "rekap.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTahunAjaran.html(data).hide().fadeIn(300);

            callback();
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeTahunAjaran()
{
    refresh();
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.add("op", "content");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $.ajax({
        url: "kalender.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result)
        {
            $("#dvTableContent").html(result).hide().fadeIn(300);

            if ($("#table").length)
                Tables("table", 1, 0);
        },
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        }
    })
}

function setNewAktif(replid, newAktif)
{
    let message = newAktif == 1 ? 
        "Aktifkan kembali kalender akademik ini?":
        "Nonaktifkan kalender akademik ini?"

    if (!confirm(message))
        return

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);

    $.ajax({
        url: "kalender.ajax.php",
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

            if (newAktif == 1)
            {
                let im = $("#imStatus" + replid);
                im.attr("src", "../images/ico/aktif.png");
                im.attr("title", "Aktif");
                im.attr("onclick", "setNewAktif(" + replid + ", 0)");
            }
            else
            {
                let im = $("#imStatus" + replid);
                im.attr("src", "../images/ico/nonaktif.png");
                im.attr("title", "Tidak Aktif");
                im.attr("onclick", "setNewAktif(" + replid + ", 1)");
            }
        },
        error: function(xhr, status, error){
            alert(xhr.responseText);
        }
    })
    
}

function tambah()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());

    newWindow("kalender.dialog.php?" + qsb.createQs(), "Ta  mbahKalender", "550", "400", "resizable=1,scrollbars=1,status=0,toolbar=0");
}

function edit(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "tahunajaran");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());

    newWindow("kalender.dialog.php?" + qsb.createQs(), "TambahKalender", "550", "400", "resizable=1,scrollbars=1,status=0,toolbar=0");
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    
    newWindow('kalender.cetak.php?'+qsb.createQs(), 'CetakKalender', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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
        url: "../help/jk_kalender.html?r=" + Math.random(),
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

function hapus(replid)
{
    if (!confirm("Hapus data kalender ini?"))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "kalender.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                showToastErrorBottom(ls[1]);
                return;
            }

            refresh();
        },
        error: function(xhr, status, error){
            alert(xhr.responseText);
        }
    })
}