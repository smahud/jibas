$(document).ready(function() 
{
    $("#nilai").focus();

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

function validateInput()
{
    let nilai = $.trim($("#nilai").val());
    if (nilai.length == 0) 
    {
        alert("Nilai siswa belum ditentukan")
        $("#nilai").focus();
        return false;
    }

    if (isNaN(nilai))
    {
        alert("Nilai harus berupa angka")
        $("#nilai").focus();
        return false;
    }

    let num = parseFloat(nilai);
    if (num < 0 || num > 100)
    {
        alert("Nilai harus diantara 0 - 100")
        $("#nilai").focus();
        return false;
    }

    let alasan = $.trim($("#alasan").val());
    if (alasan.length == 0) 
    {
        alert("Alasan perubahan data belum ditentukan")
        $("#alasan").focus();
        return false;
    }

    return true
}

function simpan()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah benar? "))
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.addInput("idujian", "idujian");
    qsb.addInput("idnilainau", "idnilainau");
    qsb.addInput("nilaiasli", "nilaiasli");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("nilai", "nilai");
    qsb.addInput("keterangan", "keterangan");
    qsb.addInput("alasan", "alasan");
    qsb.add("op", "simpan");

    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.ubahnau.dialog.ajax.php",
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

    