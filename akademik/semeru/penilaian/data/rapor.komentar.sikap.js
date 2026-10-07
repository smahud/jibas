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

function showKomentarSikapDialog(index)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kodejenis", "kodejenis" + index);
    qsb.addInput("namajenis", "namajenis" + index);
    qsb.add("mode", "select");
    qsb.add("index", index);

    newWindow('../../referensi/komensikap.dialog.php?' + qsb.createQs() , 'PilihKomenSikap', '600', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function acceptKomentarSikap(index, data64)
{
    let lsData = JSON.parse(atob(data64));

    let replid = lsData[0];
    let komensikap = lsData[1];
    let urutan = lsData[2];

    let element = "komentar" + index;
    tinymce.get(element).setContent(komensikap);
}

function refreshKomentarSikap(index)
{
    let spKomentarSikap = $("#spKomentarSikap" + index);
    spKomentarSikap.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("kodejenis", "kodejenis" + index);
    qsb.add("index", index);
    qsb.add("op", "listkomensikap");
    
    $.ajax({
        url: "rapor.komentar.sikap.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spKomentarSikap.html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function pilihKomentarSikap(index)
{
    let qsb = new QsBuilder();
    qsb.add("op", "pilih");
    qsb.add("replid", $("#komensikap" + index).val());
    qsb.addInput("kodejenis", "kodejenis" + index);

     $.ajax({
        url: "rapor.komentar.sikap.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data64)
        {
            
            let komensikap = atob(data64);
            let element = "komentar" + index;
            tinymce.get(element).setContent(komensikap);
            tinymce.get(element).focus();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function simpan()
{
    let njenis = $("#njenis").val();
    
    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.add("njenis", njenis);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");

    for(let i = 0; i < njenis; i++)
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
        
        qsb.addInput("kodejenis" + i, "kodejenis" + i);
        qsb.addInput("namajenis" + i, "namajenis" + i);
        qsb.addInput("idkomenrapor" + i, "idkomenrapor" + i);
        qsb.addInput("predikat" + i, "predikat" + i);
        qsb.add("komentar64" + i, komentar64);
    }

    let btSimpan = $("#btSimpan");
    btSimpan.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "rapor.komentar.sikap.ajax.php",
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

function simpanTemplateKomentarSikap(index)
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
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kodejenis", "kodejenis" + index);
    qsb.addInput("namajenis", "namajenis" + index);
    qsb.add("jenis", "sikap");
    qsb.add("index", index);
    qsb.add("komentar64", komentar64);

    newWindow('komentar.template.dialog.php?' + qsb.createQs() , 'SimpanTemplatKomentar', '760', '550', 'resizable=1,scrollbars=1,status=0,toolbar=0');  
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kelas", "kelas");
    
    newWindow("rapor.komentar.sikap.cetak.php?" + qsb.createQs(), "CetakKomentarSikap", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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
