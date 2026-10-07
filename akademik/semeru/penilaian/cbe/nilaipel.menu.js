function clearContent()
{
    parent.content.location.href = "blank.php";
}

function onChangePelajaran()
{
    clearContent();
}

function onChangeDept()
{
    clearContent();

    let qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    let spPelajaran = $("#spPelajaran");
    spPelajaran.html("memuat ..");

    $.ajax({
        url: "nilaipel.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spPelajaran.html(data).hide().fadeIn(300);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function showNilaiCbePelajaran()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") &&
                  Vldr.HasOption("pelajaran", "Pelajaran");
    
    if (!isValid)                  
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "pelajaran");
    qsb.add("pelajaran", $("#pelajaran option:selected").text());
    qsb.addInput("jumlah", "jumlah");
    qsb.addInput("jenis", "jenis");

    parent.content.location.href = "nilaipel.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/pn_nilaicbe.html?r=' + Math.random(), 'NilaiCbePelajaranHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}