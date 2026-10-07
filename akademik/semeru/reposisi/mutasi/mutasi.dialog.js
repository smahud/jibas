var nChecked = 0;

$(document).ready(function()
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

function checkAll()
{
    let nSiswa = $("#nsiswa").val();
    let chkAll = $("#chckall").is(":checked");
    for(let i = 1; i <= nSiswa; i++)
    {
        $("#checkmutasi" + i).prop("checked", chkAll);
    }
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

function prosesMutasi()
{
    if (!validateInput())
        return;

    if (!confirm("PROSES MUTASI " + nChecked + " SISWA?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tglmutasi", "tglmutasi_value");
    qsb.addInput("jenismutasi", "jenismutasi");
    
    let nSiswa = $("#nsiswa").val();
    let no = 0;
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checkmutasi" + i).is(":checked"))
        {
            no++;
            qsb.addInput("nis" + no, "nis" + i);
            qsb.addInput("nama" + no, "nama" + i);
            qsb.addInput("idkelasawal" + no, "idkelasawal" + i);
            qsb.addInput("idtingkatawal" + no, "idtingkatawal" + i);
            qsb.addInput("keterangan" + no, "keterangan" + i);
        }
    }
    qsb.add("nsiswa", no);

    let btProses = $("#btProses");
    let btBatal = $("#btBatal");

    btProses.prop("disabled", true);
    btBatal.prop("disabled", true);
    $("dvLoading").show();

    $.ajax({
        url: "mutasi.dialog.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResp = JSON.parse(json);
            if (lsResp[0] < 0)
            {
                alert(lsResp[1]);
                return;
            }
            
            opener.onMutasiSiswa();

            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btProses.prop("disabled", false);
            btBatal.prop("disabled", false);
            $("dvLoading").hide();
        }
    });
}

function validateInput()
{
    nChecked = 0;

    let nSiswa = $("#nsiswa").val();
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checkmutasi" + i).is(":checked"))
            nChecked++;
    }
    
    if (nChecked == 0)
    {
        alert("Pilih minimal 1 siswa untuk di proses");
        return false;
    }

    return true;
}

function showIuran(no)
{
    let qsb = new QsBuilder();
    qsb.addInput("nis", "nis" + no);
    qsb.addInput("nama", "nama" + no);
    qsb.addInput("departemen", "departemen");
    
    let addr = "../data/keu.riwayat.dialog.php?" + qsb.createQs();
    newWindow(addr,'TunggakanPembayaran','1000','700','resizeable=0,scrollbars=0,status=0,toolbar=0');
}