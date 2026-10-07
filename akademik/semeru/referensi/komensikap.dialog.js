$(document).ready(function() {
    tinymce.init({
            selector: 'textarea',
            license_key: 'gpl',
            inline: false,         // Standard mode
            height: 50,          // Sets the height to exactly 300 pixels
            menubar: false,       // Hides the top File/Edit menus
            statusbar: false,     // Hides the bottom path and counter bar
            toolbar: false // 'undo redo | bold italic underline | removeformat'
    });    

    if ($('#table').length)
        Tables("table", 1, 0);

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

$(window).on('beforeunload', function(event) 
{
    let index = $("#index").val();
    opener.refreshKomentarSikap(index);
});

function pilih(dataId)
{
    let data64 = $("#data" + dataId).val();
    let index = $("#index").val();

    opener.acceptKomentarSikap(index, data64);
    window.close();
}

function baru()
{
    $("#spJudul").html("Tambah Komentar Sikap");
    $("#komensikapid").val("0");
    $("#urutan").val("");
    $("#btBaru").css("visibility", "hidden");
    tinymce.get('komensikap').setContent("");
}

function edit(dataId)
{
    let data64 = $("#data" + dataId).val();
    let lsData = JSON.parse(atob(data64));

    let replid = lsData[0];
    let komensikap = lsData[1];
    let urutan = lsData[2];
    
    $("#spJudul").html("Ubah Komentar Sikap");
    $("#komensikapid").val(replid);
    $("#urutan").val(urutan);
    $("#btBaru").css("visibility", "visible");

    tinymce.get('komensikap').setContent(komensikap);
}

function simpan()
{
    let komensikap = tinymce.get('komensikap').getContent();
    komensikap = $.trim(komensikap);
    if (komensikap.length < 10)
    {
        alert("Komentar harus minimal 10 karakter");
        tinymce.get('komensikap').focus();
        return;
    }

    if (komensikap.length > 1000)
    {
        alert("Komentar tidak boleh lebih dari 1000 karakter");
        tinymce.get('komensikap').focus();
        return;
    }

    let isValid = Vldr.IsInteger("urutan", "Urutan");

    if (!isValid)
        return;
    
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("komensikapid", "komensikapid");
    qsb.add("komensikap64", btoa(komensikap));
    qsb.addInput("urutan", "urutan");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kodejenis", "kodejenis");

    $.ajax({
        url: "komensikap.dialog.ajax.php",
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
    qsb.addInput("mode", "mode");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("kodejenis", "kodejenis");

    $.ajax({
        url: "komensikap.dialog.ajax.php",
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

function hapus(komensikapid)
{
    if (!confirm("Hapus Komentar Sikap ini?"))
        return;

    $("#dvLoading").show();        

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("komensikapid", komensikapid);

    $.ajax({
        url: "komensikap.dialog.ajax.php",
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