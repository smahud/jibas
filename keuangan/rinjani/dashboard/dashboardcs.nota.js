$(document).ready(function ()
{
    showDaftarNota();
});

function showDaftarNota()
{
    function acceptListNota()
    {
        showTableNota(1);
    }

    fetchListNota(acceptListNota);
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
    $("#dvDaftarNota").html("memuat ..");

    $.ajax({
        url: "dashboardcs.nota.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvDaftarNota").html(data).hide().fadeIn(300);
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

function fetchListNota(callback)
{
    let qsb = new QsBuilder();
    qsb.add("op", "fetchlistnota");
    qsb.addInput("nic", "nic");
    qsb.addInput("bagiannota", "bagiannota");

    $("#dvLoading").show();
    $("#dvDaftarNota").html("memuat ..");
    $("#dvPageControl").html("");

    $.ajax({
        url: "dashboardcs.nota.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            console.log(json);

            let ls = JSON.parse(json);
            if (parseInt(ls[0]) == 0)
            {
                $("#dvDaftarNota").html(ls[1]);
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

function showPageControl(nData)
{
    let qsb = new QsBuilder();
    qsb.add("op", "showpagecontrol");
    qsb.add("ndata", nData);

    $("#dvPageControl").html("memuat ..");

    $.ajax({
        url: "dashboardcs.nota.ajax.php",
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

function tambahNotaCalonSiswa() 
{
    let qsb = new QsBuilder();
    qsb.add("id", 0);
    qsb.addInput("nic", "nic");
    qsb.addInput("nama", "nama");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");

    console.log(qsb.createQs());

    newWindow('dashboardcs.nota.dialog.php?'+qsb.createQs(), 'TambahNotaCalonSiswa','520','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function onNewData()
{
    showDaftarNota();
}

function onDataChange()
{
    onChangePage();
}

function onHoverNota(idNota)
{
    $("#menu-nota-" + idNota).css("visibility", "visible");
}

function onLeaveNota(idNota)
{
    $("#menu-nota-" + idNota).css("visibility", "hidden");
}

function refreshNota()
{
    onChangePage();
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
    qsb.addInput("nic", "nic");
    qsb.addInput("nama", "nama");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");

    newWindow('dashboardcs.nota.dialog.php?'+qsb.createQs(), 'UbahNotaCalonSiswa','520','520','resizable=1,scrollbars=1,status=0,toolbar=0');
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
        url: "dashboardcs.nota.ajax.php",
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

function onChangePage()
{
    let page = $("#page").val();
    showTableNota(page);
}

function onChangeBagianNota()
{
    showDaftarNota();
}

function kelolaBagianNota()
{
    newWindow('../referensi/bagiannota.dialog.php?mode=manage', 'PilihBagianNota', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshBagianNota()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refreshbagiannota");
    qsb.addInput("nic", "nic");
    qsb.addInput("bagiannota", "bagiannota");

    $("#dvLoading").show();
    $("#spBagianNota").html("memuat ..");

    $.ajax({
        url: "dashboardcs.nota.ajax.php",
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
