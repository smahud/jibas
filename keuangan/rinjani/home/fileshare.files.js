$(document).ready(function() {

    if ($("#table").length)
        Tables('table', 1, 0);

});


function uploadFile(iddir, fullpath)
{
    let qsb = new QsBuilder();
    qsb.add("iddir", iddir);
    qsb.add("fullpath", fullpath);

	newWindow('fileshare.upload.php?' + qsb.createQs(),'InputFile','500','450','resizable=1,scrollbars=0,status=0,toolbar=0');
}

function uploadFileUnzip(iddir, fullpath)
{
    let qsb = new QsBuilder();
    qsb.add("iddir", iddir);
    qsb.add("fullpath", fullpath);
    
	newWindow('fileshare.uploadunzip.php?' + qsb.createQs(),'InputFileZIP','500','220','resizable=1,scrollbars=0,status=0,toolbar=0');
}

function refresh()
{
    document.location.reload();
}

function cekAll()
{
    let numFile = parseInt($("#numfile").val());
    let status = $("#cek").is(":checked");
    for (let i = 1; i <= numFile; i++)
    {
        $("#cekfile" + i).prop("checked", status);
    }
}

function delSelected()
{
    let numFile = parseInt($("#numfile").val());
    let idFiles = [];

    for (let i = 1; i <= numFile; i++)
    {
        if ($("#cekfile" + i).is(":checked"))
        {
            idFiles.push(parseInt($("#idfile" + i).val()));
        }
    }
    
    if (idFiles.length == 0)
    {
        alert("Pilih file yang akan dihapus");
        return;
    }

    if (!confirm("Apakah anda yakin ingin menghapus " + idFiles.length + " file?  "))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "delselected");
    qsb.add("jsonidfiles", JSON.stringify(idFiles));

    $("#dvLoading").show();

    $.ajax({
        url: "fileshare.files.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            let res = JSON.parse(json);
            if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }
            
            refresh();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
    
}

