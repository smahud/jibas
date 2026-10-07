$(document).ready(function ()
{
    $("#tabSiswa").tabs();
});

function lulusSiswa()
{
    let lsSiswa = tabsiswa_getSelectedSiswa();
    let nSiswa = lsSiswa.length;
    if (nSiswa == 0)
    {
        alert("Belum ada siswa yang dipilih");
        return;
    }

    if ($("#kelastujuan option").length == 0)
    {
        alert("Kelas tujuan belum tersedia");
        $("#kelastujuan").focus();
        return;
    }

    let $optKelasTujuan = $("#kelastujuan option:selected");
    let dataKelasTujuan = JSON.parse(atob($optKelasTujuan.val()));
    let idKelasTujuan = dataKelasTujuan[0];
    let kelasTujuan = dataKelasTujuan[1];
    let nKelasTujuan = dataKelasTujuan[2];
    let nTerisi = dataKelasTujuan[3];

    if (nSiswa > (nKelasTujuan - nTerisi))
    {
        alert("Kapasitas kelas tujuan tidak mencukupi, tersisa " + (nKelasTujuan - nTerisi) + " siswa lagi");
        return;
    }

    let lsNis = [];
    for(let i = 0; i < nSiswa; i++)
    {
        let data = JSON.parse(atob(lsSiswa[i]));
        lsNis.push([ data.NIS, data.Nama, data.IdKelas ]);
    }

    let qsb = new QsBuilder()
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.add("nsiswa", nSiswa);
    qsb.add("lsnis64", btoa(JSON.stringify(lsNis)));
    qsb.add("idkelastujuan", idKelasTujuan);
    qsb.add("kelastujuan", kelasTujuan);
    qsb.addInput("departementujuan", "departementujuan");
    qsb.addInput("idangkatantujuan", "angkatantujuan");
    qsb.add("angkatantujuan", $("#angkatantujuan option:selected").text());
    qsb.addInput("idtahunajarantujuan", "tahunajarantujuan");
    qsb.add("tahunajarantujuan", $("#tahunajarantujuan option:selected").text());
    qsb.addInput("idtingkattujuan", "tingkattujuan");
    qsb.add("tingkattujuan", $("#tingkattujuan option:selected").text());

    let addr = "lulus.dialog.php?" + qsb.createQs();
    newWindow(addr,'LulusSiswa','780','600','resizeable=0,scrollbars=0,status=0,toolbar=0');
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

function refreshDaftarSiswaKelasTujuan()
{
    onChangeKelasTujuan();
}

function refreshKelasTujuan()
{
    function acceptKelasTujuan()
    {
        onChangeKelasTujuan();
    }
    
    let $optKelasTujuan = $("#kelastujuan option:selected");
    let dataKelasTujuan = JSON.parse(atob($optKelasTujuan.val()));
    let idKelasTujuan = dataKelasTujuan[0];
    
    fetchKelasTujuan(acceptKelasTujuan, idKelasTujuan);
}

function onChangeDeptTujuan()
{
    $('#dvDaftarSiswaTujuan').html("");

    fetchAngkatanTujuan();

    function acceptTahunAjaran()
    {
        onChangeTahunAjaranTujuan();
    }
    
    fetchTahunAjaranTujuan(acceptTahunAjaran);
}

function fetchTahunAjaranTujuan(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "tahunajarantujuan");
    qsb.addInput("departementujuan", "departementujuan");
    qsb.addInput("idtahunajaran", "idtahunajaran");

    let spTahunAjaranTujuan = $("#spTahunAjaranTujuan");
    spTahunAjaranTujuan.html("memuat ..");

    $.ajax({
        url: "lulus.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTahunAjaranTujuan.html(data).hide().fadeIn(300);
            
            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchAngkatanTujuan(callback)
{
    let qsb = new QsBuilder()
    qsb.add("op", "angkatantujuan")
    qsb.addInput("departementujuan", "departementujuan")

    let spAngkatanTujuan = $("#spAngkatanTujuan");
    spAngkatanTujuan.html("memuat ..");

    $.ajax({
        url: "lulus.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spAngkatanTujuan.html(data).hide().fadeIn(300);
            
            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeTahunAjaranTujuan()
{
    $('#dvDaftarSiswaTujuan').html("");

    function acceptTingkatTujuan()
    {
        fetchKelasTujuan(acceptKelasTujuan, 0);
    }

    function acceptKelasTujuan()
    {
        onChangeKelasTujuan();
    }
    
    fetchTingkatTujuan(acceptTingkatTujuan);
}

function fetchTingkatTujuan(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "tingkattujuan");
    qsb.addInput("departementujuan", "departementujuan");

    let spTingkatTujuan = $("#spTingkatTujuan");
    spTingkatTujuan.html("memuat ..");

    $.ajax({
        url: "lulus.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTingkatTujuan.html(data).hide().fadeIn(300);
            
            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeTingkatTujuan()
{
    $('#dvDaftarSiswaTujuan').html("");

    function acceptKelasTujuan(resp)
    {
        onChangeKelasTujuan();
    }
    
    fetchKelasTujuan(acceptKelasTujuan, 0);
}

function fetchKelasTujuan(callback, idKelasSelected)
{
    let qsb = new QsBuilder();
    qsb.add("op", "kelastujuan");
    qsb.addInput("idtahunajarantujuan", "tahunajarantujuan");
    qsb.addInput("idtingkattujuan", "tingkattujuan");
    qsb.add("idkelasselected", idKelasSelected);

    let spKelasTujuan = $("#spKelasTujuan");
    spKelasTujuan.html("memuat ..");
    
    $.ajax({
        url: "lulus.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKelasTujuan.html(data).hide().fadeIn(300);
            
            if (typeof(callback) !== "undefined")
                callback();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeKelasTujuan()
{
    if ($("#kelastujuan option").length == 0)
        return;

    let $optKelasTujuan = $("#kelastujuan option:selected");
    let dataKelasTujuan = JSON.parse(atob($optKelasTujuan.val()));
    let idKelasTujuan = dataKelasTujuan[0];

    let qsb = new QsBuilder();
    qsb.add("op", "daftarsiswatujuan");
    qsb.add("idkelasselected", idKelasTujuan);
    
    let dvDaftarSiswaTujuan = $('#dvDaftarSiswaTujuan');
    dvDaftarSiswaTujuan.html("memuat ..");

    $.ajax({
        url: "lulus.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvDaftarSiswaTujuan.html(data).hide().fadeIn(300);           
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function batalLulusSiswa(nis, nama)
{
    if (!confirm("BATALKAN KELULUSAN SISWA " + nama + " (" + nis + ")?"))
        return;

    let $optKelasTujuan = $("#kelastujuan option:selected");
    let dataKelasTujuan = JSON.parse(atob($optKelasTujuan.val()));
    let idKelasTujuan = dataKelasTujuan[0];

    let qsb = new QsBuilder();
    qsb.add("op", "batallulus");
    qsb.add("nis", nis);
    qsb.add("idkelastujuan", idKelasTujuan);
    qsb.addInput("departementujuan", "departementujuan");
        
    $.ajax({
        url: "lulus.content.ajax.php",
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

            refreshKelasTujuan();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onLulusSiswa()
{
    tabsiswa_refresh();

    refreshKelasTujuan();   
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function detailSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../siswa/siswa.detail.php?'+qsb.createQs(), 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function profilSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'ProfilSiswa' + nis,'620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}