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

function tambah(jam, hari) 
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
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
    qsb.add("jam", jam);
    qsb.add("hari", hari);

    newWindow('jadwalkelas.dialog.php?'+qsb.createQs(), 'TambahJadwalKelas', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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

    newWindow('jadwalkelas.dialog.php?'+qsb.createQs(), 'EditJadwalKelas', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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
        url: "jadwalkelas.content.ajax.php",
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

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("tingkat", "tingkat");
    
    newWindow('jadwalkelas.content.cetak.php?'+qsb.createQs(), 'CetakJadwalKelas', '800', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
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

function hapusKelas()
{
    if (!confirm("HAPUS JADWAL KELAS INI?\nSemua jadwal guru di kelas ini akan terhapus!"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapuskelas");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idkategori", "idkategori");
	
	setGui("wait");

    $.ajax({
        url: "jadwalkelas.content.ajax.php",
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

function onDataChanged()
{
    document.location.reload();
}

function showSalinJadwal()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkategori", "idkategori");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("idkelas", "idkelas");        
    qsb.addInput("kelas", "kelas");

    newWindow('jadwalkelas.salin.dialog.php?'+qsb.createQs(), 'SalinJadwalKelas', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function onSalinJadwal(nBentrok, data64)
{
    if (nBentrok > 0)
    {
        let lsBentrok = JSON.parse(atob(data64));
        
        let stBentrok = "";
        for (let i = 0; i < lsBentrok.length; i++)
        {
            stBentrok += lsBentrok[i] + "\n";
        }
        
        let msg = "BERHASIL SALIN JADWAL\n\nTerdapat " + nBentrok + " jadwal yang bentrok:\n\n" + stBentrok;
        alert(msg);
    }
    else 
    {
        alert("BERHASIL SALIN JADWAL");
    }
    
    document.location.reload();
}

function showKeterangan(r, c)
{
    let content = $("#ket-" + r + "-" + c).val();
    content = "<span style='font-size: 14px; line-height: 1.8em; color: #333333;'>" + content + "</span>";
    helpBox.show(content);
}