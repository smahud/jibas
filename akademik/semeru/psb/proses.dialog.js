$(document).ready(function ()
{
    $('#proses').focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanProses()
{
    const isValid = Vldr.InputText('proses', 'Nama Proses', 3, 100) &&
                    Vldr.InputText('kodeawalan', 'Kode Awalan', 1, 5) &&
                    Vldr.MaxText('keterangan', 255, 'Keterangan');

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add('op', 'save');
    qsb.addInput('replid', 'replid');
    qsb.addInput('departemen', 'departemen');
    qsb.addInput('proses', 'proses');
    qsb.addInput('kodeawalan', 'kodeawalan');
    qsb.addInput('keterangan', 'keterangan');

    let btnSimpan = $('#btnSimpan');
    let btnTutup = $('#btnTutup');

    btnSimpan.prop('disabled', true);
    btnTutup.prop('disabled', true);

    $.ajax({
        url: 'proses.dialog.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (json)
        {
            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                btnSimpan.prop('disabled', false);
                btnTutup.prop('disabled', false);

                alert(response[1]);
                return;
            }

            let replid = parseInt($("#replid").val());
            if (replid == 0)
                opener.onNewData();
            else 
                opener.onDataChanged();

            window.close();
        },
        error: function (xhr)
        {
            btnSimpan.prop('disabled', false);
            btnTutup.prop('disabled', false);

            alert(xhr.responseText);
        }
    });
}
