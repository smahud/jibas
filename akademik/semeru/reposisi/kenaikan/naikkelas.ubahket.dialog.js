$(document).ready(function() {
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpan()
{
    let isValid = Vldr.InputText("keterangan", "Keterangan", 5, 255);
    if (!isValid)
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("idriwayat", "idriwayat");
    qsb.addInput("keterangan", "keterangan");
    
     $.ajax({
        url: "naikkelas.ubahket.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResp = JSON.parse(json);
            if (parseInt(lsResp[0] < 0))
            {
                alert(lsResp[1]);
                return;
            }

            opener.refreshDaftarSiswaKelasTujuan();
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}