$(document).ready(function ()
{
    $("#tabSiswa").tabs();
});

function prosesAlumni()
{
    let lsSiswa = tabsiswa_getSelectedSiswa();
    let nSiswa = lsSiswa.length;
    if (nSiswa == 0)
    {
        alert("Belum ada siswa yang dipilih");
        return;
    }
    
    let lsNis = [];
    for(let i = 0; i < nSiswa; i++)
    {
        let data = JSON.parse(atob(lsSiswa[i]));
        lsNis.push([ data.NIS, data.Nama, data.IdKelas, data.IdTingkat ]);
    }

    let qsb = new QsBuilder()
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.add("nsiswa", nSiswa);
    qsb.add("lsnis64", btoa(JSON.stringify(lsNis)));

    let addr = "alumni.dialog.php?" + qsb.createQs();
    newWindow(addr,'ProsesAlumni','780','600','resizeable=0,scrollbars=0,status=0,toolbar=0');
}

function ubahKeterangan(idRiwayat, keterangan, nis, nama)
{
    let qsb = new QsBuilder();
    qsb.add("idriwayat", idRiwayat);
    qsb.add("nis", nis);
    qsb.add("nama", nama);
    qsb.add("keterangan", keterangan);

    let addr = "lulus.ubahket.dialog.php?" + qsb.createQs();
    newWindow(addr,'UbahKeterangan','430','250','resizeable=0,scrollbars=0,status=0,toolbar=0');
    
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

    document.location.href = "../../dashboard/dashboard.php?" + qsb.createQs();
}

function detailSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../siswa/siswa.detail.php?'+qsb.createQs(), 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function onAlumniSiswa()
{
    tabsiswa_refresh();

    refreshDaftarAlumni();   
}

function refreshDaftarAlumni()
{
    $("#dvTableAlumni").html("");
    $("#dvPageControl").html("");

    function acceptTahunLulus()
    {
        fetchDaftarAlumni(1);
        
        setTimeout(() => {
            fetchPageControl();
        }, 100);
    }

    fetchTahunLulus(acceptTahunLulus)
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunselected", "tahunlulus");

    let dvPageControl = $("#dvPageControl");
    dvPageControl.html("memuat ..");
    
    $.ajax({
        url: "alumni.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvPageControl.html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchDaftarAlumni(page)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftaralumni");
    qsb.add("page", page);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunselected", "tahunlulus");

    let dvTableAlumni = $("#dvTableAlumni");
    dvTableAlumni.html("memuat ..");
    
    $.ajax({
        url: "alumni.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTableAlumni.html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchTahunLulus(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "tahunlulus");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunselected", "tahunlulus");

    let spTahunKelulusan = $("#spTahunKelulusan");
    spTahunKelulusan.html("memuat ..");
    
    $.ajax({
        url: "alumni.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTahunKelulusan.html(data).hide().fadeIn(300);
            
            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onPrevPage()
{
    let page = parseInt($("#page").val());
    if (page === 1)
        return;

    $("#page").val(page - 1);
    onChangePage();    
}

function onNextPage()
{
    let page = parseInt($("#page").val());
    let npage = parseInt($("#npage").val());

    if (page === npage)
        return;

    $("#page").val(page + 1);
    onChangePage();
}

function onChangePage()
{
    let page = $("#page").val();
    fetchDaftarAlumni(page);
}

function batalAlumni(idAlumni, nis, nama)
{
     if (!confirm("BATALKAN PENDATAAN ALUMNI SISWA " + nama + " (" + nis + ")?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "batalalumni");
    qsb.add("idalumni", idAlumni);
        
    $.ajax({
        url: "alumni.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResp = JSON.parse(json);
            if (parseInt(lsResp[0]) < 0)
            {
                alert(lsResp[1]);
                return;
            }

            tabsiswa_refresh();

            refreshDaftarAlumni();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}