$(document).ready(function ()
{
    $("#kelas").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function cariWali()
{
    newWindow("../library/daftarpegawai.dialog.php", 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nipwali").val(data.NIP);
    $("#namawali").val(data.Nama);
}

function simpanKelas()
{
    const isValid = Vldr.InputText("kelas", "Kelas", 1, 50) &&
                    Vldr.InputText("kapasitas", "Kapasitas", 1, 4) &&
                    Vldr.IsInteger("kapasitas", "Kapasitas") &&
                    Vldr.IsPositive("kapasitas", "Kapasitas") &&
                    Vldr.IsNotEmpty("nipwali", "NIP Wali Kelas");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("kapasitas", "kapasitas");
    qsb.addInput("nipwali", "nipwali");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "kelas.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                btnSimpan.prop("disabled", false);
                btnTutup.prop("disabled", false);

                alert(response[1]);
                return;
            }

            opener.refresh();
            window.close();
        },
        error: function (xhr)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);

            alert(xhr.responseText);
        }
    });
}
