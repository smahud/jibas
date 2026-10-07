$(document).ready(function() {
    if ($("#tableDaftar").length)
        Tables('tableDaftar', 1, 0);

    if ($("#tableBobot").length)
        Tables('tableBobot', 1, 0);
})

function tambah()
{
    
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
       
    newWindow('pendataan.nilai.dialog.php?' + qsb.createQs(),'TambahNilai','880','600','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh()
{
    document.location.reload();
    parent.jenisujian.refresh();
}

function ubah_nilai(dataNilai64)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.add("datanilai64", dataNilai64);
       
    newWindow('pendataan.ubahnilai.dialog.php?' + qsb.createQs(),'UbahNilai','500','400','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah_nilai(idUjian, nis, nama)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.add("idujian", idUjian);
    qsb.add("nis", nis);
    qsb.add("nama", nama);

    newWindow('pendataan.tambahnilai.dialog.php?' + qsb.createQs(),'TambahNilai','500','400','resizable=1,scrollbars=1,status=0,toolbar=0');
}


function hitungUlangRata()
{
    if (!confirm("Hitung ulang rerata nilai siswa & kelas?"))
        return;

    let btRecountRerata = $("#btRecountRerata");
    btRecountRerata.prop("disabled", true);
    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "recountrerata");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idaturan", "idaturannhb");

    $.ajax({
        url: "pendataan.daftar.ajax.php",
        data: qsb.createQs(),
        method: "POST",
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
            btRecountRerata.prop("disabled", false);
            $("#dvLoading").hide();
        }
    })
    
    
}

function ubah_nau(dataNau64)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.add("datanau64", dataNau64);
       
    newWindow('pendataan.ubahnau.dialog.php?' + qsb.createQs(),'UbahNau','500','400','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ubah_info(idUjian)
{
    let qsb = new QsBuilder();
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.add("idujian", idUjian);

    newWindow('pendataan.ubahinfo.dialog.php?' + qsb.createQs(),'UbahInfoNilai','500','400','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus_nilai_ujian(idUjian, judul)
{
    if (!confirm("Hapus nilai ujian " + judul + "?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapusnilaiujian");
    qsb.add("idujian", idUjian);
    qsb.addInput("idaturan", "idaturannhb");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idsemester", "idsemester");
    
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.daftar.ajax.php",
        data: qsb.createQs(),
        method: "POST",
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
            $("#dvLoading").hide();
        }
    })
}

function hapus_nau()
{
    let jenisUjian = $("#jenisujian").val();
    if (!confirm("Hapus nilai akhir " + jenisUjian + "?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapusnau");
    qsb.addInput("idaturan", "idaturannhb");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idsemester", "idsemester");
    
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.daftar.ajax.php",
        data: qsb.createQs(),
        method: "POST",
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
            $("#dvLoading").hide();
        }
    })
}

function ck_bobot_onclick(no)
{
    let checked = $("#ckbobot" + no).is(":checked");
    if (checked)
    {
        $("#bobot" + no).prop("disabled", false);
        $("#bobot" + no).css("background-color", "#fff");
        $("#bobot" + no).focus();
    }
    else
    {
        $("#bobot" + no).prop("disabled", true);
        $("#bobot" + no).css("background-color", "#ccc");
    }
}

function hitungNauManual()
{
     let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("jenisujian", "jenisujian");
    qsb.addInput("idaturannhb", "idaturannhb");
    qsb.addInput("aspek", "aspek");
    qsb.addInput("namaaspek", "namaaspek");

    parent.daftar.location.href = "pendataan.manual.php?" + qsb.createQs();
}

function hitungNauOtomatis()
{
    let qsb = new QsBuilder();

    let nChecked = 0;
    let nBobot = parseInt($("#nbobot").val());
    qsb.add("nbobot", nBobot);
    for(let i = 1; i <= nBobot; i++)
    {
        qsb.addInput("idujian" + i, "idujian" + i);
        qsb.addInput("idbobot" + i, "idbobot" + i);

        let checked = $("#ckbobot" + i).is(":checked");
        if (!checked)
        {
            qsb.add("ckbobot" + i, 0);    
        }
        else 
        {
            let bobot = $.trim($("#bobot" + i).val());
            if (bobot === "")
            {
                alert("Anda belum memasukan bobot");
                $("#bobot" + i).focus();
                return;
            }

            if (isNaN(bobot))
            {
                alert("Bobot harus berupa angka");
                $("#bobot" + i).focus();
                return;
            }

            if (bobot < 1 || bobot > 100)
            {
                alert("Bobot harus antara 1 sampai 100");
                $("#bobot" + i).focus();
                return;
            }

            let num = Number(bobot);
            if (!Number.isInteger(num))
            {
                alert("Bobot harus berupa bilangan bulat");
                $("#bobot" + i).focus();
                return;
            }
            
            nChecked += 1;
            qsb.add("ckbobot" + i, 1);    
            qsb.add("bobot" + i, bobot);
        }
    }
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idaturan", "idaturannhb");
    qsb.addInput("idjenisujian", "idjenisujian");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.add("op", "hitungnauotomatis");

    if (nChecked == 0)
    {
        alert("Anda perlu memilih salah satu ujian untuk dihitung.");
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    let btHitungOtomatis = $("#btHitungOtomatis");
    btHitungOtomatis.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "pendataan.daftar.ajax.php",
        data: qsb.createQs(),
        method: "POST",
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
            btHitungOtomatis.prop("disabled", false);
            $("#dvLoading").hide();
        }
    })
    
}

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}


function detailSiswa(replid) 
{
	newWindow('../../siswa/siswa.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
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
    qsb.addInput("jenisujian", "jenisujian");
    
    newWindow("pendataan.daftar.cetak.php?" + qsb.createQs(), "CetakLaporanPendataanNilai", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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