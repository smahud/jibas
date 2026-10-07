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
    opener.refreshPendidikanOrtu();
});


function baru()
{
    $("#spJudul").html("Tambah Pendidikan Orang Tua");
    $("#pendidikanortuid").val("0");
    $("#pendidikanortu").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(pendidikanortuid, pendidikanortu, urutan)
{
    $("#spJudul").html("Ubah Pendidikan Orang Tua");
    $("#pendidikanortuid").val(pendidikanortuid);
    $("#pendidikanortu").val(pendidikanortu);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function pilih(pendidikanortuid, pendidikanortu)
{
    opener.acceptPendidikanOrtu(pendidikanortuid, pendidikanortu);
    window.close();
}

function hapus(pendidikanortuid)
{
    if (!confirm("Hapus Pendidikan Orang Tua ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("pendidikanortuid", pendidikanortuid);

    $.ajax({
        url: "pendidikanortu.dialog.ajax.php",
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
    let isValid = Vldr.InputText("pendidikanortu", "Pendidikan Orang Tua", 1, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("pendidikanortuid", "pendidikanortuid");
    qsb.addInput("pendidikanortu", "pendidikanortu");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "pendidikanortu.dialog.ajax.php",
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
        url: "pendidikanortu.dialog.ajax.php",
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