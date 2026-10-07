$(document).ready(function() {
    if ($("#table").length > 0)
        Tables('table', 1, 0);
})

function changePin(replid, jenis)
{
    let messsage = "";
    if (jenis == "calonsiswa")
        messsage = "Ganti PIN Calon Siswa?";
    
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

            if (jenis == "calonsiswa")
                $("#spPinSiswa" + replid).html(arr[1]);

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

function showInfoCalonSiswa(nic)
{
    var qsb = new QsBuilder();
    qsb.add("nic", nic);

    newWindow('../library/infocalonsiswa.dialog.php?'+qsb.createQs(), 'InformasiCalonSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
 
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