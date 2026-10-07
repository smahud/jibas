var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
});


function showRekapInput()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("rentang", "rentang");
    qsb.addInput("keyword", "keyword");
    
    let jenis = $("#jenis").val();
    if (jenis == "riwayat")
        parent.content.location.href = "rekapinput.riwayat.php?" + qsb.createQs();
    else
        parent.content.location.href = "rekapinput.rekap.php?" + qsb.createQs();
}

function clearContent()
{
    parent.content.location.href = "blank.php";
}

function showHelp()
{
    newWindow('../help/rf_rekapinput.html', 'RekapInputHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}