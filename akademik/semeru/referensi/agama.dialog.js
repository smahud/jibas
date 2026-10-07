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
    opener.refreshAgama();
});

function baru()
{
    $("#spJudul").html("Tambah Agama");
    $("#agamaid").val("0");
    $("#agama").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(agamaid, agama, urutan)
{
    $("#spJudul").html("Ubah Agama");
    $("#agamaid").val(agamaid);
    $("#agama").val(agama);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function pilih(agamaid, agama)
{
    opener.acceptAgama(agamaid, agama);
    window.close();
}

function hapus(agamaid)
{
    if (!confirm("Hapus Agama ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("agamaid", agamaid);

    $.ajax({
        url: "agama.dialog.ajax.php",
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
    let isValid = Vldr.InputText("agama", "Nama Agama", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("agamaid", "agamaid");
    qsb.addInput("agama", "agama");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "agama.dialog.ajax.php",
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
        url: "agama.dialog.ajax.php",
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