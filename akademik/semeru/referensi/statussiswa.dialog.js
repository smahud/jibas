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
    opener.refreshStatusSiswa();
});

function baru()
{
    $("#spJudul").html("Tambah Status Siswa");
    $("#statussiswaid").val("0");
    $("#statussiswa").val("");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(statussiswaid, statussiswa, urutan)
{
    $("#spJudul").html("Ubah Status Siswa");
    $("#statussiswaid").val(statussiswaid);
    $("#statussiswa").val(statussiswa);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");
}

function pilih(statussiswaid, statussiswa)
{
    opener.acceptStatusSiswa(statussiswaid, statussiswa);
    window.close();
}

function hapus(statussiswaid)
{
    if (!confirm("Hapus Status Siswa ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("statussiswaid", statussiswaid);

    $.ajax({
        url: "statussiswa.dialog.ajax.php",
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
    let isValid = Vldr.InputText("statussiswa", "Status Siswa", 3, 20) && 
                  Vldr.InputText("urutan", "Urutan", 1, 3) &&
                  Vldr.IsInteger("urutan", "Urutan") && 
                  Vldr.IsPositive("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("statussiswaid", "statussiswaid");
    qsb.addInput("statussiswa", "statussiswa");
    qsb.addInput("urutan", "urutan");

    $.ajax({
        url: "statussiswa.dialog.ajax.php",
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
        url: "statussiswa.dialog.ajax.php",
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