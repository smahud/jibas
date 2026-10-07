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
    opener.refreshAsalSekolah();
});

function onChangeJenjangSekolah()
{
    let qsb = new QsBuilder();
    qsb.add("op", "asalsekolah");
    qsb.addInput("jenjangsekolah", "jenjangsekolah");
    qsb.addInput("mode", "mode");

    $("#spJenjangSekolah").html($("#jenjangsekolah option:selected").text())
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: "asalsekolah.dialog.ajax.php",
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
            alert(xhr.responseText)
        }
    })
}

function baru()
{
    $("#spJudul").html("Tambah Asal Sekolah");
    $("#asalsekolahid").val("0");
    $("#asalsekolah").val("");
    $("#btBaru").css("visibility", "hidden");
}

function edit(asalsekolahid, asalsekolah)
{
    $("#spJudul").html("Ubah Asal Sekolah");
    $("#asalsekolahid").val(asalsekolahid);
    $("#asalsekolah").val(asalsekolah);
    $("#btBaru").css("visibility", "visible");
}

function pilih(asalsekolahid, asalsekolah)
{
    opener.acceptAsalSekolah(asalsekolahid, asalsekolah);
    window.close();
}

function refresh()
{
    onChangeJenjangSekolah();
}

function hapus(asalsekolahid)
{
    if (!confirm("Hapus Asal Sekolah ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("asalsekolahid", asalsekolahid);

    $.ajax({
        url: "asalsekolah.dialog.ajax.php",
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
    let isValid = Vldr.InputText("asalsekolah", "Asal Sekolah", 3, 20);
    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("asalsekolahid", "asalsekolahid");
    qsb.addInput("asalsekolah", "asalsekolah");
    qsb.addInput("jenjangsekolah", "jenjangsekolah");

    $.ajax({
        url: "asalsekolah.dialog.ajax.php",
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
