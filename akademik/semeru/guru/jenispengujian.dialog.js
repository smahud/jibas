$( document ).ready(function() 
{
    $("#jenisujianbaru").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanJenisPengujian()
{
    const isValid = Vldr.InputText("jenisujianbaru", "Jenis Pengujian", 3, 50) &&
                    Vldr.InputText("singkatan", "Singkatan", 1, 10) &&
                    Vldr.InputText("urutan", "Urutan", 1, 3) &&
                    Vldr.IsInteger("urutan", "Urutan") &&
                    Vldr.IsNotNegative("urutan", "Urutan") &&
                    Vldr.IsInRangeInteger("urutan", "Urutan", 1, 255);

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("jenisujianbaru", "jenisujianbaru");
    qsb.addInput("singkatan", "singkatan");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("urutan", "urutan");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "jenispengujian.dialog.ajax.php",
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