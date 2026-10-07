$(document).ready(function () 
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function cariPegawai()
{
    newWindow("../library/daftarpegawai.dialog.php", 350, 550, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nip").val(data.NIP);
    $("#nama").val(data.Nama);
}

function simpan()
{
    let isValid = Vldr.HasOption("pelajaran", "Pelajaran") &&
                  Vldr.IsHaveInput("nip", "Guru") &&
                  Vldr.InputText("jam2", "Jam Akhir", 1, 2) && 
                  Vldr.IsInteger("jam2", "Jam Akhir") &&
                  Vldr.IsPositive("jam2", "Jam Akhir");

    if (isValid)                  
    {
        let jam1 = parseInt($("#jam1").val());
        let jam2 = parseInt($("#jam2").val());
        let maxJam = parseInt($("#maxjam").val());

        if (jam1 > jam2)
        {
            alert("Jam Akhir harus lebih besar dari Jam Awal");
            $("#jam2").focus();
            isValid = false;
            return;
        }

        if (jam2 > maxJam)
        {
            alert("Jam Akhir tidak boleh lebih besar dari " + maxJam);
            $("#jam2").focus();
            isValid = false;
            return;
        }
    }

    if (!isValid)
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idkategori", "idkategori");
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.addInput("nip", "nip");
    qsb.addInput("jam1", "jam1");
    qsb.addInput("jam2", "jam2");
    qsb.addInput("jamorig1", "jamorig1");
    qsb.addInput("jamorig2", "jamorig2");
    qsb.addInput("hari", "hari");
    qsb.addInput("status", "status");
    qsb.addInput("keterangan", "keterangan");

    setGui("wait");

    $.ajax({
        url: "jadwalkelas.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            setGui("ready");

            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                alert(response[1]);
                $("#spInfo").html(response[1]).hide().fadeIn(300);
                return;
            }

            if (parseInt(response[0]) == 0)
            {
                alert("Ada jadwal yang bentrok");
                $("#spInfo").html(response[1]).hide().fadeIn(300);
                return;
            }

            opener.onDataChanged();
            window.close();
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
            $("#btnSimpan").prop("disabled", true);
            $("#btnTutup").prop("disabled", true);
            $("#dvLoading").show();
            break;
        case "ready":
            $("#btnSimpan").prop("disabled", false);
            $("#btnTutup").prop("disabled", false);
            $("#dvLoading").hide();
            break;
    }
}