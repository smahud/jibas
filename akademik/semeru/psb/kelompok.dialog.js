$(document).ready(function ()
{
    $('#kelompok').focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanKelompok()
{
    const isValid = Vldr.InputText('kelompok', 'Kelompok', 3, 100) &&
                    Vldr.InputText('kapasitas', 'Kapasitas', 1, 4) &&
                    Vldr.IsInteger('kapasitas', 'Kapasitas') &&
                    Vldr.IsPositive('kapasitas', 'Kapasitas') &&
                    Vldr.MaxText('keterangan', 255, 'Keterangan');

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add('op', 'save');
    qsb.addInput('replid', 'replid');
    qsb.addInput('departemen', 'departemen');
    qsb.addInput('idprosespsb', 'idprosespsb');
    qsb.addInput('prosespsb', 'prosespsb');
    qsb.addInput('kelompok', 'kelompok');
    qsb.addInput('kapasitas', 'kapasitas');
    qsb.addInput('keterangan', 'keterangan');

    let btnSimpan = $('#btnSimpan');
    let btnTutup = $('#btnTutup');

    btnSimpan.prop('disabled', true);
    btnTutup.prop('disabled', true);

    $.ajax({
        url: 'kelompok.dialog.ajax.php',
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

            let replid = parseInt($('#replid').val());
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
