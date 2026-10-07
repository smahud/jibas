$(document).ready(function()
{
    if ($("#tableSiswaAsal").length)   
        Tables('tableSiswaAsal', 1, 0);

    if ($("#tableSiswaTujuan").length)   
        Tables('tableSiswaTujuan', 1, 0);
});

function refreshKelasTujuan()
{
    if ($("#kelastujuan option").length <= 0)
        return;

    let b64 = $("#kelastujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelasTujuan = lsKey[0];

    let qsb = new QsBuilder();
    qsb.add("op", "refreshkelastujuan");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idkelas", "idkelasasal");
    qsb.add("idkelastujuan", idKelasTujuan);

    $("#dvLoading").show();
    $("#dvSelectKelasTujuan").html("memuat ..");
    $("#dvTableSiswaKelasTujuan").html("memuat ..");

    $.ajax({
        url: "pindahkelas.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvSelectKelasTujuan").html(data).hide().fadeIn(300);

            fetchTableSiswaKelasTujuan();
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

function refreshTableSiswaKelasAsal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "siswakelasasal");
    qsb.addInput("idkelas", "idkelasasal");

    $("#dvLoading").show();
    $("#dvTableSiswaKelasAsal").html("memuat ..");

    $.ajax({
        url: "pindahkelas.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvTableSiswaKelasAsal").html(data).hide().fadeIn(300);

            if ($("#tableSiswaAsal").length)   
                Tables('tableSiswaAsal', 1, 0);
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

function fetchTableSiswaKelasTujuan()
{
    if ($("#kelastujuan option").length <= 0)
        return;

    let b64 = $("#kelastujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelasTujuan = lsKey[0];

    let qsb = new QsBuilder();
    qsb.add("op", "siswakelastujuan");
    qsb.add("idkelastujuan", idKelasTujuan);

    $("#dvLoading").show();
    $("#dvTableSiswaKelasTujuan").html("memuat ..");

    $.ajax({
        url: "pindahkelas.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvTableSiswaKelasTujuan").html(data).hide().fadeIn(300);

            if ($("#tableSiswaTujuan").length)   
                Tables('tableSiswaTujuan', 1, 0);
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

function onChangeKelasTujuan()
{
    fetchTableSiswaKelasTujuan();
}

function pindahSiswa()
{
    let nData = parseInt($("#ndata").val());
    if (nData == 0)
        return;

    let lsNis = [];
    for (let i = 1; i <= nData; i++)
    {
        if ($(`#ck${i}`).is(":checked"))
        {
            lsNis.push($(`#nis${i}`).val());
        }
    }

    if (lsNis.length == 0)
    {
        alert("belum ada siswa yang dipilih");
        return;
    }

    let b64 = $("#kelastujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelasTujuan = lsKey[0];
    let kapasitas = parseInt(lsKey[1]);
    let terisi = parseInt(lsKey[2]);

    if (lsNis.length + terisi > kapasitas)
    {
        alert("kapasitas kelas tidak mencukupi");
        return;
    }

    if (!confirm("Pindahkan siswa terpilih?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "pindahsiswa");
    qsb.add("idkelastujuan", idKelasTujuan);
    qsb.add("keterangan", $("#keterangan").val());
    qsb.add("jsonnis64", btoa(JSON.stringify(lsNis)));

    $("#dvLoading").show();

    $.ajax({
        url: "pindahkelas.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }

            showToastSuccessTop("Berhasil memindahkan siswa");
            refreshKelasTujuan();
            refreshTableSiswaKelasAsal();
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

function batalPindah(nis, idkelas)
{
    if (!confirm("Batalkan pemindahan siswa?\nSiswa akan dikembalikan ke kelas semula"))
        return

    let qsb = new QsBuilder();
    qsb.add("op", "batalpindah");
    qsb.add("nis", nis);
    qsb.add("idkelas", idkelas);

    $("#dvLoading").show();
    //$("#dvTableSiswaKelasTujuan").html("memuat ..");

    $.ajax({
        url: "pindahkelas.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }

            showToastSuccessTop("Berhasil membatalkan pemindahan siswa");
            refreshKelasTujuan();
            refreshTableSiswaKelasAsal();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })

}

function showInfoSiswa(nis)
{
    var qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}