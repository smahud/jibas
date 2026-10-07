$( document ).ready(function() 
{
    $("#nama").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanPelajaran()
{
    let sifatVal = $("input[name='sifat']:checked").val();
    
    const isValid = Vldr.InputText("nama", "Nama Pelajaran", 5, 50) &&
                    Vldr.InputText("kode", "Singkatan", 1, 4) &&
                    Vldr.IsNotEmptyValue(sifatVal, "Sifat") &&
                    Vldr.InputText("urutan", "Urutan", 1, 4) &&
                    Vldr.IsInteger("urutan", "Urutan") &&
                    Vldr.IsNotNegative("urutan", "Urutan") &&
                    Vldr.MaxText("keterangan", "Keterangan", 255);

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kode", "kode");
    qsb.addInput("nama", "nama");
    qsb.add("sifat", sifatVal);
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("urutan", "urutan");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "pelajaran.dialog.ajax.php",
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