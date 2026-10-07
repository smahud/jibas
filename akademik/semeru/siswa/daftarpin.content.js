$(document).ready(function() {
    if ($("#table").length > 0)
        Tables('table', 1, 0);
})

function changePin(replid, jenis)
{
    let messsage = "";
    if (jenis == "siswa")
        messsage = "Ganti PIN Siswa?";
    else if (jenis == "ayah")
        messsage = "Ganti PIN Ayah?";
    else if (jenis == "ibu")
        messsage = "Ganti PIN Ibu?";
    
    if (!confirm(messsage))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "gantipin")        
    qsb.add("replid", replid);
    qsb.add("jenis", jenis);

    $("#dvLoading").show();

    $.ajax({
        url: "daftarpin.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }

            if (jenis == "siswa")
                $("#spPinSiswa" + replid).html(arr[1]);
            else if (jenis == "ayah")
                $("#spPinAyah" + replid).html(arr[1]);
            else if (jenis == "ibu")
                $("#spPinIbu" + replid).html(arr[1]);

            showToastSuccessTop("Berhasil");
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })

    
}

function showInfoSiswa(nis)
{
    var qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../library/infosiswa.dialog.php?'+qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
 
    let addr = "daftarpin.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakDaftarPin','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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