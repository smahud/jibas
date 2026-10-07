$(document).ready(function() 
{
    $("#kategori").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });

})

function simpan()
{
    let isValid = Vldr.InputText("kategori", "Kategori", 5, 100) &&
                  Vldr.IsInteger("urutan", "Urutan") &&
                  Vldr.IsNotNegative("urutan", "Urutan") &&
                  Vldr.IsNotZero("urutan", "Urutan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("kategori", "kategori");
    qsb.addInput("urutan", "urutan");
    qsb.addInput("keterangan", "keterangan");

    $.ajax({
        url: "kategorijadwal.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
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
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        }
    })
}