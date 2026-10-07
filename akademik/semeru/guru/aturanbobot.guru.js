function cariPegawai()
{
    newWindow("../library/daftarpegawai.dialog.php", 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nip").val(data.NIP);
    $("#nama").val(data.Nama);

    $("#spNip").html(data.NIP);
    $("#spNama").html(data.Nama);

    parent.content.location.href = "../guru/blank.php";
    fetchPelajaran(data.NIP);
}

function showInfoPegawai()
{
    var qsb = new QsBuilder();
    qsb.addInput("nip", "nip");

    newWindow('../library/infopegawai.dialog.php?'+qsb.createQs(), 'InformasiPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function fetchPelajaran(nip)
{
    $("#dvPelajaran").css("visibility", "visible");
    $("#dvTablePelajaran").html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaranguru");
    qsb.add("nip", nip);

    $.ajax({
        url: 'aturangrading.guru.ajax.php',
        method: 'POST',
        data: qsb.createQs(),
        success: function (result) 
        {
            $("#dvPelajaran").css("visibility", "visible");
            $("#dvTablePelajaran").html(result).hide().fadeIn(500);

            if ($("#table").length)
                Tables("table", 1, 0);
        },
        error: function (xhr) 
        {
            alert(xhr.responseText);
        }
    })
}

function pilih(idpelajaran, pelajaran, departemen)
{
    let qsb = new QsBuilder();
    qsb.add("idpelajaran", idpelajaran);
    qsb.add("pelajaran", pelajaran);
    qsb.add("departemen", departemen);
    qsb.addInput("nip", "nip");
    qsb.addInput("nama", "nama");

    parent.content.location.href = "aturanbobot.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/gp_aturanrapor.html?r=' + Math.random(), 'AturanBobotHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}