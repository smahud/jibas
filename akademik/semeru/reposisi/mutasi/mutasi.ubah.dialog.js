$(document).ready(function()
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

function simpanMutasi()
{
    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idmutasi", "idmutasi");
    qsb.addInput("tglmutasi", "tglmutasi_value");
    qsb.addInput("idjenismutasi", "jenismutasi");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btSimpan");
    let btnTutup = $("#btTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "mutasi.ubah.dialog.ajax.php",
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

            opener.refreshDaftarMutasi();

            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function ()
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);
        }
    });
}

function showPilihTglMutasi()
{
    var selDate = $("#tglmutasi_value").val();

    $("#tglmutasi").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tglmutasi_value").val(date);
            $("#tglmutasi").val(dateutil_formatInaDate(date));
        }
    }).focus();
};