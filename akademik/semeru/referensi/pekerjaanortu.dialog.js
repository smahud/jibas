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
    opener.refreshPekerjaanOrtu();
});

function baru()
{
    $("#spJudul").html("Tambah Pekerjaan Orang Tua");
    $("#pekerjaanortuid").val("0");
    $("#pekerjaanortu").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(pekerjaanortuid, pekerjaanortu)
{
    $("#spJudul").html("Ubah Pekerjaan Orang Tua");
    $("#pekerjaanortuid").val(pekerjaanortuid);
    $("#pekerjaanortu").val(pekerjaanortu);
    $("#btBaru").css("visibility", "visible");
}

function pilih(pekerjaanortuid, pekerjaanortu)
{
    opener.acceptPekerjaanOrtu(pekerjaanortuid, pekerjaanortu);
    window.close();
}

function hapus(pekerjaanortuid)
{
    if (!confirm("Hapus Pekerjaan Orang Tua ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("pekerjaanortuid", pekerjaanortuid);

    $.ajax({
        url: "pekerjaanortu.dialog.ajax.php",
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
    let isValid = Vldr.InputText("pekerjaanortu", "Nama Pekerjaan Orang Tua", 3, 20);
    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("pekerjaanortuid", "pekerjaanortuid");
    qsb.addInput("pekerjaanortu", "pekerjaanortu");

    $.ajax({
        url: "pekerjaanortu.dialog.ajax.php",
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
        url: "pekerjaanortu.dialog.ajax.php",
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