$(document).ready(function ()
{
    if ($("#tabCalonSiswa").length)
        $("#tabCalonSiswa").tabs();
   
    tabcsiswa_setAcceptResult(acceptCalonSiswa);
});

function acceptCalonSiswa(kelompok, json64)
{
    if ($("#angkatan option").length == 0)
    {
        showToastErrorTop("Belum ada data angkatan");
        return;
    }

    if ($("#tahunajaran option").length == 0)
    {
        showToastErrorTop("Belum ada data tahun ajaran");
        return;
    }

    if ($("#tingkat option").length == 0)
    {
        showToastErrorTop("Belum ada data tingkat");
        return;
    }

    if ($("#kelas option").length == 0)
    {
        showToastErrorTop("Belum ada data kelas");
        return;
    }

    let b64 = $("#kelas").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelasTujuan = lsKey[0];
    let kelasTujuan = lsKey[1];
    let kapasitas = parseInt(lsKey[2]);
    let terisi = parseInt(lsKey[3]);

    if (terisi + 1 >= kapasitas)
    {
        showToastErrorTop("Kelas tujuan sudah penuh");
        return;
    }

    let data = JSON.parse(atob(json64));

    var qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.add("tahunajaran", $("#tahunajaran option:selected").text());
    qsb.addInput("idangkatan", "angkatan");
    qsb.add("angkatan", $("#angkatan option:selected").text());    
    qsb.addInput("idtingkat", "tingkat");
    qsb.add("tingkat", $("#tingkat option:selected").text());    
    qsb.add("idkelas", idKelasTujuan);
    qsb.add("kelas", kelasTujuan);
    qsb.add("nopendaftaran", data.NIC);
    qsb.add("nama", data.Nama);
    qsb.add("replid", data.Replid);

    newWindow('penempatan.dialog.php?' + qsb.createQs(), 'PenempatanCalonSiswa', '450', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function fetchDaftarSiswa()
{
    if ($("#angkatan option").length === 0)
        return;

    if ($("#kelas option").length === 0)
        return;

    let b64 = $("#kelas").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelasTujuan = lsKey[0];

    let qsb = new QsBuilder();
    qsb.add("op", "fetchdaftarsiswa");
    qsb.add("idkelas", idKelasTujuan);
    qsb.addInput("idangkatan", "angkatan");

    $("#dvLoading").show();
    $("#dvTableSiswaKelasTujuan").html("memuat ..");
    
    $.ajax({
        url: "penempatan.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvTableSiswaKelasTujuan").html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function ()    
        {
            $("#dvLoading").hide();
        }
    })
}

function onChangeAngkatan()
{
    fetchDaftarSiswa();
}

function onChangeKelas()
{
    fetchDaftarSiswa();
}

function onChangeTahunAjaran()
{
    $("#dvTableSiswaKelasTujuan").html("");
    $("#spKelas").html("");

    function acceptKelas()
    {
        fetchDaftarSiswa();
    }

    fetchKelas(acceptKelas);
}

function onChangeTingkat()
{
    onChangeTahunAjaran();
}

function fetchKelas(callback)
{
    if ($("#tingkat option").length === 0)
        return;

    if ($("#tahunajaran option").length === 0)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "fetchkelas");
    qsb.addInput("idtingkat", "tingkat");
    qsb.addInput("idtahunajaran", "tahunajaran");

    $("#dvLoading").show();
    $("#spKelas").html("memuat ..");
    
    $.ajax({
        url: "penempatan.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#spKelas").html(data).hide().fadeIn(300);

            callback();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function ()    
        {
            $("#dvLoading").hide();
        }
    })
}

function onSuccess(replid)
{
    showToastSuccessTop("Berhasil menempatkan siswa");

    /*
    if ($("#spTerima" + replid).length)
    {
        $("#spTerima" + replid).remove();
    }
    */

    if ($("#trCalonSiswa" + replid).length)
        $("#trCalonSiswa" + replid).fadeOut(300, function () { $("#trCalonSiswa" + replid).remove(); });
    
    fetchKelas(function () {
        fetchDaftarSiswa();
    });
}

function batalTerima(replid, nis)
{
    if (!confirm("Batalkan penerimaan siswa ini?\n\n Siswa akan dikembalikan ke data calon siswa"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "batalterima");
    qsb.add("replid", replid);
    qsb.add("nis", nis);
    
    $("#dvLoading").show();

    $.ajax({
        url: "penempatan.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            let ls = $.parseJSON(data);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }
            
            showToastSuccessTop("Berhasil membatalkan penerimaan siswa");

            if ($("#trSiswa" + replid).length)
                $("#trSiswa" + replid).fadeOut(300, function () { $("#trSiswa" + replid).remove(); });
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function ()    
        {
            $("#dvLoading").hide();
        }
    })
}

function showInfoCalonSiswa(nic)
{
    var qsb = new QsBuilder();
    qsb.add("nic", nic);

    newWindow('../library/infocalonsiswa.dialog.php?'+qsb.createQs(), 'InformasiCalonSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function showInfoSiswa(nis)
{
    var qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../library/infosiswa.dialog.php?'+qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');

}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')

}