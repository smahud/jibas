$(document).ready(function()
{
    let replid = parseInt($("#replid").val());

    if (replid === 0)
        $("#row_nopendaftaran").css("display", "none");

    $("#nama").focus();        

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
})

$(document).ready(function() 
{
    $("#angkatan").focus();
});

function simpan()
{
    let isValid = Vldr.IsNotEmpty("nama", "Nama Calon Siswa") &&
                  Vldr.InputText("nama", "Nama Calon Siswa", 3, 255);

    let tglLahir = $.trim($("#tgllahir").val());
    if (isValid && tglLahir != "") 
    {
        isValid = Vldr.IsInteger("tgllahir", "Tanggal Lahir") &&
                  Vldr.IsPositive("tgllahir", "Tanggal Lahir") &&
                  Vldr.IsInRangeInteger("tgllahir", "Tanggal Lahir", 1, 31);
    }                

    let blnLahir = $.trim($("#blnlahir").val());
    if (isValid && blnLahir != "") 
    {
        isValid = Vldr.IsInteger("blnlahir", "Bulan Lahir") &&
                  Vldr.IsPositive("blnlahir", "Bulan Lahir") &&
                  Vldr.IsInRangeInteger("blnlahir", "Bulan Lahir", 1, 12);
    }                

    let thnLahir = $.trim($("#thnlahir").val());
    if (isValid && thnLahir != "") 
    {
        isValid = Vldr.IsInteger("thnlahir", "Tahun Lahir") &&
                  Vldr.IsPositive("thnlahir", "Tahun Lahir") &&
                  Vldr.IsInRangeInteger("thnlahir", "Tahun Lahir", 1900, 2200);
    }                

    if (!isValid)                  
        return;

    if (!confirm("Data sudah benar?"))        
        return;
                  
    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("nama", "nama");
    qsb.addInput("panggilan", "panggilan");
    qsb.addInput("tmplahir", "tmplahir");
    qsb.addInput("tgllahir", "tgllahir");
    qsb.addInput("blnlahir", "blnlahir");
    qsb.addInput("thnlahir", "thnlahir");
    qsb.addInput("keterangan", "keterangan");
    qsb.add("gender", $('input[name="gender"]:checked').val());

    setGui("wait");

    $.ajax({
        url: "pendataan.dialog.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = $.parseJSON(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            let replid = parseInt($("#replid").val());
            if (replid === 0)
                opener.onNewData();
            else
                opener.onDataChange();
                
            window.close();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            setGui("ready");
        }
    });
}

function setGui(state)
{
    switch(state)
    {
        case "wait":
            $("#dvLoading").show();
            $("#btnSimpan").prop("disabled", true);
            $("#btnTutup").prop("disabled", true);
            break;
        case "ready":
            $("#dvLoading").hide();
            $("#btnSimpan").prop("disabled", false);
            $("#btnTutup").prop("disabled", false);
            break;
    }
}
