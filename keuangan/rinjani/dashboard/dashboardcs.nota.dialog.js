function simpan()
{
    let isValid = Vldr.IsNotEmpty("judul", "Judul") &&
                  Vldr.InputText("judul", "Judul", 3, 255) &&
                  Vldr.IsNotEmpty("nota", "Nota") &&
                  Vldr.InputText("nota", "Nota", 3, 5000) &&
                  confirm("Data sudah benar?");

    if (!isValid)                  
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("id", "id");
    qsb.addInput("judul", "judul");
    qsb.addInput("nota", "nota");
    qsb.addInput("nic", "nic");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");
    qsb.addInput("userlevel", "userlevel");
    qsb.addInput("userid", "userid");
    
    setGui("wait");

    $.ajax({
        url: "dashboardcs.nota.dialog.ajax.php",
        data: qsb.createQs(),
        method: "POST",
        success: function (json)
        {
            console.log(json);

            let res = JSON.parse(json);
            if (parseInt(res[0]) < 0)
            {
                alert(res[1]);
                return;
            }

            let id = parseInt($("#id").val());
            if (id == 0)
                opener.onNewData();
            else 
                opener.onDataChange();
                                
            window.close();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            setGui("ready");
        }
    })
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