$( document ).ready(function() 
{
    $("#kalender").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpan()
{
    let isValid = Vldr.InputText("kalender", "Kalender Akademik", 5, 50);
    if (!isValid)
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("kalender", "kalender");
    qsb.addInput("keterangan", "keterangan");

    $.ajax({
        url: "kalender.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            $("#dvLoading").hide();

            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            opener.refresh();
            window.close();
        },
        error: function(xhr, status, error)
        {
            $("#dvLoading").hide();

            alert(xhr.responseText);
        }
    })
}