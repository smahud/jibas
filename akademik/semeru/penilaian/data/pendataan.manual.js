$(document).ready(function() {
    if ($("#tableDaftar").length)
        Tables("tableDaftar", 1, 0);

    if ($("#nilainau1").length) 
        $("#nilainau1").focus();
});

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');

    //document.location.href = "../../dashboard/dashboard.php?replid=" + replid;
}

function detailSiswa(replid) 
{
	newWindow('../../siswa/siswa.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function validateInput()
{
    let nSiswa = parseInt($("#nsiswa").val())
    if (nSiswa == 0)
    {
        alert("Belum ada data siswa")
        return false;
    }

    for (let i = 1; i <= nSiswa; i++) 
    {
        let nilai = $.trim($("#nilainau" + i).val());
        if (nilai.length == 0)
        {
            alert("Nilai siswa belum ditentukan")
            $("#nilainau" + i).focus();
            return false;
        }

        if (isNaN(nilai))
        {
            alert("Nilai harus berupa angka")
            $("#nilainau" + i).focus();
            return false;
        }

        let num = parseFloat(nilai);
        if (num < 0 || num > 100)
        {
            alert("Nilai harus diantara 0 - 100")
            $("#nilainau" + i).focus();
            return false;
        }
    }

    return true
}

function simpanNilaiAkhir()
{
    if (!validateInput())
        return;
    
    if (!confirm("Data sudah benar?"))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("nsiswa", "nsiswa");

    let nSiswa = parseInt($("#nsiswa").val())
    for (let i = 1; i <= nSiswa; i++) 
    {
        qsb.addInput("idnilainau" + i, "idnilainau" + i);
        qsb.addInput("nilainau" + i, "nilainau" + i);
        qsb.addInput("nis" + i, "nis" + i);
    }
    
    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.manual.ajax.php",
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

            refresh();
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

function refresh()
{
    document.location.reload();
}