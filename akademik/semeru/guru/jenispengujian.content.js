$(document).ready(function() 
{
    if ($("#table").length)
        Tables('table', 0, 0);
});

function refresh() 
{     
    document.location.reload();
    parent.list.refresh();
}

function tambah() 
{         
    let qsb = new QsBuilder();
    qsb.addInput("preplid", "preplid");
    qsb.addInput("nama_pel", "nama_pel");
    qsb.addInput("nama_dep", "nama_dep");

    newWindow('jenispengujian.dialog.php?' + qsb.createQs(), 'TambahJenisPengujian', '550', '400', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function edit(replid) 
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("preplid", "preplid");
    qsb.addInput("nama_pel", "nama_pel");
    qsb.addInput("nama_dep", "nama_dep");

    newWindow('jenispengujian.dialog.php?' + qsb.createQs(), 'UbahJenisPengujian', '550', '400', 'resizable=1,scrollbars=1,status=1,toolbar=0')
}

function hapus(replid, idpelajaran) 
{         
    if (!confirm("Hapus jenis pengujian ini?")) 
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);

    $.ajax({
        url: "jenispengujian.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0) 
            {
                alert(ls[1]);
                showToastErrorBottom(ls[1]);
                return;
            }

            refresh();
        }
    });
}

function cetak(id) 
{
    let qsb = new QsBuilder();
    qsb.addInput("pelajaran", "nama_pel");
    qsb.addInput("departemen", "nama_dep");

    newWindow('jenispengujian.content.cetak.php?' + qsb.createQs(), 'CetakJenisPengujian', '790', '650', 'resizable=1,scrollbars=1,status=1,toolbar=0')
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
    newWindow('../help/gp_jenispengujian.html?r=' + Math.random(), 'JenisPengujianHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}