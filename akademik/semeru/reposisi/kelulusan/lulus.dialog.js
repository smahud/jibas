var nChecked = 0;

$(document).ready(function() {
    if ($("#tableSiswa").length > 0)
        Tables('tableSiswa', 1, 0);

    $("#nisbaru1").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });

});

function salinNis(no)
{
    $("#nisbaru" + no).val($("#nis" + no).val());
}

function validateInput()
{
    let nSiswa = $("#nsiswa").val();
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checklulus" + i).is(":checked"))
        {
            let nis = $.trim($("#nisbaru" + i).val());
            if (nis.length < 3)
            {
                alert("Panjang nis minimal 3 karakter")
                $("#nisbaru" + i).focus();
                return false;
            }

            nChecked++;
        }
    }
    
    if (nChecked == 0)
    {
        alert("Pilih minimal 1 siswa untuk di proses");
        return false;
    }

    return true;
}

function lulusSiswa()
{
    if (!validateInput())
        return;
    
    if (!confirm("PROSES KELULUSAN " + nChecked + " SISWA?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("departementujuan", "departementujuan");
    qsb.addInput("idangkatantujuan", "idangkatantujuan");
    qsb.addInput("angkatantujuan", "angkatantujuan");
    qsb.addInput("idtingkattujuan", "idtingkattujuan");
    qsb.addInput("tingkattujuan", "tingkattujuan");
    qsb.addInput("idkelastujuan", "idkelastujuan");
    qsb.addInput("kelastujuan", "kelastujuan");
    qsb.addInput("idtahunajarantujuan", "idtahunajarantujuan");
    qsb.addInput("tahunajarantujuan", "tahunajarantujuan");

    let nSiswa = $("#nsiswa").val();
    let no = 0;
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checklulus" + i).is(":checked"))
        {
            no += 1;
            qsb.addInput("nis" + no, "nis" + i);
            qsb.addInput("nama" + no, "nama" + i);
            qsb.addInput("idkelasawal" + no, "idkelasawal" + i);
            qsb.addInput("nisbaru" + no, "nisbaru" + i);
            qsb.addInput("keterangan" + no, "keterangan" + i);
        }
    }
    qsb.add("nsiswa", nChecked);

    let btLulus = $("#btLulus");
    let btBatal = $("#btBatal");

    btLulus.prop("disabled", true);
    btBatal.prop("disabled", true);
    $("dvLoading").show();

    $.ajax({
        url: "lulus.dialog.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResp = JSON.parse(json);
            if (lsResp[0] < 0)
            {
                alert(lsResp[1]);
                return;
            }
            
            opener.onLulusSiswa();

            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btLulus.prop("disabled", false);
            btBatal.prop("disabled", false);
            $("dvLoading").hide();
        }
    });
}

function showIuran(no)
{
    let qsb = new QsBuilder();
    qsb.addInput("nis", "nis" + no);
    qsb.addInput("nama", "nama" + no);
    qsb.addInput("departemen", "departemen");
    
    let addr = "../data/keu.riwayat.dialog.php?" + qsb.createQs();
    newWindow(addr,'TunggakanPembayaran','1000','700','resizeable=0,scrollbars=0,status=0,toolbar=0');
}