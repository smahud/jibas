function showSearchGuru()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");

    newWindow("../../library/daftarguru.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptGuru(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nipguru").val(data.NIP);
    $("#namaguru").val(data.Nama);
}

function onStatusChanged(cnt, status, bgColor)
{
    for(let i = 0; i < 5; i++)
    {
        $("#row" + i + cnt).css("background-color", "#ffffff");
    }
    $("#row" + status + cnt).css("background-color", bgColor);
}

function simpan()
{
    let isValid = Vldr.IsHaveInput('nipguru', 'Guru') &&
                  Vldr.HasOption('statusguru', "Status Guru") &&
                  Vldr.IsNumeric('jumlah', 'Jumlah Jam Mengajar') &&
                  Vldr.IsInteger('telat', 'Keterlambatan') &&
                  Vldr.IsHaveInput('materi', 'Materi') &&
                  confirm("Data sudah benar?");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("idpresensi", "idpresensi");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("nipguru", "nipguru");
    qsb.addInput("namaguru", "namaguru");
    qsb.addInput("statusguru", "statusguru");
    qsb.addInput("jumlah", "jumlah");
    qsb.addInput("telat", "telat");
    qsb.addInput("materi", "materi");
    qsb.addInput("refleksi", "refleksi");
    qsb.addInput("keterangan", "keterangan");
    qsb.addInput("tanggal", "tanggal");
    qsb.addInput("waktu", "waktu");
    qsb.addInput("nsiswa", "nsiswa");

    let nSiswa = parseInt($("#nsiswa").val());
    for(let i = 1; i <= nSiswa; i++)
    {
        let value = $('input[name="status' + i + '"]:checked').val();
        qsb.add("status" + i, value);
        qsb.addInput("catatan" + i, "catatan" + i);
        qsb.addInput("idpp" + i, "idpp" + i);
        qsb.addInput("nis" + i, "nis" + i);
    }

    let btSimpan = $("#btSimpan");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "inputpp.content.ajax.php",
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
    document.location.reload();
}

function showInfoSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InfoSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus()
{
    if (!confirm("HAPUS DATA PRESENSI PELAJARAN?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.addInput("idpresensi", "idpresensi");

    let btSimpan = $("#btSimpan");
    let btHapus = $("#btHapus");

    btSimpan.prop("disabled", true);
    btHapus.prop("disabled", true);
    $("#dvLoading").show();

    $.ajax({
        url: "inputpp.content.ajax.php",
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

            document.location.href = "blank.php";
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

function showDashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}