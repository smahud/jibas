$(document).ready(function() {
    if ($("#cari").length)
        $("#cari").focus();
})

function onChangeCari()
{
    parent.content.location.href = "blank.php";
}

function onChangeDept()
{
    parent.content.location.href = "blank.php";
}

function onChangeJenisCari()
{
    parent.content.location.href = "blank.php";

    let qsb = new QsBuilder();
    qsb.add("op", "fetchcari");
    qsb.addInput("jeniscari", "jeniscari");

    $("#spCari").html("memuat ..");

    $.ajax({
        url: "carialumni.menu.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#spCari").html(data).hide().fadeIn(300);

            if ($("#cari").length)
                $("#cari").focus();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showCariAlumni()
{
    let cari = $.trim($("#cari").val());
    if (cari.length == 0)
        return;

    let qsb = new QsBuilder();
    qsb.addInput("jeniscari", "jeniscari");
    qsb.add("jeniscaritext", $("#jeniscari option:selected").text());
    qsb.addInput("cari", "cari");
    qsb.addInput("departemen", "departemen");
        
    parent.content.location.href = "carialumni.content.php?" + qsb.createQs();
}

function showHelp()
{
    newWindow('../../help/rs_carialumni.html?r=' + Math.random(), 'CariAlumniHelp','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}