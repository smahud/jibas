$(document).ready (function () {   
    $("#kode").focus();
});

simpanBiayaLayanan = function () {

    var idServiceFee = $("#idservicefee").val();
    var dept = $.trim($("#dept").val());
    if (dept.length === 0)
    {
        alert("Data departemen tidak tersedia");
        return;
    }

    var kode = $.trim($("#kode").val());
    if (kode.length < 3)
    {
        alert("Kode biaya layanan minimal 3 karater");
        $("#kode").focus();
        return;
    }

    var nama = $.trim($("#nama").val());
    if (nama.length < 5)
    {
        alert("Nama biaya layanan minimal 5 karater");
        $("#nama").focus();
        return;
    }

    var biaya = rupiahToNumber($("#biaya").val());
    if (isNaN(parseInt(biaya)))
    {
        alert("Baya layanan tidak benar");
        $("#biaya").focus();
        return;
    }

    var rekKas = $.trim($('#rekkas').val());
    if (rekKas.length === 0)
    {
        alert("Belum ada data rekening kas");
        $("#inforekkas").focus();
        return;
    }

    var rekPendapatan = $.trim($("#rekpendapatan").val());
    if (rekPendapatan.length === 0)
    {
        alert("Belum ada data rekening pendapatan");
        $("#infoekpendapatan").focus();
        return;
    }

    var keterangan = $.trim($("#keterangan").val());

    var request = new QsBuilder();
    request.add("op", "3276897493284732894");
    request.add("idservicefee", idServiceFee);
    request.add("dept", dept);
    request.add("kode", kode);
    request.add("nama", nama);
    request.add("biaya", biaya);
    request.add("keterangan", keterangan);
    request.add("rekkas", $("#rekkas").val());
    request.add("rekpendapatan", $("#rekpendapatan").val());
    var qs = request.createQs();

    $.ajax({
        url: "servicefee.dialog.ajax.php",
        method: "POST",
        data: qs,
        success: function (json)
        {
            var result = $.parseJSON(json);
            if (parseInt(result[0]) < 0)
            {
                showToast(result[1], 2000, 'error', 'top');
                return;
            }

            showToast("Berhasil", 2000, 'success', 'top');
            sendToAppServer("datasync");

            opener.location.reload();
            window.close();
        },
        error: function(xhr)
        {
            showToast(xhr.responseText, 2000, 'error', 'top');
        }
    })
};

showRekAkunDialog = function(kategori, subKategori)
{
    let qsb = new QsBuilder();
    qsb.add("kategori", kategori);
    qsb.add("subkategori", subKategori);

    let addr = '../library/rekakun.dialog.php?' + qsb.createQs();
    newWindow(addr, 'RekAkunDialog3', '760', '560', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

acceptRekAkunDialog = function(kategori, subKategori, kodeRek, namaRek)
{
    if (kategori === "HARTA")
    {
        $("#inforekkas").val(kodeRek + " " + namaRek);
        $("#rekkas").val(kodeRek);
    }
    else if (kategori === "PENDAPATAN")
    {
        $("#inforekpendapatan").val(kodeRek + " " + namaRek);
        $("#rekpendapatan").val(kodeRek);
    }
};