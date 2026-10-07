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
    opener.refreshKolomTambahan();
});

function pilih(kolomtambahanid, kolomtambahan)
{
    opener.pilihKolomTambahan(kolomtambahanid, kolomtambahan);
    window.close();
}

function edit(kolomtambahanid, kolomtambahan, jenis, urutan)
{
    $("#spJudul").html("Ubah Data Tambahan");
    $("#kolomtambahanid").val(kolomtambahanid);
    $("#kolomtambahan").val(kolomtambahan);
    $("#jenis").val(jenis);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function baru()
{
    $("#spJudul").html("Tambah Data Tambahan");
    $("#kolomtambahanid").val("0");
    $("#kolomtambahan").val("");
    $("#jenis").val("1");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function simpan()
{
    let isValid = Vldr.InputText("kolomtambahan", "Data Tambahan", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kolomtambahanid", "kolomtambahanid");
    qsb.addInput("kolomtambahan", "kolomtambahan");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "kolomtambahan.dialog.ajax.php",
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
    qsb.addInput("departemen", "departemen");

    $.ajax({
        url: "kolomtambahan.dialog.ajax.php",
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

function hapus(kolomtambahanid)
{
    if (!confirm("Hapus Data Tambahan ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("kolomtambahanid", kolomtambahanid);

    $.ajax({
        url: "kolomtambahan.dialog.ajax.php",
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