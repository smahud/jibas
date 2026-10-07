$(document).ready(function ()
{
    $("#tabSiswa").tabs();
});

function prosesMutasi()
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

    let addr = "mutasi.dialog.php?" + qsb.createQs();
    newWindow(addr,'ProsesMutasi','780','600','resizeable=0,scrollbars=0,status=0,toolbar=0');
}

function ubahMutasi(data64)
{
    let qsb = new QsBuilder();
    qsb.add("data64", data64);

    let addr = "mutasi.ubah.dialog.php?" + qsb.createQs();
    newWindow(addr,'UbahMutasi','500','400','resizeable=0,scrollbars=0,status=0,toolbar=0');
    
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs()
    newWindow(url, 'DashboardSiswa', '800', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function detailSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../siswa/siswa.detail.php?'+qsb.createQs(), 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function onMutasiSiswa()
{
    tabsiswa_refresh();

    refreshDaftarMutasi();   
}

function onChangeJenisMutasi()
{
    refreshDaftarMutasi();
}

function refreshDaftarMutasi()
{
    $("#dvTableMutasi").html("");
    $("#dvPageControl").html("");
    
    fetchDaftarMutasi(1);
    
    setTimeout(() => {
        fetchPageControl();
    }, 100);
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idjenismutasi", "jenismutasi");

    let dvPageControl = $("#dvPageControl");
    dvPageControl.html("memuat ..");
    
    $.ajax({
        url: "mutasi.content.ajax.php",
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

function fetchDaftarMutasi(page)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftarmutasi");
    qsb.add("page", page);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idjenismutasi", "jenismutasi");

    let dvTableMutasi = $("#dvTableMutasi");
    dvTableMutasi.html("memuat ..");
    
    $.ajax({
        url: "mutasi.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTableMutasi.html(data).hide().fadeIn(300);
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
    fetchDaftarMutasi(page);
}

function batalMutasi(idMutasi, nis, nama)
{
     if (!confirm("BATALKAN MUTASI SISWA " + nama + " (" + nis + ")?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "batalmutasi");
    qsb.add("idmutasi", idMutasi);
        
    $.ajax({
        url: "mutasi.content.ajax.php",
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

            refreshDaftarMutasi();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}