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
    opener.refreshKondisiSiswa();
});


function baru()
{
    $("#spJudul").html("Tambah Kondisi Siswa");
    $("#kondisisiswaid").val("0");
    $("#kondisisiswa").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(kondisisiswaid, kondisisiswa, urutan)
{
    $("#spJudul").html("Ubah Kondisi Siswa");
    $("#kondisisiswaid").val(kondisisiswaid);
    $("#kondisisiswa").val(kondisisiswa);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function pilih(kondisisiswaid, kondisisiswa)
{
    opener.acceptKondisiSiswa(kondisisiswaid, kondisisiswa);
    window.close();
}

function hapus(kondisisiswaid)
{
    if (!confirm("Hapus Kondisi Siswa ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("kondisisiswaid", kondisisiswaid);

    $.ajax({
        url: "kondisisiswa.dialog.ajax.php",
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
    let isValid = Vldr.InputText("kondisisiswa", "Kondisi Siswa", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("kondisisiswaid", "kondisisiswaid");
    qsb.addInput("kondisisiswa", "kondisisiswa");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "kondisisiswa.dialog.ajax.php",
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
        url: "kondisisiswa.dialog.ajax.php",
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