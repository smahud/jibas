function tambahAturanBobot(idTingkat, tingkat)
{
    let qsb = new QsBuilder();
    qsb.add("mode", "tambah");
    qsb.add("idtingkat", idTingkat);
    qsb.add("tingkat", tingkat);
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("departemen", "departemen");

    newWindow('aturanbobot.dialog.php?'+qsb.createQs(), "TambahAturanBobot", 600, 600, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function editAturanBobot(idTingkat, tingkat, aspek)
{
    let qsb = new QsBuilder();
    qsb.add("mode", "edit");
    qsb.add("idtingkat", idTingkat);
    qsb.add("tingkat", tingkat);
    qsb.add("aspek", aspek);
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("departemen", "departemen");

    newWindow('aturanbobot.dialog.php?'+qsb.createQs(), "EditAturanBobot", 600, 600, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapusAturanBobot(idTingkat, tingkat, aspek)
{
    if (!confirm("Hapus aturan perhitungan nilai rapor ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("idtingkat", idTingkat);
    qsb.add("aspek", aspek);
    qsb.addInput("nip", "nip");
    qsb.addInput("idpelajaran", "idpelajaran");

    $.ajax({
        url: "aturanbobot.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResponse = $.parseJSON(json);
            if (parseInt(lsResponse[0]) < 0)
            {
                alert(lsResponse[1]);
                showToastErrorBottom(lsResponse[1]);
                return;
            }

            showToastSuccessTop("Berhasil hapus");
            onDataChanged();
        }
    });
}

function onDataChanged()
{
    let dvContent = $("#dvContent");
    dvContent.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "content");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    $.ajax({
        url: "aturanbobot.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(result) 
        {
            dvContent.html(result).hide().fadeIn(300);
        },
        error: function (xhr, status, error)
        {
            alert(xhr.responseText);
        }
    });
}

function cetakAturanBobot()
{
    let qsb = new QsBuilder();
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    newWindow("aturanbobot.content.cetak.php?" + qsb.createQs(), "CetakAturanBobot", 790, 650, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvContent").length)
            return $("#dvContent").html();

        return "-";
    }
}

function showHelp()
{
    newWindow('../help/gp_aturanrapor.html?r=' + Math.random(), 'AturanBobotHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function setAktifAturanBobot(replid, newAktif)
{
    let msg = "";
    if (newAktif == 0)
        msg = "NON AKTIF KAN ATURAN INI?";
    else 
        msg = "Aktifkan kembali aturan ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: "aturanbobot.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let lsResponse = $.parseJSON(json);
            if (parseInt(lsResponse[0]) < 0)
            {
                alert(lsResponse[1]);
                return;
            }

            onDataChanged();
        }
    });
}