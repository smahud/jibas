function clearContent()
{
    parent.content.location.href = "blank.php";
}

function onChangeDept()
{
    clearContent();

    fetchTahunMutasi();
}

function onChangeTahunMutasi()
{
    clearContent();
}

function fetchTahunMutasi()
{
    let qsb = new QsBuilder();
    qsb.add("op", "tahunmutasi");
    qsb.addInput("departemen", "departemen");

    let spTahunMutasi = $("#spTahunMutasi");
    spTahunMutasi.html("memuat ..");
    
    $.ajax({
        url: "statistik.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            spTahunMutasi.html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showStatistikMutasiSiswa()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") && 
                  Vldr.HasOption("tahunmutasi", "Tahun Mutasi");

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunmutasi", "tahunmutasi");

    parent.content.location.href = "statistik.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/rs_statistikmutasi.html?r=' + Math.random(), 'StatistikMutasiHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}