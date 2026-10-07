$(document).ready(function() 
{
    $("#kode").focus();

    initUi();
});

function initUi()
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
}

function showPilihTanggal()
{
    var selDate = $("#tanggal_value").val();

    $("#tanggal").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tanggal_value").val(date);
            $("#tanggal").val(dateutil_formatInaDate(date));
        }
    }).focus();
};

function validateInput()
{
    let materi = $.trim($("#materi").val());
    if (materi.length == 0) 
    {
        alert("Materi belum ditentukan")
        $("#materi").focus();
        return false;
    }

    return true;
}


function simpan()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idujian", "idujian");
    qsb.addInput("kode", "kode");
    qsb.addInput("tanggal", "tanggal_value");
    qsb.addInput("materi", "materi");
    qsb.addInput("rpp", "rpp");

    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.ubahinfo.dialog.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorTop(ls[1])
                alert(ls[1]);
                return;
            }

            opener.refresh();
            window.close(); 
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btTutup.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
}