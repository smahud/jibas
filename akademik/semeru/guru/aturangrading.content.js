function tambahAturanGrading(idTingkat, tingkat)
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

    newWindow('aturangrading.dialog.php?'+qsb.createQs(), "TambahAturanGrading", 600, 600, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function editAturanGrading(idTingkat, tingkat, aspek)
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

    newWindow('aturangrading.dialog.php?'+qsb.createQs(), "UbahAturanGrading", 600, 600, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapusAturanGrading(idTingkat, tingkat, aspek)
{
    if (!confirm("Hapus aturan grading ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("idtingkat", idTingkat);
    qsb.add("aspek", aspek);
    qsb.addInput("nip", "nip");
    qsb.addInput("idpelajaran", "idpelajaran");

    $.ajax({
        url: "aturangrading.content.ajax.php",
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
        url: "aturangrading.content.ajax.php",
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

function cetakAturanGrading()
{
    let qsb = new QsBuilder();
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    newWindow("aturangrading.content.cetak.php?" + qsb.createQs(), "CetakAturanGrading", 790, 650, 'resizable=1,scrollbars=1,status=0,toolbar=0');
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
    newWindow('../help/gp_aturangrading.html?r=' + Math.random(), 'AturanGradingHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}