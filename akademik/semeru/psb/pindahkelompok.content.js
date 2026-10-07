$(document).ready(function()
{
    if ($("#tableCalonSiswaAsal").length)   
        Tables('tableCalonSiswaAsal', 1, 0);

    if ($("#tableCalonSiswaTujuan").length)   
        Tables('tableCalonSiswaTujuan', 1, 0);
});

function refreshKelompokTujuan()
{
    if ($("#kelompoktujuan option").length <= 0)
        return;

    let b64 = $("#kelompoktujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelompokTujuan = lsKey[0];

    let qsb = new QsBuilder();
    qsb.add("op", "refreshkelompoktujuan");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.add("idkelompoktujuan", idKelompokTujuan);

    $("#dvLoading").show();
    $("#dvSelectKelompokTujuan").html("memuat ..");
    $("#dvTableCalonSiswaKelompokTujuan").html("memuat ..");

    $.ajax({
        url: "pindahkelompok.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvSelectKelompokTujuan").html(data).hide().fadeIn(300);

            fetchTableCalonSiswaKelompokTujuan();
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

function refreshTableCalonSiswaAsal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "calonsiswakelompokasal");
    qsb.addInput("idkelompok", "idkelompok");

    $("#dvLoading").show();
    $("#dvTableCalonSiswaKelompokAsal").html("memuat ..");

    $.ajax({
        url: "pindahkelompok.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvTableCalonSiswaKelompokAsal").html(data).hide().fadeIn(300);

            if ($("#tableCalonSiswaAsal").length)   
                Tables('tableCalonSiswaAsal', 1, 0);
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

function fetchTableCalonSiswaKelompokTujuan()
{
    if ($("#kelompoktujuan option").length <= 0)
        return;

    let b64 = $("#kelompoktujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelompokTujuan = lsKey[0];

    let qsb = new QsBuilder();
    qsb.add("op", "calonsiswakelompoktujuan");
    qsb.add("idkelompoktujuan", idKelompokTujuan);

    $("#dvLoading").show();
    $("#dvTableCalonSiswaKelompokTujuan").html("memuat ..");

    $.ajax({
        url: "pindahkelompok.content.ajax.php?",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $("#dvTableCalonSiswaKelompokTujuan").html(data).hide().fadeIn(300);

            if ($("#tableCalonSiswaTujuan").length)   
                Tables('tableCalonSiswaTujuan', 1, 0);
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

function onChangeKelompokTujuan()
{
    fetchTableCalonSiswaKelompokTujuan();
}

function pindahCalonSiswa()
{
    let nData = parseInt($("#ndata").val());
    if (nData == 0)
        return;

    let lsNic = [];
    for (let i = 1; i <= nData; i++)
    {
        if ($(`#ck${i}`).is(":checked"))
        {
            lsNic.push($(`#nopendaftaran${i}`).val());
        }
    }

    if (lsNic.length == 0)
    {
        alert("belum ada calon siswa yang dipilih");
        return;
    }

    let b64 = $("#kelompoktujuan").val();
    let lsKey = JSON.parse(atob(b64));        
    let idKelompokTujuan = lsKey[0];
    let kapasitas = parseInt(lsKey[1]);
    let terisi = parseInt(lsKey[2]);

    if (lsNic.length + terisi > kapasitas)
    {
        alert("kapasitas kelompok tidak mencukupi");
        return;
    }

    if (!confirm("Pindahkan calon siswa terpilih?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "pindahcalonsiswa");
    qsb.add("idkelompoktujuan", idKelompokTujuan);
    qsb.add("keterangan", $("#keterangan").val());
    qsb.add("jsonnic64", btoa(JSON.stringify(lsNic)));

    $("#dvLoading").show();

    $.ajax({
        url: "pindahkelompok.content.ajax.php?",
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

            showToastSuccessTop("Berhasil memindahkan calon siswa");
            refreshKelompokTujuan();
            refreshTableCalonSiswaAsal();
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

function dashboardCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../dashboard/dashboardcs.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function profilCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../library/infocalonsiswa.dialog.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '620','520', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}