$(document).ready(function() {
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function toggleInputBobot(rowNo)
{
    let isChecked = $("#cek" + rowNo).is(":checked");
    if (isChecked)
    {
        $("#bobot" + rowNo).prop("disabled", false);
        $("#bobot" + rowNo).removeClass("bg-input-disabled");
        $("#bobot" + rowNo).focus();
        $("#isdel" + rowNo).val("0");
    }
    else
    {
        $("#bobot" + rowNo).prop("disabled", true);
        $("#bobot" + rowNo).addClass("bg-input-disabled");

        let idBobot = $("#idbobot" + rowNo).val();
        if (idBobot != "0")
            $("#isdel" + rowNo).val("1");
    }
}

function simpanAturanBobot()
{
    let idTingkat = $("#idtingkat").val();
    let aspek = $("#aspek").val();
    let idPelajaran = $("#idpelajaran").val();
    let nip = $("#nip").val();
    let nJenisUjian = parseInt($("#njenisujian").val());

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.add("idtingkat", idTingkat);
    qsb.add("aspek", aspek);
    qsb.add("idpelajaran", idPelajaran);
    qsb.add("nip", nip);
    qsb.add("njenisujian", nJenisUjian);

    let valid = true;
    let nbobot = 0;
    for (let i = 1; i <= nJenisUjian; i++)
    {
        let checked = $("#cek" + i).is(":checked");
        let bobot = $("#bobot" + i).val();
        let isDel = $("#isdel" + i).val();
        let idBobot = parseInt($("#idbobot" + i).val());
        let idJenisUjian = parseInt($("#idjenisujian" + i).val());

        if (!checked && idBobot == 0)
            continue;

        if (bobot == "" || bobot == "0")
        {
            showToastErrorTop("Bobot harus diisi!", "error");
            $("#bobot" + i).focus();
            valid = false;
            break;
        }

        if (isNaN(bobot))
        {
            showToastErrorTop("Bobot harus angka!");
            $("#bobot" + i).focus();
            valid = false;
            break;
        }

        if (parseFloat(bobot) < 0)
        {
            showToastErrorTop("Bobot tidak boleh negatif!");
            $("#bobot" + i).focus();
            valid = false;
            break;
        }

        if (parseFloat(bobot) > 100)
        {
            showToastErrorTop("Bobot tidak boleh lebih dari 100!");
            $("#bobot" + i).focus();
            valid = false;
            break;
        }
        
        nbobot += 1;
        qsb.add("cek" + nbobot, checked ? "1" : "0");
        qsb.add("bobot" + nbobot, bobot);
        qsb.add("idbobot" + nbobot, idBobot);
        qsb.add("idjenisujian" + nbobot, idJenisUjian);
        qsb.add("isdel" + nbobot, isDel);
    }
    qsb.add("nbobot", nbobot);

    if (nbobot === 0)
    {
        showToastErrorTop("Bobot harus diisi minimal 1!");
        valid = false;
    }

    if (!valid) 
        return;

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "aturanbobot.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);
            
            let lsResponse = $.parseJSON(json);
            if (parseInt(lsResponse[0]) < 0)
            {
                alert(lsResponse[1]);
                return;
            }

            showToastSuccessTop("Berhasil simpan");
            opener.onDataChanged();
            window.close();
        },
        error: function(xhr)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);

            alert(xhr.responseText);
        }
    });
}