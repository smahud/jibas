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
    opener.refreshBagianNota();
});

function pilih(bagianid, bagian)
{
    opener.acceptBagianNota(bagianid, bagian);
    window.close();
}

function baru()
{
    $("#spJudul").html("Tambah Bagian Nota");
    $("#bagianid").val("0");
    $("#bagian").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(bagianid, bagian, urutan)
{
    $("#spJudul").html("Ubah Bagian Nota");
    $("#bagianid").val(bagianid);
    $("#bagian").val(bagian);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function hapus(bagianid)
{
    if (!confirm("Hapus bagian nota ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("bagianid", bagianid);

    $.ajax({
        url: "bagiannota.dialog.ajax.php",
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
    let isValid = Vldr.InputText("bagian", "Nama Bagian Nota", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("bagianid", "bagianid");
    qsb.addInput("bagian", "bagian");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "bagiannota.dialog.ajax.php",
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
        url: "bagiannota.dialog.ajax.php",
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