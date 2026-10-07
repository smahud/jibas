function onChangeDept()
{
    clearContent();

    $("#nis").val("");
    $("#nama").val("");
}

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function cariSiswa()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");

    newWindow("../../library/daftarsiswa.dialog.php?" + qsb.createQs(), 300, 500, 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#nis").val(data.NIS);
    $("#nama").val(data.Nama);
    $("#kelompok").val(kelompok);

    clearContent();
}

function showRiwayatNilaiSiswa()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.IsHaveInput("nis", "NIS");

    if (!isValid) 
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("nis", "nis");
    qsb.addInput("nama", "nama");

    parent.content.location.href = "nilai.siswa.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_riwayatnilaisiswa.html?r=' + Math.random(), 'RiwayatNilaiSiswaHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}