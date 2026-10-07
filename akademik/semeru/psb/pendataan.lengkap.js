$(document).ready(function()
{
    let replid = parseInt($("#replid").val());

    if (replid === 0)
        $("#row_nopendaftaran").css("display", "none");

    $("#nama").focus();
})

function showAgamaDialog()
{
    newWindow('../referensi/agama.dialog.php?mode=manage', 'PilihAgama', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshAgama()
{
    let spAgama = $("#spAgama");
    spAgama.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=agama",
        success: function(data)
        {
            spAgama.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showSukuDialog()
{
    newWindow('../referensi/suku.dialog.php?mode=manage', 'PilihSuku', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshSuku()
{
    let spSuku = $("#spSuku");
    spSuku.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=suku",
        success: function(data)
        {
            spSuku.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showStatusSiswaDialog()
{
    newWindow('../referensi/statussiswa.dialog.php?mode=manage', 'PilihStatusSiswa', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshStatusSiswa()
{
    let spStatusSiswa = $("#spStatusSiswa");
    spStatusSiswa.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=statussiswa",
        success: function(data)
        {
            spStatusSiswa.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showKondisiSiswaDialog()
{
    newWindow('../referensi/kondisisiswa.dialog.php?mode=manage', 'PilihKondisiSiswa', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshKondisiSiswa()
{
    let spKondisiSiswa = $("#spKondisiSiswa");
    spKondisiSiswa.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=kondisisiswa",
        success: function(data)
        {
            spKondisiSiswa.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeJenjangSekolah()
{
    let qsb = new QsBuilder();
    qsb.add("op", "asalsekolah");
    qsb.addInput("jenjangsekolah", "jenjangsekolah");

    let spAsalSekolah = $("#spAsalSekolah");
    spAsalSekolah.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spAsalSekolah.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showAsalSekolahDialog()
{
    let qsb = new QsBuilder();
    qsb.add("mode", "manage");
    qsb.addInput("jenjangsekolah", "jenjangsekolah");

    newWindow('../referensi/asalsekolah.dialog.php?' + qsb.createQs(), 'PilihAsalSekolah', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshAsalSekolah()
{
    let qsb = new QsBuilder();
    qsb.add("op", "asalsekolah");
    qsb.addInput("jenjangsekolah", "jenjangsekolah");
    
    let spAsalSekolah = $("#spAsalSekolah");
    spAsalSekolah.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spAsalSekolah.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showPendidikanOrtuDialog()
{
    let qsb = new QsBuilder();
    qsb.add("mode", "manage");
    newWindow('../referensi/pendidikanortu.dialog.php?' + qsb.createQs(), 'PilihPendidikanOrtu', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshPendidikanOrtu()
{
    let spPendidikanAyah = $("#spPendidikanAyah");
    spPendidikanAyah.html("memuat ..");

    let spPendidikanIbu = $("#spPendidikanIbu");
    spPendidikanIbu.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=pendidikanortu",
        success: function(data)
        {
            let data2 = data.replaceAll("ID_ELEMENT", "pendidikanayah");
            spPendidikanAyah.html(data2).hide().fadeIn(300);

            data2 = data.replaceAll("ID_ELEMENT", "pendidikanibu");
            spPendidikanIbu.html(data2).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showPekerjaanOrtuDialog()
{
    let qsb = new QsBuilder();
    qsb.add("mode", "manage");
    newWindow('../referensi/pekerjaanortu.dialog.php?' + qsb.createQs(), 'PilihPekerjaanOrtu', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function refreshPekerjaanOrtu()
{
    let spPekerjaanAyah = $("#spPekerjaanAyah");
    spPekerjaanAyah.html("memuat ..");

    let spPekerjaanIbu = $("#spPekerjaanIbu");
    spPekerjaanIbu.html("memuat ..");

    $.ajax({
        url: "pendataan.lengkap.ajax.php",
        method: "POST",
        data: "op=pekerjaanortu",
        success: function(data)
        {
            let data2 = data.replaceAll("ID_ELEMENT", "pekerjaanayah");
            spPekerjaanAyah.html(data2).hide().fadeIn(300);

            data2 = data.replaceAll("ID_ELEMENT", "pekerjaanibu");
            spPekerjaanIbu.html(data2).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}


function validateInputs()
{
    let isValid = Vldr.IsNotEmpty("nama", "Nama Calon Siswa") &&
                  Vldr.InputText("nama", "Nama Calon Siswa", 3, 255);
    if (!isValid) 
        return false;

    let tglLahir = $.trim($("#tgllahir").val());
    if (tglLahir != "") 
    {
        isValid = Vldr.IsInteger("tgllahir", "Tanggal Lahir") &&
                  Vldr.IsPositive("tgllahir", "Tanggal Lahir") &&
                  Vldr.IsInRangeInteger("tgllahir", "Tanggal Lahir", 1, 31);

        if (!isValid) 
            return false;
    }                

    let blnLahir = $.trim($("#blnlahir").val());
    if (blnLahir != "") 
    {
        isValid = Vldr.IsInteger("blnlahir", "Bulan Lahir") &&
                  Vldr.IsPositive("blnlahir", "Bulan Lahir") &&
                  Vldr.IsInRangeInteger("blnlahir", "Bulan Lahir", 1, 12);

        if (!isValid) 
            return false;
    }                

    let thnLahir = $.trim($("#thnlahir").val());
    if (thnLahir != "") 
    {
        isValid = Vldr.IsInteger("thnlahir", "Tahun Lahir") &&
                  Vldr.IsPositive("thnlahir", "Tahun Lahir") &&
                  Vldr.IsInRangeInteger("thnlahir", "Tahun Lahir", 1900, 2200);

        if (!isValid) 
            return false;
    }                

    let tglLahirAyah = $.trim($("#tgllahirayah").val());
    if (tglLahirAyah != "") 
    {
        isValid = Vldr.IsInteger("tgllahirayah", "Tanggal Lahir Ayah") &&
                  Vldr.IsPositive("tgllahirayah", "Tanggal Lahir Ayah") &&
                  Vldr.IsInRangeInteger("tgllahirayah", "Tanggal Lahir Ayah", 1, 31);

        if (!isValid) 
            return false;
    }                

    let blnLahirAyah = $.trim($("#blnlahirayah").val());
    if (blnLahirAyah != "") 
    {
        isValid = Vldr.IsInteger("blnlahirayah", "Bulan Lahir Ayah") &&
                  Vldr.IsPositive("blnlahirayah", "Bulan Lahir Ayah") &&
                  Vldr.IsInRangeInteger("blnlahirayah", "Bulan Lahir Ayah", 1, 12);

        if (!isValid) 
            return false;
    }                

    let thnLahirAyah = $.trim($("#thnlahirayah").val());
    if (thnLahirAyah != "") 
    {
        isValid = Vldr.IsInteger("thnlahirayah", "Tahun Lahir Ayah") &&
                  Vldr.IsPositive("thnlahirayah", "Tahun Lahir Ayah") &&
                  Vldr.IsInRangeInteger("thnlahirayah", "Tahun Lahir Ayah", 1900, 2200);
                  
        if (!isValid) 
            return false;
    }                

    let tglLahirIbu = $.trim($("#tgllahiribu").val());
    if (tglLahirIbu != "") 
    {
        isValid = Vldr.IsInteger("tgllahiribu", "Tanggal Lahir Ibu") &&
                  Vldr.IsPositive("tgllahiribu", "Tanggal Lahir Ibu") &&
                  Vldr.IsInRangeInteger("tgllahiribu", "Tanggal Lahir Ibu", 1, 31);

        if (!isValid) 
            return false;
    }                

    let blnLahirIbu = $.trim($("#blnlahiribu").val());
    if (blnLahirIbu != "") 
    {
        isValid = Vldr.IsInteger("blnlahiribu", "Bulan Lahir Ibu") &&
                  Vldr.IsPositive("blnlahiribu", "Bulan Lahir Ibu") &&
                  Vldr.IsInRangeInteger("blnlahiribu", "Bulan Lahir Ibu", 1, 12);

        if (!isValid) 
            return false;
    }                

    let thnLahirIbu = $.trim($("#thnlahiribu").val());
    if (thnLahirIbu != "") 
    {
        isValid = Vldr.IsInteger("thnlahiribu", "Tahun Lahir Ibu") &&
                  Vldr.IsPositive("thnlahiribu", "Tahun Lahir Ibu") &&
                  Vldr.IsInRangeInteger("thnlahiribu", "Tahun Lahir Ibu", 1900, 2200);
                  
        if (!isValid) 
            return false;
    }               

    let sum1 = rupiahToNumber($.trim($("#sum1").val()));
    if (sum1 != "") 
    {
        isValid = Vldr.IsIntegerValue(sum1, "Jumlah Sumbangan 1") &&
                  Vldr.IsPositiveValue(sum1, "Jumlah Sumbangan 1");
                  
        if (!isValid) 
        {
            $("#sum1").focus();
            return false;
        }
        else           
        {
            $("#sum1").val(sum1);  
        }
    }   
    else 
    {
        $("#sum1").val("");  
    }
    
    let sum2 = rupiahToNumber($.trim($("#sum2").val()));
    if (sum2 != "") 
    {
        isValid = Vldr.IsIntegerValue(sum2, "Jumlah Sumbangan 2") &&
                  Vldr.IsPositiveValue(sum2, "Jumlah Sumbangan 2");
                  
        if (!isValid) 
        {
            $("#sum2").focus();
            return false;
        }
        else           
        {
            $("#sum2").val(sum2);  
        }
    }       
    else 
    {
        $("#sum2").val("");  
    }

    let ujian1 = $.trim($("#ujian1").val());
    if (ujian1 != "") 
    {
        isValid = Vldr.IsNumeric("ujian1", "Nilai Ujian 1") &&
                  Vldr.IsZeroPositive("ujian1", "Nilai Ujian 1") &&
                  Vldr.IsInRangeNumeric("ujian1", "Nilai Ujian 1", 0, 100);
                  
        if (!isValid) 
            return false;
    }       

    let ujian2 = $.trim($("#ujian2").val());
    if (ujian2 != "") 
    {
        isValid = Vldr.IsNumeric("ujian2", "Nilai Ujian 2") &&
                  Vldr.IsZeroPositive("ujian2", "Nilai Ujian 2") &&
                  Vldr.IsInRangeNumeric("ujian2", "Nilai Ujian 2", 0, 100);
                  
        if (!isValid) 
            return false;
    }       

    let ujian3 = $.trim($("#ujian3").val());
    if (ujian3 != "") 
    {
        isValid = Vldr.IsNumeric("ujian3", "Nilai Ujian 3") &&
                  Vldr.IsZeroPositive("ujian3", "Nilai Ujian 3") &&
                  Vldr.IsInRangeNumeric("ujian3", "Nilai Ujian 3", 0, 100);
                  
        if (!isValid) 
            return false;
    }       

    let ujian4 = $.trim($("#ujian4").val());
    if (ujian4 != "") 
    {
        isValid = Vldr.IsNumeric("ujian4", "Nilai Ujian 4") &&
                  Vldr.IsZeroPositive("ujian4", "Nilai Ujian 4") &&
                  Vldr.IsInRangeNumeric("ujian4", "Nilai Ujian 4", 0, 100);
                  
        if (!isValid) 
            return false;
    }      

    let ujian5 = $.trim($("#ujian5").val());
    if (ujian5 != "") 
    {
        isValid = Vldr.IsNumeric("ujian5", "Nilai Ujian 5") &&
                  Vldr.IsZeroPositive("ujian5", "Nilai Ujian 5") &&
                  Vldr.IsInRangeNumeric("ujian5", "Nilai Ujian 5", 0, 100);
                  
        if (!isValid) 
            return false;
    }      

    let ujian6 = $.trim($("#ujian6").val());
    if (ujian6 != "") 
    {
        isValid = Vldr.IsNumeric("ujian6", "Nilai Ujian 6") &&
                  Vldr.IsZeroPositive("ujian6", "Nilai Ujian 6") &&
                  Vldr.IsInRangeNumeric("ujian6", "Nilai Ujian 6", 0, 100);
                  
        if (!isValid) 
            return false;
    }  

    let ujian7 = $.trim($("#ujian7").val());
    if (ujian7 != "") 
    {
        isValid = Vldr.IsNumeric("ujian7", "Nilai Ujian 7") &&
                  Vldr.IsZeroPositive("ujian7", "Nilai Ujian 7") &&
                  Vldr.IsInRangeNumeric("ujian7", "Nilai Ujian 7", 0, 100);
                  
        if (!isValid) 
            return false;
    }  

    let ujian8 = $.trim($("#ujian8").val());
    if (ujian8 != "") 
    {
        isValid = Vldr.IsNumeric("ujian8", "Nilai Ujian 8") &&
                  Vldr.IsZeroPositive("ujian8", "Nilai Ujian 8") &&
                  Vldr.IsInRangeNumeric("ujian8", "Nilai Ujian 8", 0, 100);
                  
        if (!isValid) 
            return false;
    }  

    let ujian9 = $.trim($("#ujian9").val());
    if (ujian9 != "") 
    {
        isValid = Vldr.IsNumeric("ujian9", "Nilai Ujian 9") &&
                  Vldr.IsZeroPositive("ujian9", "Nilai Ujian 9") &&
                  Vldr.IsInRangeNumeric("ujian9", "Nilai Ujian 9", 0, 100);
                  
        if (!isValid) 
            return false;
    }  

    let ujian10 = $.trim($("#ujian10").val());
    if (ujian10 != "") 
    {
        isValid = Vldr.IsNumeric("ujian10", "Nilai Ujian 10") &&
                  Vldr.IsZeroPositive("ujian10", "Nilai Ujian 10") &&
                  Vldr.IsInRangeNumeric("ujian10", "Nilai Ujian 10", 0, 100);
                  
        if (!isValid) 
            return false;
    }  

    return true;
}

function Simpan()
{
    if (!validateInputs())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    // 1. Grab the form element
    var form = $('#inputform')[0]; // Must be the raw DOM element, not the jQuery object

    // 2. Instantiate the FormData object
    var formData = new FormData(form);

    formData.append('op', 'simpan');

    // 4. Send via jQuery AJAX
    $.ajax({
        url: 'pendataan.lengkap.simpan.php',
        type: 'POST',
        data: formData,
        processData: false,  // Tell jQuery not to process the data into a string
        contentType: false,  // Tell jQuery not to set a Content-Type header
        success: function(json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorBottom(ls[1]);
                alert(ls[1]);
                return;
            }

            let replid = parseInt($("#replid").val());

            if (replid === 0)
                sessionStorage.setItem('dataSiswaSaved', 'newdata');
            else                 
                sessionStorage.setItem('dataSiswaSaved', 'datachanged');

            window.history.back();            
        },
        error: function(xhr, status, error) 
        {
            alert(xhr.responseText);
        }
    });
}