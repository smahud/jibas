$(document).ready(function() {
    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpanAturanGrading()
{
    if ($("#aspek option").length === 0)
        return;

    let isValid = true;
    let nEmpty = 0;
    for(let i = 1; i <= 10; i++)
    {
        let nmin = $.trim($("#nmin"+i).val());
        let nmax = $.trim($("#nmax"+i).val());
        let grade = $.trim($("#grade"+i).val());

        if (nmin.length === 0 && nmax.length === 0 && grade.length === 0)
        {
            nEmpty += 1;
            continue;
        }

        if (nmin.length === 0)
        {
            alert("Nilai minimum belum diisi");
            isValid = false;
            $("#nmin"+i).focus();
            return;
        }

        if (isNaN(nmin))
        {
            alert("Nilai minimum harus berupa angka");
            isValid = false;
            $("#nmin"+i).focus();
            return;
        }

        if (parseFloat(nmin) < 0)
        {
            alert("Nilai minimum tidak boleh negatif");
            isValid = false;
            $("#nmin"+i).focus();
            return;
        }

        if (nmax.length === 0)
        {
            alert("Nilai maksimum belum diisi");
            isValid = false;
            $("#nmax"+i).focus();
            return;
        }

        if (isNaN(nmax))
        {
            alert("Nilai maksimum harus berupa angka");
            isValid = false;
            $("#nmax"+i).focus();
            return;
        }

        if (parseFloat(nmax) < 0)
        {
            alert("Nilai maksimum tidak boleh negatif");
            isValid = false;
            $("#nmax"+i).focus();
            return;
        }

        if (parseFloat(nmin) > parseFloat(nmax))
        {
            alert("Nilai minimum harus lebih kecil dari nilai maksimum");
            isValid = false;
            $("#nmin"+i).focus();
            return;
        }

        if (grade.length === 0)
        {
            alert("Grade belum diisi");
            isValid = false;
            $("#grade"+i).focus();
            return;
        }
    }

    if (nEmpty == 10)
    {
        alert("Masukkan minimal 1 baris data");
        isValid = false;
    }

    if (!isValid) 
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("nip", "nip");
    qsb.addInput("aspek", "aspek");

    let n = 0;
    for (let i = 1; i <= 10; i++)
    {
        let nmin = $.trim($("#nmin"+i).val());
        let nmax = $.trim($("#nmax"+i).val());
        let grade = $.trim($("#grade"+i).val());

        if (nmin.length === 0 && nmax.length === 0 && grade.length === 0)
            continue;

        n += 1;
        qsb.add("nmin"+n, nmin);
        qsb.add("nmax"+n, nmax);
        qsb.add("grade"+n, grade);
    }
    qsb.add("ngrade", n);

    let btnSimpan = $("#btnSimpan");
    let btnTutup = $("#btnTutup");

    btnSimpan.prop("disabled", true);
    btnTutup.prop("disabled", true);

    $.ajax({
        url: "aturangrading.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            btnSimpan.prop("disabled", false);
            btnTutup.prop("disabled", false);

            let lsResponse = JSON.parse(json);
            if (parseInt(lsResponse[0]) < 1)
            {
                alert(lsResponse[1]);
                return;
            }
            
            opener.onDataChanged();
            window.close();
        },
        error: function(xhr, status, error)
        {
            alert(error);
        }   
    })
}