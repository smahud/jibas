var filterMode = 0;
var dialogBox = null;

$(document).ready(function ()
{
    dialogBox = new DialogBox("#divDialog", 600, 400);

    showDaftarNota();

    initEvent();
});

function initEvent()
{
    $('#dvRekapTransContainer').on('wheel', function(e) {
        // Prevent the main page from scrolling vertically
        e.preventDefault();
        
        // Fetch the native wheel event details
        let originalEvent = e.originalEvent;
        
        // Determine the scroll direction and intensity
        let delta = originalEvent.deltaY;
        
        // Adjust the current horizontal position
        this.scrollLeft += delta;
    });
}

function refreshNota()
{
    if ($("#page").length == 0)
        return;

    onChangePage();
}

function showBlank()
{
    $("#dvTableContentNota").html("");
    $("#dvPageControl").html("");
}

function onChangeChBulanTahun()
{
    let checked = $("#chBulanTahun").is(":checked");

    $("#bulan").prop("disabled", !checked);
    $("#tahun").prop("disabled", !checked);
}

function onChangeBulanTahun()
{
    showBlank();
}

function onChangePenulis()
{
    showBlank();
}

function onChangeChKelompok()
{
    let checked = $("#chKelompok").is(":checked");

    $("#kelompok").prop("disabled", !checked);
    $("#btCariPerson").prop("disabled", !checked);
}

function onChangeCbKelompok()
{
    showBlank();

    $("#userid").val("");
    $("#username").val("");
}

function onChangeChPenulis()
{
    let checked = $("#chPenulis").is(":checked");

    $("#penulis").prop("disabled", !checked);
}

function onChangeChKeyword()
{
    let checked = $("#chKeyword").is(":checked");

    $("#keyword").prop("disabled", !checked);
    $("#keyword").css("background-color", checked ? "white" : "#ccc");
    $("#keyword").focus();  
}

function cariPerson()
{
    let kelompok = $("#kelompok").val();

    let qsb = new QsBuilder();
    qsb.add("departemen", $("#departemen").val());

    if (kelompok === "siswa")
        addr = "../library/daftarsiswa.dialog.php?" + qsb.createQs();
    else if (kelompok === "calonsiswa")
        addr = "../library/daftarcalonsiswa.dialog.php?" + qsb.createQs();
    else if (kelompok === "pegawai")
        addr = "../library/daftarpegawai.dialog.php?" + qsb.createQs();

    newWindow(addr, 'CariUser', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function onChangeDept()
{
    showBlank();
}

function onChangeBagianNota()
{
    showBlank();
}

function onNewData()
{
    showDaftarNota();
}

function onUpdateData()
{
    onChangePage();
}

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#userid").val(data.NIS);
    $("#username").val(data.Nama + " (" + data.NIS + ")");
}

function acceptCalonSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#userid").val(data.NIC);
    $("#username").val(data.Nama + " (" + data.NIC + ")");
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#userid").val(data.NIP);
    $("#username").val(data.Nama + " (" + data.NIP + ")");
}


function onFilterClick()
{
    if (filterMode == 0)
    {
        filterMode = 1;
        $(".trFilter").show();
        $("#btLihat2").show();
        $("#btLihat1").hide();
    }
    else
    {
        filterMode = 0;
        $(".trFilter").hide();
        $("#btLihat2").hide();
        $("#btLihat1").show();
    }
}

function showDaftarNota()
{
    function acceptListNota()
    {
        showTableNota(1);
    }

    fetchListNota(acceptListNota);
}

function fetchListNota(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "fetchlistnota");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");
    
    if ($("#chBulanTahun").is(":checked"))
    {
        qsb.addInput("bulan", "bulan");
        qsb.addInput("tahun", "tahun");
    }
    
    if ($("#chKelompok").is(":checked"))
    {
        if ($.trim($("#userid").val()).length > 0)
        {
            qsb.addInput("kelompok", "kelompok");
            qsb.addInput("userid", "userid");
        }
    }
    
    if ($("#chPenulis").is(":checked"))
    {
        qsb.addInput("penulis", "penulis");
    }

    if ($("#chKeyword").is(":checked"))
    {
        let keyword = $.trim($("#keyword").val());
        if (keyword.length >= 3)
            qsb.add("keyword", keyword);
    }

    $("#dvLoading").show();
    $("#dvTableContentNota").html("memuat ..");

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            console.log(json);

            let ls = JSON.parse(json);
            if (parseInt(ls[0]) == 0)
            {
                $("#dvTableContentNota").html(ls[1]);
                return;
            }
            else if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            let nData = parseInt(ls[1]);
            showPageControl(nData);

            $("#nnota").val(nData);
            $("#jsonidnota").val(JSON.stringify(ls[2]));

            callback();
        },
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function onChangePage()
{
    let page = $("#page").val();
    showTableNota(page);
}

function showPageControl(nData)
{
    let qsb = new QsBuilder();
    qsb.add("op", "showpagecontrol");
    qsb.add("ndata", nData);

    $("#dvPageControl").html("memuat ..");

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvPageControl").html(data).hide().fadeIn(300);
        },
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        }
    })
}

function showTableNota(page)
{
    let jsonIdNota = $("#jsonidnota").val();
    if (jsonIdNota == "")
        return;

    let lsIdNota = JSON.parse(jsonIdNota);
    let stIdNota = lsIdNota[page - 1];

    let qsb = new QsBuilder();
    qsb.add("op", "showtablenota");
    qsb.add("stidnota", stIdNota);

    $("#dvLoading").show();
    $("#dvTableContentNota").html("memuat ..");

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvTableContentNota").html(data).hide().fadeIn(300);
        },
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
}

function tambahNota() 
{
    let qsb = new QsBuilder();
    qsb.add("id", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");

    newWindow('home.nota.dialog.php?'+qsb.createQs(), 'TambahNota','520','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function onHoverNota(idNota)
{
    $("#menu-nota-" + idNota).css("visibility", "visible");
}

function onLeaveNota(idNota)
{
    $("#menu-nota-" + idNota).css("visibility", "hidden");
}

function view(idNota)
{
    let nota = $("#nota-" + idNota).html();
    
    dialogBox.show(nota);
}

function edit(idNota)
{
    let qsb = new QsBuilder();
    qsb.add("id", idNota);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");

    newWindow('home.nota.dialog.php?'+qsb.createQs(), 'UbahNota','520','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(idNota)
{   
    if (!confirm("Hapus nota ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("id", idNota);
    
    $("#dvLoading").show();

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let res = JSON.parse(json);
            if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }

            onChangePage();

            let nData = parseInt($("#nnota").val()) - 1;
            if (nData < 0)
                nData = 0;

            $("#spNData").html(nData);
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

function kelolaBagianNota()
{
    newWindow('../referensi/bagiannota.dialog.php?mode=manage', 'PilihBagianNota', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshBagianNota()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refreshbagiannota");
    qsb.addInput("bagiannota", "bagiannota");

    $("#dvLoading").show();
    $("#spBagianNota").html("memuat ..");

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spBagianNota").html(data).hide().fadeIn(300);
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

function showPilihTanggalRekap ()
{
    var selDate = $("#tglrekap").val();

    $("#txtglrekap").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tglrekap").val(date);
            $("#txtglrekap").val(dateutil_formatInaDate(date));

            loadRekapTransaksi();
        }
    }).focus();
};

function refreshRekap()
{
    loadRekapTransaksi();
}

function loadRekapTransaksi()
{
    let qsb = new QsBuilder();
    qsb.add("op", "rekaptrans");
    qsb.addInput("tglrekap", "tglrekap");

    $("#dvLoading").show();
    $("#dvRekapTransContent").html("memuat ..");

    $.ajax({
        url: "home.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvRekapTransContent").html(data).hide().fadeIn(300);
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