var activeCell = null;
var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});

function cellHover(cell)
{
    if (activeCell == cell)
        return;

    $(cell).css("background-color", "#d9ffc1");
}

function cellClick(cell)
{
    if (activeCell != null)
        $(activeCell).css("background-color", "#ffffff");

    activeCell = cell;

    $(cell).css("background-color", "#fff08c");
}

function cellOut(cell)
{
    if (activeCell != cell)
        $(cell).css("background-color", "#ffffff");
}

function onDataChanged()
{
    document.location.reload();
}

function tambah(jam, hari) 
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idkategori", "idkategori");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("maxjam", "maxjam");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.add("jam", jam);
    qsb.add("hari", hari);

    newWindow('jadwalguru.dialog.php?'+qsb.createQs(), 'TambahJadwalGuru', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function edit(replid) 
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idkategori", "idkategori");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("maxjam", "maxjam");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    newWindow('jadwalguru.dialog.php?'+qsb.createQs(), 'EditJadwalGuru', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function hapus(replid) 
{
    if (!confirm("HAPUS JADWAL GURU INI?"))
        return;
        
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);
	
	setGui("wait");

    $.ajax({
        url: "jadwalguru.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            setGui("ready");

            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                showToastErrorTop(response[1]);
                return;
            }

            onDataChanged();
        },
        error: function (xhr)
        {
            setGui("ready");
            alert(xhr.responseText);
        }
    });
}

function hapusGuru()
{
    if (!confirm("HAPUS SEMUA JADWAL GURU INI?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapusguru");
    qsb.addInput("nip", "nip");
    qsb.addInput("idkategori", "idkategori");
	
	setGui("wait");

    $.ajax({
        url: "jadwalguru.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            setGui("ready");

            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                showToastErrorTop(response[1]);
                return;
            }

            onDataChanged();
        },
        error: function (xhr)
        {
            setGui("ready");
            alert(xhr.responseText);
        }
    });
}

function setGui(state)
{
    switch(state)
    {
        case "wait":
            $("#dvLoading").show();
            break;
        case "ready":
            $("#dvLoading").hide();
            break;
    }
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("kategori", "kategori");
    
    newWindow('jadwalguru.content.cetak.php?'+qsb.createQs(), 'CetakJadwalGuru', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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

function showKeterangan(r, c)
{
    let content = $("#ket-" + r + "-" + c).val();
    content = "<span style='font-size: 14px; line-height: 1.8em; color: #333333;'>" + content + "</span>";
    helpBox.show(content);
}