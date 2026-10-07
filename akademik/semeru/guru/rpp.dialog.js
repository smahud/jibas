$(document).ready(function() 
{
    tinymce.init({
            selector: '#deskripsi',
            license_key: 'gpl',
            skin: 'oxide',
            content_style: 'body { font-size: 14px;}',
            menubar: false,
            toolbar_mode: 'sliding',
            plugins: 'code lists table image link', 
            toolbar: 'undo redo | bold italic | forecolor backcolor | table | bullist numlist | alignleft aligncenter alignright | blocks | code',
            font_family_formats: 'Verdana=verdana,sans-serif; Arial=arial,helvetica,sans-serif',
            font_size_formats: '12px 14px 16px 18px',
            branding: false,
            promotion: false
        });    

    $("#koderpp").focus();        
    
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpan()
{
    const isValid = Vldr.InputText("koderpp", "Kode RPP", 1, 10) &&
                    Vldr.InputText("urutan", "Urutan", 1, 10) &&
                    Vldr.IsNumeric("urutan", "Urutan") && 
                    Vldr.InputText("materi", "Materi", 3, 255);

    let deskripsi = tinymce.get('deskripsi').getContent();
    if ($.trim(deskripsi).length < 10)
    {
        alert("Deskripsi", "Deskripsi harus memiliki minimal 10 karakter");
        return;
    }

    if (!confirm("Data RPP sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "save");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("semester", "semester");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("koderpp", "koderpp");
    qsb.addInput("materi", "materi");
    qsb.add("deskripsi", deskripsi);
    qsb.addInput("urutan", "urutan");

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "rpp.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                btnSimpan.prop("disabled", false);
                btnTutup.prop("disabled", false);

                alert(response[1]);
                return;
            }

            opener.onDataChanged();
            window.close();
        },
        error: function (xhr)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);

            alert(xhr.responseText);
        }
    });
}

function removeHtmlTags()
{
    let str = $.trim($("#deskripsi").val());
    str = str.replace(/<[^>]*>/g, '')
             .replace(/&amp;/g, '&')
             .replace(/&lt;/g, '<')
             .replace(/&gt;/g, '>')
             .replace(/&quot;/g, '"')
             .replace(/&#39;/g, "'")
             .replace(/&nbsp;/g, ' ');
    $("#deskripsi").val(str);
}