$(document).ready(function ()
{
    $("#tahunajaran").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });

    Calendar.setup({
        inputField: "tglmulai",
        ifFormat: "%d-%m-%Y",
        button: "btntglmulai"
    });

    Calendar.setup({
        inputField: "tglakhir",
        ifFormat: "%d-%m-%Y",
        button: "btntglakhir"
    });
});

function simpanTahunAjaran()
{
    const isValid = Vldr.InputText("tahunajaran", "Tahun Ajaran", 3, 50) &&
                    Vldr.IsNotEmpty("tglmulai", "Tanggal Mulai") &&
                    Vldr.IsNotEmpty("tglakhir", "Tanggal Akhir") &&
                    Vldr.IsNotEmpty("departemen", "Departemen");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tglmulai", "tglmulai_value");
    qsb.addInput("tglakhir", "tglakhir_value");
    qsb.addInput("keterangan", "keterangan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "tahunajaran.dialog.ajax.php",
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

function showPilihTglMulai()
{
    var selDate = $("#tglmulai_value").val();

    $("#tglmulai").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tglmulai_value").val(date);
            $("#tglmulai").val(dateutil_formatInaDate(date));
        }
    }).focus();
};

function showPilihTglAkhir()
{
    var selDate = $("#tglakhir_value").val();

    $("#tglakhir").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tglakhir_value").val(date);
            $("#tglakhir").val(dateutil_formatInaDate(date));
        }
    }).focus();
};
