function onChangeDept()
{
    parent.content.location.href = "blank.php";
}

function showPenempatan()
{
    let isValid = Vldr.HasOption("departemen", "Departemen");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    
    parent.content.location.href = "penempatan.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../help/psb_penempatan.html', 'PenempatanHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}