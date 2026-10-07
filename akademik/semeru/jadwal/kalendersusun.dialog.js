$(document).ready(function() {

    $("#kode").focus();

    tinymce.init({
            selector: '#keterangan',
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

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function showPilihTglAwal()
{
    var selDate = $("#tanggalawal_value").val();

    $("#tanggalawal").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tanggalawal_value").val(date);
            $("#tanggalawal").val(dateutil_formatInaDate(date));
        }
    }).focus();
};

function showPilihTglAkhir()
{
    var selDate = $("#tanggalakhir_value").val();

    $("#tanggalakhir").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date)
        {
            $("#tanggalakhir_value").val(date);
            $("#tanggalakhir").val(dateutil_formatInaDate(date));
        }
    }).focus();
}

function simpan()
{
    let isValid = Vldr.InputText("kode", "Kode Kegiatan", 1, 100) &&
                  Vldr.InputText("kegiatan", "Nama Kegiatan", 5, 100) &&
                  Vldr.IsNotEmpty("tanggalawal", "Tanggal Awal") &&
                  Vldr.IsNotEmpty("tanggalakhir", "Tanggal Akhir");

    if (!isValid)
        return;

    let keterangan = tinymce.get('keterangan').getContent();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("idkalender", "idkalender");
    qsb.addInput("kode", "kode");
    qsb.addInput("kegiatan", "kegiatan");
    qsb.addInput("tanggalawal", "tanggalawal_value");
    qsb.addInput("tanggalakhir", "tanggalakhir_value");
    qsb.add("keterangan", keterangan);

    $.ajax({
        url: "kalendersusun.dialog.ajax.php",
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

            opener.refresh();
            window.close();
        },
        error: function(xhr, status, error)
        {
            alert(xhr.responseText);
        }
    })
}