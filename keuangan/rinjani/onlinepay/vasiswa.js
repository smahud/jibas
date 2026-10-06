var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 500, 500);
});

changeDep = function ()
{
    var dept = $("#departemen").val();
    fetchTingkat(dept);
};

changeTingkat = function ()
{
    var dept = $("#departemen").val();
    var idTingkat = $("#tingkat").val();

    fetchKelas(dept, idTingkat);
};

changeKelas = function ()
{
    $("#divContent").html("");
}

fetchTingkat = function (dept)
{
    $("#divTingkat").html("memuat ..");
    $("#divKelas").html("");
    $("#divContent").html("");

    $.ajax({
        url: "vasiswa.ajax.php",
        method: "POST",
        data: "op=fetchtingkat&dept=" + dept,
        success: function (data)
        {
            $("#divTingkat").html(data);

            var idTingkat = $("#tingkat").val();
            fetchKelas(dept, idTingkat);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

fetchKelas = function (dept, idTingkat)
{
    $("#divKelas").html("memuat ..");
    $("#divContent").html("");

    $.ajax({
        url: "vasiswa.ajax.php",
        method: "POST",
        data: "op=fetchkelas&dept="+dept+"&idtingkat="+idTingkat,
        success: function (data)
        {
            $("#divKelas").html(data);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
};

showDaftarVaSiswa = function ()
{
    $("#divContent").html("");

    if ($("#kelas").length == 0)
        return;

    $("#divContent").html("memuat ..");

    var dept = $("#departemen").val();
    let idKelas = $("#kelas").val();
    $.ajax({
        url: "vasiswa.ajax.php",
        method: "POST",
        data: "op=showdaftarvasiswa&idkelas=" + idKelas + "&dept=" + dept,
        success: function (data)
        {
            $("#divContent").html(data).hide().fadeIn(500);

            if ($("#table").length)
                Tables("table", 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

simpanVaSiswa = function (no)
{
    let btnSimpan = $("#btSimpan-"+no);
    let spSimpan = $("#spSimpan-"+no);

    btnSimpan.prop("disabled", true);
    spSimpan.css("color", "#efefef");
    spSimpan.html("menyimpan ..");

    let qsb = new QsBuilder();
    qsb.add("op", "simpanvasiswa");

    let lsVaNo = [];
    let nis = $("#nis-"+no).val();
    qsb.add("nis", nis);
    for(let ix = 1; ix <= 3; ix++)
    {
        let idVa = $("#idva-"+no+"-"+ix).val();
        let vaNo = $("#vano-"+no+"-"+ix).val();
        let vaBank = $("#vabank-"+no+"-"+ix).val();

        if (vaNo != "")
        {
            if (lsVaNo.includes(vaNo))
            {
                alert("Virtual Account " + vaNo + " sudah digunakan");
                btnSimpan.prop("disabled", false);
                return;
            }

            lsVaNo.push(vaNo);
        }

        qsb.add("idva-" + ix, idVa);
        qsb.add("vano-" + ix, vaNo);
        qsb.add("vabank-" + ix, vaBank);
    }

    $.ajax({
        url: "vasiswa.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            btnSimpan.prop("disabled", false);

            let ls = JSON.parse(json);
            if (parseInt(ls[0]) <= 0)
            {
                spSimpan.css("color", "red");
                spSimpan.html(ls[1]);
                return;
            }

            spSimpan.css("color", "blue");
            spSimpan.html("tersimpan");

            sendToAppServer("datasync");

            let lsIdVaSiswa = ls[2];
            for(let ix = 1; ix <= 3; ix++)
            {
                $("#idva-"+no+"-"+ix).val(lsIdVaSiswa[ix-1]);
            }

            setTimeout(() => {
                spSimpan.html("");
            }, 2000);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function showVaSiswaHelp()
{
    $.ajax({
        url: "../help/op_vasiswa.html?r=" + Math.random(),

        success: function (content)
        {
            helpBox.show(content);

              setTimeout(function () {
                $("#divHelpDialog").scrollTop(0);
            }, 750)
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}