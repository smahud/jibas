function bukaWebcam()
{
    newWindow('../library/face_capture_webcam.php', 'BukaWebcam','920','780','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function pilihGambar()
{
    newWindow('../library/face_capture_image.php', 'PilihGambar','920','780','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptFaceCapture(base64)
{
    $("#imData").val(base64);
    $("#imPreview").attr("src", "data:image/jpeg;base64," + base64);
    $("#btnSimpan").show();
}

function simpanFoto()
{
    if (!confirm("Ganti foto calon siswa?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("foto", "imData");

    $("#dvLoading").show();
    $("#btnSimpan").prop("disabled", true);

    $.ajax({
        url: "gantifoto.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            refresh();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            resetForm();
        }
    });
}

function resetForm()
{
    $("#dvLoading").hide();
    $("#imData").val('');
    $("#imPreview").attr('src', '');
    $("#btnSimpan").hide();
    $("#btnSimpan").prop("disabled", false);
}

function refresh()
{
    let qsb = new QsBuilder();
    qsb.add("op", "refresh");
    qsb.addInput("replid", "replid");

    $.ajax({
        url: "gantifoto.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (data)
        {
            $('#dvCurrentFoto').html(data).hide().fadeIn(300);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}