$( document ).ready(function() 
{
    $("#kode").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanKelompokPelajaran()
{
    const isValid = Vldr.InputText("kode", "Kode Kelompok Pelajaran", 1, 10) &&
                    Vldr.InputText("nama", "Nama Kelompok Pelajaran", 1, 50) &&
                    Vldr.IsInteger("urutan", "Urutan") &&
                    Vldr.IsNotNegative("urutan", "Urutan") &&
                    Vldr.IsNotZero("urutan", "Urutan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("kode", "kode");
    qsb.addInput("nama", "nama");
    qsb.addInput("urutan", "urutan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "kelompokpelajaran.dialog.ajax.php",
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