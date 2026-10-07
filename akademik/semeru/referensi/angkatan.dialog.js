$( document ).ready(function() 
{
    $("#angkatan").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanAngkatan()
{
    const isValid = Vldr.InputText("angkatan", "Angkatan", 1, 50);
    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("angkatan", "angkatan");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "angkatan.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                alert(response[1]);
                return;
            }

            let replid = parseInt($("#replid").val());
            if (replid === 0)
                opener.onNewData();
            else
                opener.onDataChanged();

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