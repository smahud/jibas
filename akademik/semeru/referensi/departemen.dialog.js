$( document ).ready(function() 
{
    $("#departemen").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function cariKepsek()
{
    newWindow("../library/daftarpegawai.dialog.php", 400, 550, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nipkepsek").val(data.NIP);
    $("#namakepsek").val(data.Nama);
}

function simpanDepartemen()
{
    const isValid = Vldr.InputText("departemen", "Departemen", 3, 50) &&
                    Vldr.IsNotEmpty("nipkepsek", "NIP Kepala Sekolah") &&
                    Vldr.IsInteger("urutan", "Urutan") &&
                    Vldr.IsNotNegative("urutan", "Urutan") &&
                    Vldr.IsNotZero("urutan", "Urutan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nipkepsek", "nipkepsek");
    qsb.addInput("urutan", "urutan");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "departemen.dialog.ajax.php",
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