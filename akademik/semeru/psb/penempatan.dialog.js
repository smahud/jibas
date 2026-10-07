$(document).ready(function() {
    $("#nis").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

function setGui(state)
{
    switch(state)
    {
        case "wait":
            $("#btnSimpan").prop("disabled", true);
            $("#btnBatal").prop("disabled", true);
            $("#dvLoading").show();
            break;
        case "ready":
            $("#btnSimpan").prop("disabled", false);
            $("#btnBatal").prop("disabled", false);
            $("#dvLoading").hide();
            break;
    }
}

function simpan()
{
    let isValid = Vldr.IsNotEmpty("nis", "NIS") &&
                  confirm("Data sudah benar?");
                  
    if (!isValid)                  
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idangkatan", "idangkatan");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("nopendaftaran", "nopendaftaran");
    qsb.addInput("nis", "nis");
    qsb.addInput("keterangan", "keterangan");

    setGui("wait");

    $.ajax({
        url: "penempatan.dialog.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = $.parseJSON(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            let replid = $("#replid").val();
            window.opener.onSuccess(replid);
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            setGui("ready");
        }
    });
}