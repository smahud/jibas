function simpan()
{
    let isValid = Vldr.IsNotEmpty("folder", "Folder Baru") &&
                  Vldr.InputText("folder", "Folder Baru", 1, 255);

    if (!isValid)
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "createfolder");
    qsb.addInput("iddir", "iddir");
    qsb.addInput("folder", "folder");

    setGui("wait");
    $.ajax({
        url: "fileshare.dirs.create.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(json)
        {
            console.log(json);

            let res = JSON.parse(json);
            if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }

            opener.onNewFolder();
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