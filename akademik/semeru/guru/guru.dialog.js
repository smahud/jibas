$( document ).ready(function() 
{
    $("#status").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanGuru()
{
    const isValid = Vldr.IsNotEmpty("nip", "NIP Guru") &&
                    Vldr.MaxText("keterangan", 255, "Keterangan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("nipguru", "nip");
    qsb.addInput("namaguru", "nama");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("status", "status");
    qsb.addInput("keterangan", "keterangan");
    qsb.addInput("departemen", "departemen");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "guru.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);

            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
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

function cariPegawai()
{
    newWindow("../library/daftarpegawai.dialog.php", 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nip").val(data.NIP);
    $("#nama").val(data.Nama);
}

function showStatusGuru()
{
    newWindow('../referensi/statusguru.dialog.php?mode=manage', 'StatusGuru', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refreshStatusGuru()
{
    let spStatusGuru = $("#spStatusGuru");
    spStatusGuru.html("memuat ..");

    $.ajax({
        url: "guru.dialog.ajax.php",
        method: "POST",
        data: "op=refreshstatusguru",
        success: function(data)
        {
            spStatusGuru.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}