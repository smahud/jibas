$(document).ready(function() {
    $("#cari").keyup(function(event) {
        if (event.key === "Enter") 
        {
            let text = $.trim($(this).val());
            if (text.length < 3)
            {
                showToastErrorBottom("Masukkan minimal 3 karakter untuk mencari");
                return;
            }

            onCariChanged();    
        }
    });    
})

function tambah()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");

    newWindow('rpp.dialog.php?' + qsb.createQs(), 'TambahRpp', '550', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function onStatusChanged()
{
    let qsb = new QsBuilder();
    qsb.add("page", 1);
    qsb.addInput("status", "status");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    
    document.location.href = "rpp.content.php?" + qsb.createQs();
}

function edit(idRpp)
{
    let qsb = new QsBuilder();
    qsb.add("replid", idRpp);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");

    newWindow('rpp.dialog.php?' + qsb.createQs(), 'UbahRpp', '550', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0')
}

function hapus(idRpp)
{
    let msg = "HAPUS RPP INI?"
    if (!confirm(msg))
        return
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", idRpp);

    $.ajax({
        url: "rpp.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result)
        {
            let lsData = JSON.parse(result);
            if (parseInt(lsData[0]) < 0) 
            {
                alert(lsData[1]);
                showToastErrorBottom(lsData[1]);
                return;
            }
            
            onDataChanged();
            showToastSuccessBottom("Data berhasil dihapus");
        },
        error: function(xhr, status, error)
        {
            showToastErrorBottom("Error: " + error);
        }
    })
}

function onHoverRpp(idRpp)
{
    $("#menu-rpp-" + idRpp).css("visibility", "visible");
}

function onLeaveRpp(idRpp)
{
    $("#menu-rpp-" + idRpp).css("visibility", "hidden");
}

function onChangePage()
{
    let qsb = new QsBuilder();
    qsb.add("op", "page");
    qsb.addInput("page", "page");
    qsb.addInput("status", "status");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idpelajaran", "idpelajaran");

    $.ajax({
        url: "rpp.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result)
        {
            $("#dvTableContent").html(result).hide().fadeIn(500);
        },
        error: function(xhr, status, error)
        {
            showToastErrorBottom("Error: " + error);
        }
    })
}

function loadPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("page", "page");
    qsb.addInput("status", "status");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idpelajaran", "idpelajaran");

    let dvPageControl = $("#dvPageControl");
    dvPageControl.css("visibility", "visible");
    dvPageControl.html("memuat ...");

    $.ajax({
        url: "rpp.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result)
        {
            dvPageControl.html(result).hide().fadeIn(500);
        },
        error: function(xhr, status, error)
        {
            showToastErrorBottom("Error: " + error);
        }
    })
}

function onDataChanged()
{
    let mode = $("#mode").val();
    if (mode == "list")
    {
        onChangePage();
        loadPageControl();
    }
    else 
    {
        onCariChanged();
    }
}

function setAktif(idRpp, newAktif)
{
    let message = newAktif == 1 ? "Aktifkan RPP ini?" : "Non aktifkan RPP ini?";
    if (!confirm(message))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", idRpp);
    qsb.add("newaktif", newAktif);
    
    $.ajax({
        url: "rpp.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResult = JSON.parse(json);

            if (parseInt(lsResult[0]) < 0)
            {
                showToastErrorBottom(lsResult[1]);
                return;
            }

            if (newAktif == 1) {
                $("#aktif-" + idRpp).attr("src", "../images/ico/aktif.png");
                $("#aktif-" + idRpp).attr("title", "aktif");
                $("#aktif-" + idRpp).attr("onclick", "setAktif(" + idRpp + ", 0)");
            } else {
                $("#aktif-" + idRpp).attr("src", "../images/ico/nonaktif.png");
                $("#aktif-" + idRpp).attr("title", "non aktif");
                $("#aktif-" + idRpp).attr("onclick", "setAktif(" + idRpp + ", 1)");
            }

            onDataChanged();

            showToastSuccessTop("Berhasil");
        },
        error: function(xhr)
        {
            showToastErrorBottom("Error: " + xhr.responseText);
        }
    })
}

function view(idRpp)
{
    let qsb = new QsBuilder();
    qsb.add("replid", idRpp);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("semester", "semester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("pelajaran", "pelajaran");

    newWindow('rpp.content.view.php?' + qsb.createQs(), 'ViewRpp', '550', '600', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function onCariChanged()
{
    let qsb = new QsBuilder();
    qsb.add("op", "search");
    qsb.add("cari", $.trim($("#cari").val()));
    qsb.addInput("status", "status");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idpelajaran", "idpelajaran");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    let dvPageControl = $("#dvPageControl");
    dvPageControl.css("visibility", "hidden");

    $.ajax({
        url: "rpp.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result)
        {
            $("#mode").val("search");
            dvTableContent.html(result).hide().fadeIn(500);
        },
        error: function(xhr, status, error)
        {
            showToastErrorBottom("Error: " + error);
        }
    })
}