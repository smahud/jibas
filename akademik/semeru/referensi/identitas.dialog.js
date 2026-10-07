$(document).ready(function()
{
    $("#nama").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

function simpanIdentitas()
{
    let isValid = Vldr.InputText("nama", "Nama Sekolah", 3, 255);
    if (!isValid)
        return;

    let formData = new FormData();
    formData.append("op", "simpan");
    formData.append("replid", $("#replid").val());
    formData.append("departemen", $("#departemen").val());
    formData.append("nama", $("#nama").val());
    formData.append("alamat1", $("#alamat1").val());
    formData.append("tlp1", $("#tlp1").val());
    formData.append("tlp2", $("#tlp2").val());
    formData.append("fax1", $("#fax1").val());
    formData.append("alamat2", $("#alamat2").val());
    formData.append("tlp3", $("#tlp3").val());
    formData.append("tlp4", $("#tlp4").val());
    formData.append("fax2", $("#fax2").val());
    formData.append("situs", $("#situs").val());
    formData.append("email", $("#email").val());

    if ($.trim($("#logo")).length === 0)
    {
        formData.append("haslogo", 0);
    }
    else
    {
        formData.append("haslogo", 1);
        formData.append("logo", $("#logo")[0].files[0]);
    }

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "identitas.dialog.ajax.php",
        method: "POST",
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            opener.refresh();
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function (xhr)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);
        }
    })
}