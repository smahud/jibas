$(document).ready(function() {
    if ($("#tableAlumni").length)
        Tables("tableAlumni", 1, 0);
});

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

function fetchDaftarAlumni(page)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftaralumni");
    qsb.add("page", page);
    qsb.addInput("urut", "urut");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunkelulusan", "tahunkelulusan");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");
    
    $.ajax({
        url: "daftaralumni.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTableContent.html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunkelulusan", "tahunkelulusan");

    let dvPageControl = $("#dvPageControl");
    dvPageControl.html("memuat ..");
    
    $.ajax({
        url: "daftaralumni.ajax.php",
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

function batalAlumni(idAlumni, nis, nama)
{
     if (!confirm("BATALKAN PENDATAAN ALUMNI SISWA " + nama + " (" + nis + ")?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "batalalumni");
    qsb.add("idalumni", idAlumni);
        
    $.ajax({
        url: "daftaralumni.ajax.php",
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

            onChangePage();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeUrut(urut)
{
    $("#urut").val(urut);
    fetchDaftarAlumni(1);
}

function onChangeDepartemen()
{
    $("#dvTableContent").html("");
    $("#dvPageControl").html("");

    function acceptTahunKelulusan()
    {
        fetchDaftarAlumni(1);

        setTimeout(() => {
            fetchPageControl();
        }, 100);
    }

    fetchTahunKelulusan(acceptTahunKelulusan);
}

function fetchTahunKelulusan(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "tahunlulus");
    qsb.addInput("departemen", "departemen");

    let spTahunKelulusan = $("#spTahunKelulusan");
    spTahunKelulusan.html("memuat ..");
    
    $.ajax({
        url: "daftaralumni.ajax.php",
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

function detailSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../siswa/siswa.detail.php?'+qsb.createQs(), 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunkelulusan", "tahunkelulusan");

    let addr = "daftaralumni.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakDaftarAlumni','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();
        return "-";
    }
}