var nChecked = 0;

function checkAll()
{
    let nSiswa = $("#nsiswa").val();
    let chkAll = $("#chckall").is(":checked");
    for(let i = 1; i <= nSiswa; i++)
    {
        $("#checkalumni" + i).prop("checked", chkAll);
    }
}

function prosesAlumni()
{
    if (!validateInput())
        return;

    if (!confirm("PROSES ALUMNI " + nChecked + " SISWA?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("departemen", "departemen");
    
    let nSiswa = $("#nsiswa").val();
    let no = 0;
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checkalumni" + i).is(":checked"))
        {
            no += 1;
            qsb.addInput("nis" + no, "nis" + i);
            qsb.addInput("nama" + no, "nama" + i);
            qsb.addInput("idkelasawal" + no, "idkelasawal" + i);
            qsb.addInput("idtingkatawal" + no, "idtingkatawal" + i);
        }
    }
    qsb.add("nsiswa", no);

    let btProses = $("#btProses");
    let btBatal = $("#btBatal");

    btProses.prop("disabled", true);
    btBatal.prop("disabled", true);
    $("dvLoading").show();

    $.ajax({
        url: "alumni.dialog.ajax.php",
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
            
            opener.onAlumniSiswa();

            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btProses.prop("disabled", false);
            btBatal.prop("disabled", false);
            $("dvLoading").hide();
        }
    });
}

function validateInput()
{
    nChecked = 0;

    let nSiswa = $("#nsiswa").val();
    for(let i = 1; i <= nSiswa; i++)
    {
        if ($("#checkalumni" + i).is(":checked"))
            nChecked++;
    }
    
    if (nChecked == 0)
    {
        alert("Pilih minimal 1 siswa untuk di proses");
        return false;
    }

    return true;
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