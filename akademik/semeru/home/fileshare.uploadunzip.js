function checkFileSize(oFile)
{
    let maxSizeMb = parseInt($("#maxsize").val());
    let maxSize = maxSizeMb * 1024 * 1024; 

    if (oFile.files[0].size > maxSize)
    {
        alert("File terlalu besar (maksimal " + maxSizeMb + " MB)");
        oFile.value = "";
        oFile.focus();
    }
}

function validate()
{
    var fileSelected = 0;
    if ($("#filezip").val().length > 0)
        fileSelected++;

    if (fileSelected == 0) 
    {
        alert("Pilih file ZIP yang akan diunggah");
        return false;
    }

    return true;
}    

function simpan()
{
    let isValid = validate();
    if (!isValid)
        return;

    var form = $('#uploadform')[0]; 
    var formData = new FormData(form);

    setGui("wait");

    $.ajax({
        url: "fileshare.uploadunzip.simpan.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(json) 
        {
            let res = JSON.parse(json);
            if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }

            opener.refresh();
            window.close();
        },
        error: function(xhr) 
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            setGui("ready");
        }
    });        
}

function setGui(state)
{
    switch(state)
    {
        case "wait":
            $("#dvLoading").show();
            $("#btnSimpan").prop("disabled", true);
            $("#btnTutup").prop("disabled", true);
            break;
        case "ready":
            $("#dvLoading").hide();
            $("#btnSimpan").prop("disabled", false);
            $("#btnTutup").prop("disabled", false);
            break;
    }
}