$(document).ready(function() {
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
    opener.refreshSuku();
});

function baru()
{
    $("#spJudul").html("Tambah Suku");
    $("#sukuid").val("0");
    $("#suku").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(sukuid, suku)
{
    $("#spJudul").html("Ubah Suku");
    $("#sukuid").val(sukuid);
    $("#suku").val(suku);
    $("#btBaru").css("visibility", "visible");
}

function pilih(sukuid, suku)
{
    opener.acceptSuku(sukuid, suku);
    window.close();
}

function hapus(sukuid)
{
    if (!confirm("Hapus Suku ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("sukuid", sukuid);

    $.ajax({
        url: "suku.dialog.ajax.php",
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
    let isValid = Vldr.InputText("suku", "Nama Suku", 3, 20);
    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("sukuid", "sukuid");
    qsb.addInput("suku", "suku");

    $.ajax({
        url: "suku.dialog.ajax.php",
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
        url: "suku.dialog.ajax.php",
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