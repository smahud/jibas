$( document ).ready(function() 
{
    $("#bagian").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanPegawai()
{
    var isValid = Vldr.InputText("nip", "NIP", 3, 50) &&
                  Vldr.InputText("nama", "Nama Pegawai", 3, 100);

    if (!isValid)
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("bagian", "bagian");
    qsb.addInput("nama", "nama");
    qsb.addInput("nip", "nip");
    qsb.addInput("hp", "hp");
    qsb.addInput("panggilan", "panggilan");
    qsb.addInput("kelamin", "kelamin");
    qsb.addInput("menikah", "menikah");
    qsb.addInput("email", "email");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "pegawai.dialog.ajax.php",
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
            opener.showToastSuccessBottom("Data pegawai berhasil disimpan");
            
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