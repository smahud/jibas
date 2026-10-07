$(document).ready(function() 
{
    $("#nilaikkm").focus();

    if ($("#tableDaftar").length)
        Tables('tableDaftar', 0, 0);
})

function validateInput()
{
    let nSiswa = parseInt($('#nsiswa').val());
    let jumNhb = parseInt($('#jumnhb').val());

    if (nSiswa === 0)
    {
        alert("Data siswa tidak ditemukan. Nilai rapor tidak bisa ditentukan.");
        return false;
    }

    if (jumNhb === 0)
    {
        alert("Data nilai pengujian tidak ditemukan. Nilai rapor tidak bisa ditentukan.");
        return false;
    }

    let nilaiKKM = $.trim($('#nilaikkm').val());
    if (nilaiKKM === "")
    {
        alert("Nilai KKM belum ditentukan");
        $('#nilaikkm').focus();
        return false;
    }

    if (isNaN(nilaiKKM))
    {
        alert("Nilai KKM harus angka");
        $('#nilaikkm').focus();
        return false;
    }

    if (parseFloat(nilaiKKM) < 0 || parseFloat(nilaiKKM) > 100)
    {
        alert("Nilai KKM tidak boleh lebih kecil dari 0 atau lebih besar dari 100");
        $('#nilaikkm').focus();
        return false;
    }

    //cek apakah semua nilai sudah diisi
    for (let i = 1; i <= nSiswa; i++)
    {
        let nilai = $.trim($('#nilaiangka' + i).val());
        if (nilai === "")
        {
            alert("Nilai rapor siswa belum ditentukan");
            $('#nilaiangka' + i).focus();
            return false;
        }

        if (isNaN(nilai))
        {
            alert("Nilai rapor siswa harus angka");
            $('#nilaiangka' + i).focus();
            return false;
        }

        if (parseFloat(nilai) < 0 || parseFloat(nilai) > 100)
        {
            alert("Nilai rapor siswa tidak boleh lebih kecil dari 0 atau lebih besar dari 100");
            $('#nilaiangka' + i).focus();
            return false;
        }
    }
    
    return true;
}

function refresh()
{
    document.location.reload();
}

function recountNilaiRapor()
{
    if (!confirm("Hitung ulang dan simpan nilai rapor?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "recount");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("dasarpenilaian", "dasarpenilaian");
    qsb.addInput("judulpenilaian", "judulpenilaian");

    let btSimpan = $("#btSimpan");
    let btRecount = $("#btRecount");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btRecount.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "rapor.nilai.penentuan.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorTop(ls[1])
                alert(ls[1]);
                return;
            }

            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btRecount.prop("disabled", false);
            btHapus.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
}

function simpanNilaiRapor()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("nilaikkm", "nilaikkm");
    qsb.addInput("idinfo", "idinfo");
    qsb.addInput("nsiswa", "nsiswa");
    let nSiswa = parseInt($('#nsiswa').val());
    for (let i = 1; i <= nSiswa; i++)
    {
        qsb.addInput("nis" + i, "nis" + i);
        qsb.addInput("nilaiangka" + i, "nilaiangka" + i);
        qsb.addInput("nilaihuruf" + i, "nilaihuruf" + i);
    }

    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("dasarpenilaian", "dasarpenilaian");
    qsb.addInput("judulpenilaian", "judulpenilaian");
    
    let btSimpan = $("#btSimpan");
    let btRecount = $("#btRecount");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btRecount.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "rapor.nilai.penentuan.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorTop(ls[1])
                alert(ls[1]);
                return;
            }

            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btRecount.prop("disabled", false);
            btHapus.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
    
}

function deleteNilaiRapor()
{
    if (!confirm("HAPUS NILAI DAN KOMENTAR RAPOR?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "delete");
    qsb.addInput("idinfo", "idinfo");
    
    let btSimpan = $("#btSimpan");
    let btRecount = $("#btRecount");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btRecount.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "rapor.nilai.penentuan.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(json) 
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                showToastErrorTop(ls[1])
                alert(ls[1]);
                return;
            }

            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btRecount.prop("disabled", false);
            btHapus.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
    
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("judulpenilaian", "judulpenilaian");
    
    newWindow("rapor.nilai.penentuan.cetak.php?" + qsb.createQs(), "CetakNilaiPenentuanRapor", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
    //document.location.href = "../../dashboard/dashboard.php?replid=" + replid;
}

function detailSiswa(replid) 
{
	newWindow('../../siswa/siswa.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}
