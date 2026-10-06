function simpan()
{
    let isValid = Vldr.IsNotEmpty("judul", "Judul") &&
                  Vldr.InputText("judul", "Judul", 3, 255) &&
                  Vldr.IsNotEmpty("nota", "Nota") &&
                  Vldr.InputText("nota", "Nota", 3, 5000);

    if (!isValid)                  
        return;
        
    let kelompok = $("#kelompok").val();
    if (kelompok != "---")
        isValid &= Vldr.IsNotEmpty("personid", "Identitas");
    
    if (!isValid)                  
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("id", "id");
    qsb.addInput("judul", "judul");
    qsb.addInput("nota", "nota");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("personid", "personid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("bagiannota", "bagiannota");
    qsb.addInput("userlevel", "userlevel");
    qsb.addInput("userid", "userid");
    
    setGui("wait");

    $.ajax({
        url: "home.nota.dialog.ajax.php",
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
                opener.onUpdateData();
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

function onChangeCbKelompok()
{
    let kelompok = $("#kelompok").val();
    $("#userid").val("");
    $("#username").val("");

    $("#btCariPerson").prop("disabled", kelompok == "---")
}

function cariPerson()
{
    let kelompok = $("#kelompok").val();

    let qsb = new QsBuilder();
    qsb.add("departemen", $("#departemen").val());

    if (kelompok === "siswa")
        addr = "../library/daftarsiswa.dialog.php?" + qsb.createQs();
    else if (kelompok === "calonsiswa")
        addr = "../library/daftarcalonsiswa.dialog.php?" + qsb.createQs();
    else if (kelompok === "pegawai")
        addr = "../library/daftarpegawai.dialog.php?" + qsb.createQs();

    newWindow(addr, 'CariUser', '550', '590', 'resizable=1,scrollbars=1,status=0,toolbar=0');
}

function acceptSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#personid").val(data.NIS);
    $("#personname").val(data.Nama + " (" + data.NIS + ")");
}

function acceptCalonSiswa(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#personid").val(data.NIC);
    $("#personname").val(data.Nama + " (" + data.NIC + ")");
}

function acceptPegawai(kelompok, json64)
{
    let data = JSON.parse(atob(json64));

    $("#personid").val(data.NIP);
    $("#personname").val(data.Nama + " (" + data.NIP + ")");
}

function onChangeCbKelompok()
{
    let kelompok = $("#kelompok").val();
    $("#personid").val("");
    $("#personname").val("");

    $("#btCariPerson").prop("disabled", kelompok == "---")
    if (kelompok == "---")
        $("#trPerson").hide();
    else
        $("#trPerson").show();
}