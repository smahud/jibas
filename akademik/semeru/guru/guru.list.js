$(document).ready(function() 
{
    if ($("#table").length)
        Tables('table', 0, 0);
});

function onChangeDept(showBlank) 
{         
    var qsb = new QsBuilder();
    qsb.add("op", "pelajaran");
    qsb.addInput("departemen", "departemen");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    if (showBlank)
        parent.content.location.href = "blank.php";

    $.ajax({
        url: "guru.list.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTableContent.html(data).hide().fadeIn(500);

            if ($("#table").length)
                Tables('table', 0, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function pilih(id_pel, nama_dep, nama_pel) 
{         
    var qsb = new QsBuilder();
    qsb.add("id_pel", id_pel);
    qsb.add("nama_dep", nama_dep);
    qsb.add("nama_pel", nama_pel);

    parent.content.location.href = "guru.content.php?" + qsb.createQs();
}

function refresh()
{
    onChangeDept(false);
}

function showHelp()
{
    newWindow('../help/gp_guru.html?r=' + Math.random(), 'PendataanGuruHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}
