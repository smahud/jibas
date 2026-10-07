$(document).ready(function() {

    initUi();
    
})

function initUi()
{
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });

    $("#urutan").focus();

    tinymce.init({
            selector: 'textarea',
            license_key: 'gpl',
            height: 180,          // Sets the height to exactly 300 pixels
            menubar: false,       // Hides the top File/Edit menus
            statusbar: false,     // Hides the bottom path and counter bar
            toolbar: 'undo redo | bold italic underline |  forecolor backcolor | removeformat',
        });    
}

$(window).on('beforeunload', function(event) 
{
    let jenis = $("#jenis").val();
    let index = $("#index").val();
    
    if (jenis == "nilai")
        opener.refreshKomentarPelajaran(index);
    else if (jenis == "sikap")
        opener.refreshKomentarSikap(index);
});


function simpan()
{
    let komentar = tinymce.get('komentar').getContent();
    komentar = $.trim(komentar);
    if (komentar.length < 10)
    {
        alert("Komentar harus minimal 10 karakter");
        tinymce.get('komentar').focus();
        return;
    }

    if (komentar.length > 1000)
    {
        alert("Komentar tidak boleh lebih dari 1000 karakter");
        tinymce.get('komentar').focus();
        return;
    }

    let isValid = Vldr.IsHaveInput("urutan", "Urutan") &&
                  Vldr.IsInteger("urutan", "Urutan");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kodeaspek", "kodeaspek");
    qsb.addInput("urutan", "urutan");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("kodejenis", "kodejenis");

    let komentar64 = btoa(tinymce.get("komentar").getContent());
    qsb.add("komentar64", komentar64);

    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        type: "POST",
        url: "komentar.template.dialog.ajax.php",
        data: qsb.createQs(),
        success: function(json)
        {
            let ls = JSON.parse(json);
            if (ls[0] < 0)
            {
                alert(ls[1]);
                return;
            }
            
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btTutup.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
        
}