$(document).ready(function() {
    tinymce.init({
            selector: 'textarea',
            license_key: 'gpl',
            height: 180,          // Sets the height to exactly 300 pixels
            menubar: false,       // Hides the top File/Edit menus
            statusbar: false,     // Hides the bottom path and counter bar
            toolbar: 'undo redo | bold italic underline |  forecolor backcolor | removeformat',
        });    
    
    if ($("#tableDaftar").length)
        Tables('tableDaftar', 1, 0);
})

function showKomentarPelajaranDialog(index)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kodeaspek", "kdaspek" + index);
    qsb.addInput("namaaspek", "nmaspek" + index);
    qsb.add("mode", "select");
    qsb.add("index", index);

    newWindow('../../referensi/komenpel.dialog.php?' + qsb.createQs() , 'PilihKomenPelajaran', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function simpanTemplateKomentarPelajaran(index)
{
    let komentar = tinymce.get("komentar" + index).getContent();
    komentar = $.trim(komentar);
    if (komentar.length < 10)
    {
        alert("Komentar harus minimal 10 karakter");
        tinymce.get("komentar" + index).focus();
        return;
    }

    let komentar64 = btoa(komentar);

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kodeaspek", "kdaspek" + index);
    qsb.addInput("namaaspek", "nmaspek" + index);
    qsb.add("jenis", "nilai");
    qsb.add("index", index);
    qsb.add("komentar64", komentar64);

    newWindow('komentar.template.dialog.php?' + qsb.createQs() , 'SimpanTemplatKomentar', '760', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function acceptKomentarPelajaran(index, data64)
{
    let lsData = JSON.parse(atob(data64));

    let replid = lsData[0];
    let komenpel = lsData[1];
    let urutan = lsData[2];

    let element = "komentar" + index;
    tinymce.get(element).setContent(komenpel);
}

function pilihKomentarPelajaran(index)
{
    let qsb = new QsBuilder();
    qsb.add("op", "pilih");
    qsb.add("replid", $("#komenpel" + index).val());

     $.ajax({
        url: "rapor.komentar.form.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data64)
        {
            let komenpel = atob(data64);
            let element = "komentar" + index;
            tinymce.get(element).setContent(komenpel);
            tinymce.get(element).focus();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function refreshKomentarPelajaran(index)
{
    let spKomentarPelajaran = $("#spKomentarPelajaran" + index);
    spKomentarPelajaran.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kdaspek", "kdaspek" + index);
    qsb.add("index", index);
    qsb.add("op", "listkomenpel");
    
    $.ajax({
        url: "rapor.komentar.form.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKomentarPelajaran.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function simpan()
{
    let naspek = $("#naspek").val();
    
    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.add("naspek", naspek);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("semester", "semester");

    for(let i = 0; i < naspek; i++)
    {
        let komentar = tinymce.get("komentar" + i).getContent();
        komentar = $.trim(komentar);
        if (komentar.length == 0)
        {
            alert("Komentar belum ditentukan");
            tinymce.get("komentar" + i).focus();
            return;
        }
        let komentar64 = btoa(komentar);
        
        qsb.addInput("kodeaspek" + i, "kdaspek" + i);
        qsb.addInput("namaaspek" + i, "nmaspek" + i);
        qsb.addInput("idnap" + i, "idnap" + i);
        qsb.add("komentar64" + i, komentar64);
    }

    let btSimpan = $("#btSimpan");
    btSimpan.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "rapor.komentar.form.ajax.php",
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
            
            showToastSuccessBottom("Komentar telah tersimpan");

            parent.siswa.location.reload();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            $("#dvLoading").hide();
        }
    })
}

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("kelas", "kelas");
    
    newWindow("rapor.komentar.form.cetak.php?" + qsb.createQs(), "CetakKomentarNilaiRapor", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableDaftar").length)
            return $("#dvTableDaftar").html();

        return "-";
    }
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
    //document.location.href = "../../dashboard/dashboard.php?replid=" + replid;
}

function profilSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
