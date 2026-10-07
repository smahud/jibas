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
    opener.refreshPilihanData();
});

function pilih(pilihandataid, pilihandata)
{
    opener.acceptPilihanData(pilihandataid, pilihandata);
    window.close();
}

function edit(pilihandataid, pilihandata, urutan)
{
    $("#spJudul").html("Ubah Pilihan Data");
    $("#pilihandataid").val(pilihandataid);
    $("#pilihandata").val(pilihandata);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function baru()
{
    $("#spJudul").html("Tambah Pilihan Data");
    $("#pilihandataid").val("0");
    $("#pilihandata").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function simpan()
{
    let isValid = Vldr.InputText("pilihandata", "Pilihan Data", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idtambahan", "idtambahan");
    qsb.addInput("pilihandataid", "pilihandataid");
    qsb.addInput("pilihandata", "pilihandata");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "kolomtambahan.pilihan.ajax.php",
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
    qsb.addInput("idtambahan", "idtambahan");

    $.ajax({
        url: "kolomtambahan.pilihan.ajax.php",
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

function hapus(pilihandataid)
{
    if (!confirm("Hapus Pilihan Data ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("pilihandataid", pilihandataid);

    $.ajax({
        url: "kolomtambahan.pilihan.ajax.php",
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