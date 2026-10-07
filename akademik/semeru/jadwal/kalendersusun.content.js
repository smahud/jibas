$(document).ready(function () {
    if ($("#table").length)
        Tables("table", 1, 0);
});
    

function tambah() 
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idkalender", "idkalender");
    qsb.addInput("kalender", "kalender");

    newWindow('kalendersusun.dialog.php?'+qsb.createQs(), 'TambahKegiatan', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}    

function edit(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idkalender", "idkalender");
    qsb.addInput("kalender", "kalender");

    newWindow('kalendersusun.dialog.php?'+qsb.createQs(), 'EditKegiatan', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function hapus(replid)
{
    if (!confirm("Hapus kegiatan kalender akademik ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "kalendersusun.content.ajax.php",
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

            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });  
}

function view(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("kalender", "kalender");

    newWindow('kalendersusun.content.view.php?'+qsb.createQs(), 'ViewKegiatan', '780', '680', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refresh");
    qsb.addInput("idkalender", "idkalender");

    $("#dvLoading").show();

    $.ajax({
        url: "kalendersusun.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);
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
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("kalender", "kalender");
    
    newWindow('kalendersusun.content.cetak.php?'+qsb.createQs(), 'CetakKalender', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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