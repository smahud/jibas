$(document).ready(function() 
{
    if ($("#table").length) 
        Tables("table", 1, 0);        

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

$(window).on('beforeunload', function(event) 
{
    opener.refreshStatusGuru();
});

function baru()
{
    $("#spJudul").html("Tambah Status Guru");
    $("#statusguruid").val("0");
    $("#status").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(statusguruid, status, urutan)
{
    $("#spJudul").html("Ubah Status Guru");
    $("#statusguruid").val(statusguruid);
    $("#status").val(status);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function pilih(statusguruid, status)
{
    opener.acceptStatusGuru(statusguruid, status);
    window.close();
}

function hapus(statusguruid)
{
    if (!confirm("Hapus Status Guru ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("statusguruid", statusguruid);

    $.ajax({
        url: "statusguru.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            refresh();
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

function simpan()
{
    let isValid = Vldr.InputText("status", "Status", 3, 50) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("statusguruid", "statusguruid");
    qsb.addInput("status", "status");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "statusguru.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            baru();           
            refresh();
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

function refresh()
{
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "refresh");

    $.ajax({
        url: "statusguru.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);

            if ($("#table").length) 
                Tables("table", 1, 0);        
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
