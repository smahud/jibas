$(document).ready(function() {
    $("#kode").focus(); 

    if ($("#tableSiswa").length)
        Tables('tableSiswa', 1, 0);

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

function onCheckAll()
{
    let nSiswa = parseInt($("#nsiswa").val());
    if (nSiswa == 0)
        return;

    let checked = $("#cekall").is(":checked");
    for (let i = 1; i <= nSiswa; i++)
    {
        $("#cek" + i).prop("checked", checked);
    }
}

function applySelected()
{
    let nilai = $.trim($("#nilaisel").val());
    let keterangan = $.trim($("#keterangansel").val());

    if (nilai.length == 0)
    {
        alert("Nilai siswa belum ditentukan");
        $("#nilaisel").focus();
        return;
    }

    if (isNaN(nilai))
    {
        alert("Nilai harus berupa angka");
        $("#nilaisel").focus();
        return;
    }

    let num = parseFloat(nilai);
    if (num < 0 || num > 100)
    {
        alert("Nilai harus diantara 0 - 100");
        $("#nilaisel").focus();
        return;
    }

    let nSiswa = parseInt($("#nsiswa").val());
    if (nSiswa == 0)
    {
        alert("Belum ada data siswa")
        return;
    }

    for (let i = 1; i <= nSiswa; i++)
    {
        let checked = $("#cek" + i).is(":checked");
        if (checked)
        {
            $("#nilai" + i).val(nilai);
            $("#keterangan" + i).val(keterangan);
        }
    }
}

function clearAll()
{
    if (!confirm("Hapus semua nilai dan keterangan"))
        return;
    
    let nSiswa = parseInt($("#nsiswa").val());
    if (nSiswa == 0)
        return;

    for (let i = 1; i <= nSiswa; i++)
    {
        $("#nilai" + i).val("");
        $("#keterangan" + i).val("");
    }
}

function validateInput()
{
    let materi = $.trim($("#materi").val());
    if (materi.length == 0) 
    {
        alert("Materi belum ditentukan")
        $("#materi").focus();
        return false;
    }

    let nSiswa = parseInt($("#nsiswa").val())
    if (nSiswa == 0)
    {
        alert("Belum ada data siswa")
        return false;
    }

    for (let i = 1; i <= nSiswa; i++) 
    {
        let nilai = $.trim($("#nilai" + i).val());
        if (nilai.length == 0)
        {
            alert("Nilai siswa belum ditentukan")
            $("#nilai" + i).focus();
            return false;
        }

        if (isNaN(nilai))
        {
            alert("Nilai harus berupa angka")
            $("#nilai" + i).focus();
            return false;
        }

        let num = parseFloat(nilai);
        if (num < 0 || num > 100)
        {
            alert("Nilai harus diantara 0 - 100")
            $("#nilai" + i).focus();
            return false;
        }
    }

    return true
}

function simpan()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    qsb.addInput("kode", "kode");
    qsb.addInput("tanggal", "tanggal_value");
    qsb.addInput("materi", "materi");
    qsb.addInput("rpp", "rpp");
    qsb.addInput("nsiswa", "nsiswa");

    let nSiswa = parseInt($("#nsiswa").val())
    for (let i = 1; i <= nSiswa; i++) 
    {
        let nis = $.trim($("#nis" + i).val());
        let nilai = $.trim($("#nilai" + i).val());
        let keterangan = $.trim($("#keterangan" + i).val());

        qsb.add("nis" + i, nis);
        qsb.add("nilai" + i, nilai);
        qsb.add("keterangan" + i, keterangan);
    }
    
    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.nilai.dialog.ajax.php",
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

function showBatchInput()
{
    $("#spBatchInput").hide();
    $("#dvBatchInput").fadeIn(500);
    $(".tdcheck").fadeIn(500);
    $("#nilaisel").focus();
}

function hideBatchInput()
{
    $("#spBatchInput").show();
    $("#dvBatchInput").hide();
    $(".tdcheck").hide();
}