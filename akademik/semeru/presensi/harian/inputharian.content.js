$(document).ready(function()
{
    if ($("#tableRekapInput").length)
        Tables('tableRekapInput', 1, 0);

    let event = $("#event").val();
    if (event == "ondatasaved")
    {
        let idPresensi = $("#idpresensi").val();
        showInputForm(idPresensi);

        showToastSuccessTop("Berhasil");
    }
});

function showInputForm(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtahunajaran", "idtahunajaran");

    let dvInputForm = $("#dvInputForm");
    dvInputForm.html("memuat ..");

    $.ajax({
        url: "inputharian.content.form.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            dvInputForm.html(data);

            if ($("#tableInput").length)
                Tables('tableInput', 1, 0);

            applyAllRowColor();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeAwal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tanggalawal");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");

    let spTanggalAwal = $("#spTanggalAwal");
    spTanggalAwal.html("memuat ..");

    $.ajax({
        url: "inputharian.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spTanggalAwal.html(data);

            dayNameAwal();

            countHariAktif(); 
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onChangeAkhir()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tanggalakhir");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");

    let spTanggalAkhir = $("#spTanggalAkhir");
    spTanggalAkhir.html("memuat ..");

    $.ajax({
        url: "inputharian.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spTanggalAkhir.html(data);

            dayNameAkhir();

            countHariAktif(); 
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function countHariAktif()
{
    let qsb = new QsBuilder();
    qsb.add("op", "counthariaaktif");
    qsb.addInput("tahunawal", "tahunawal");
    qsb.addInput("bulanawal", "bulanawal");
    qsb.addInput("tanggalawal", "tanggalawal");
    qsb.addInput("tahunakhir", "tahunakhir");
    qsb.addInput("bulanakhir", "bulanakhir");
    qsb.addInput("tanggalakhir", "tanggalakhir");
    qsb.add("hariaktif", 0);

    qsb.showConsoleLog();

    let spHariAktif = $("#spHariAktif");
    spHariAktif.html("memuat ..");

    $.ajax({
        url: "inputharian.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spHariAktif.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function applyAll(jenis)
{
    let nilai = $("#def" + jenis).val();
    if (isNaN(nilai))
        return;

    nilai = parseInt(nilai);
    if (nilai < 0)
        return;

    let nSiswa = parseInt($("#nsiswa").val());
    let hariAktif = parseInt($("#hariaktif").val());

    if (nilai > hariAktif)
    {
        alert("Nilai " + jenis + " tidak boleh melebihi jumlah hari aktif.");
        return;
    }

    for (let cnt = 1; cnt <= nSiswa; cnt++)
    {
        $("#" + jenis + cnt).val(nilai);
    }

    applyAllRowColor();
}

function validateInput()
{
    let jenis = ["hadir","ijin","sakit","cuti","alpa"];
    let nSiswa = parseInt($("#nsiswa").val());
    let hariAktif = parseInt($("#hariaktif").val());

    for (let i = 1; i <= nSiswa; i++)
    {
        let checked = $("#exclude" + i).prop("checked");
        let exclude = checked ? 1 : 0;
        if (exclude == 0)
        {
            let total = 0;
            for (let j = 0; j < jenis.length; j++)
            {
                let el = $("#" + jenis[j] + i);
                let nilai = parseInt(el.val());
                if (isNaN(nilai))
                {
                    alert("Input harus berupa angka!");
                    el.focus();
                    return false;
                }

                if (nilai < 0)
                {
                    alert("Input tidak boleh negatif!");
                    el.focus();
                    return false;
                }
                
                if (nilai > hariAktif)
                {
                    alert("Input tidak boleh melebihi jumlah hari aktif!");
                    el.focus();
                    return false;
                }
                total += nilai;
            }

            if (total !== hariAktif)
            {
                alert("Total input harus sama dengan jumlah hari aktif!");
                $("#hadir" + i).focus();
                return false;
            }
        }
    }

    return true;
}

function simpan()
{
    if (!validateInput())
        return;

    if (!confirm("Data sudah benar?"))
        return;

    let tanggal1 = parseInt($("#tahunawal").val()) + "-" + $("#bulanawal").val() + "-" + $("#tanggalawal").val();
    let tanggal2 = parseInt($("#tahunakhir").val()) + "-" + $("#bulanakhir").val() + "-" + $("#tanggalakhir").val();

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.add("tanggal1", tanggal1);
    qsb.add("tanggal2", tanggal2);
    qsb.addInput("hariaktif", "hariaktif");
    qsb.addInput("nsiswa", "nsiswa");

    let jenis = ["hadir","ijin","sakit","cuti","alpa"];
    let nSiswa = parseInt($("#nsiswa").val());

    for (let i = 1; i <= nSiswa; i++)
    {
        qsb.addInput("nis" + i, "nis" + i);
        qsb.addInput("idph" + i, "idph" + i);

        let checked = $("#exclude" + i).prop("checked");
        let exclude = checked ? 1 : 0;
        qsb.add("exclude" + i, exclude);

        if (exclude == 0)
        {
            for (let j = 0; j < jenis.length; j++)
            {
                qsb.addInput(jenis[j] + i, jenis[j] + i);
            }    
            qsb.addInput("ket" + i, "ket" + i);
        }
        else 
        {
            for (let j = 0; j < jenis.length; j++)
            {
                qsb.add(jenis[j] + i, 0);
            }    
            qsb.add("ket" + i, "");
        }
    }

    let btSimpan = $("#btSimpan");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "inputharian.content.ajax.php",
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

            let state = ls[1];
            let replid = ls[2];

            reloadPage(state, replid);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btHapus.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });
}

function reloadPage(state, replid)
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");        
    qsb.addInput("kelas", "kelas");        
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
    qsb.add("event", "ondatasaved");
    qsb.add("idpresensi", replid);

    location.href = "inputharian.content.php?" + qsb.createQs();
}

function toggleExclude(cnt)
{
    let checked = $("#exclude" + cnt).prop("checked");
    if (checked)
    {
        $("#hadir" + cnt).hide();
        $("#ijin" + cnt).hide();
        $("#sakit" + cnt).hide();
        $("#cuti" + cnt).hide();
        $("#alpa" + cnt).hide();
        $("#ket" + cnt).hide();
    }
    else
    {
        $("#hadir" + cnt).show();
        $("#ijin" + cnt).show();
        $("#sakit" + cnt).show();
        $("#cuti" + cnt).show();
        $("#alpa" + cnt).show();
        $("#ket" + cnt).show();
    }
}

function dayNameAwal()
{
    let qsb = new QsBuilder();
    qsb.add("op", "dayname");
    qsb.addInput("tahun", "tahunawal");
    qsb.addInput("bulan", "bulanawal");
    qsb.addInput("tanggal", "tanggalawal");

    let spHariAwal = $("#spHariAwal");
    spHariAwal.html("memuat ..");

    $.ajax({
        url: "inputharian.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spHariAwal.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });   
}

function dayNameAkhir()
{
    let qsb = new QsBuilder();
    qsb.add("op", "dayname");
    qsb.addInput("tahun", "tahunakhir");
    qsb.addInput("bulan", "bulanakhir");
    qsb.addInput("tanggal", "tanggalakhir");

    let spHariAkhir = $("#spHariAkhir");
    spHariAkhir.html("memuat ..");

    $.ajax({
        url: "inputharian.content.ajax.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            spHariAkhir.html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });   
}

function showInfoSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InfoSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus()
{
    if (!confirm("HAPUS DATA PRESENSI HARIAN INI?"))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.addInput("replid", "replid");

    let btSimpan = $("#btSimpan");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "inputharian.content.ajax.php",
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

            let state = ls[1];
            let replid = ls[2];

            reloadPage(state, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            btSimpan.prop("disabled", false);
            btHapus.prop("disabled", false);
            $("#dvLoading").hide();
        }
    });   
}

function applyAllRowColor()
{
    let nData = parseInt($("#ndata").val());
    for (let i = 1; i <= nData; i++)
    {
        applyRowColor(i);
    }
}

function applyRowColor(row)
{
    let lsJenis = ["hadir","ijin","sakit","alpa","cuti"];
    let lsColor = ["#d0eefc", "#caf1d4", "#ecd9ea", "#e9caca", "#ebe2ce"];

    for (let i = 0; i < lsJenis.length; i++)
    {
        let el = $("#" + lsJenis[i] + row);
        let val = el.val();
        if (isNaN(val))
        {
            $("#td" + lsJenis[i] + row).css("background-color", "transparent");
            continue;
        }
        
        if (val > 0)
        {
            $("#td" + lsJenis[i] + row).css("background-color", lsColor[i]);
        }
        else
        {
            $("#td" + lsJenis[i] + row).css("background-color", "transparent");
        }
    }
}

function showDashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}