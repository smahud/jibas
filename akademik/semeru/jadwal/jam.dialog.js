$( document ).ready(function() 
{
    $("#jammulai").focus();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });
});

function simpan()
{
    let isValid = Vldr.IsHaveInput("jammulai", "Jam Mulai") &&
                  Vldr.IsInteger("jammulai", "Jam Mulai") &&
                  Vldr.IsNotNegative("jammulai", "Jam Mulai") &&
                  Vldr.IsInRangeInteger("jammulai", "Jam Mulai", 0, 23) &&
                  Vldr.IsHaveInput("menitmulai", "Menit Mulai") &&
                  Vldr.IsInteger("menitmulai", "Menit Mulai") &&
                  Vldr.IsNotNegative("menitmulai", "Menit Mulai") &&
                  Vldr.IsInRangeInteger("menitmulai", "Menit Mulai", 0, 59) &&
                  Vldr.IsHaveInput("jamakhir", "Jam Akhir") &&
                  Vldr.IsInteger("jamakhir", "Jam Akhir") &&
                  Vldr.IsNotNegative("jamakhir", "Jam Akhir") &&
                  Vldr.IsInRangeInteger("jamakhir", "Jam Akhir", 0, 23) &&
                  Vldr.IsHaveInput("menitakhir", "Menit Akhir") &&
                  Vldr.IsInteger("menitakhir", "Menit Akhir") && 
                  Vldr.IsNotNegative("menitakhir", "Menit Akhir") &&
                  Vldr.IsInRangeInteger("menitakhir", "Menit Akhir", 0, 59);

    if (!isValid)                  
        return;

    let jamMulai = $("#jammulai").val().padStart(2, "0");
    let menitMulai = $("#menitmulai").val().padStart(2, "0");
    let jamAkhir = $("#jamakhir").val().padStart(2, "0");
    let menitAkhir = $("#menitakhir").val().padStart(2, "0");

    let waktuMulai = jamMulai + ":" + menitMulai + ":00";
    let waktuAkhir = jamAkhir + ":" + menitAkhir + ":00";

    if (waktuMulai == waktuAkhir)
    {
        isValid = false;
        alert("Waktu Mulai dan Waktu Akhir tidak boleh sama.");
    }

    if (waktuMulai > waktuAkhir)
    {
        isValid = false;
        alert("Waktu Mulai tidak boleh lebih besar dari Waktu Akhir.");
    }
    
    if (!isValid) 
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "simpan");
    qsb.addInput("replid", "replid");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("jamke", "jamke");
    qsb.add("waktumulai", waktuMulai);
    qsb.add("waktuakhir", waktuAkhir);
    qsb.addInput("keterangan", "keterangan");

    setGui("wait");

    $.ajax({
        url: "jam.dialog.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            setGui("ready");

            let response = JSON.parse(json);
            if (parseInt(response[0]) < 0)
            {
                alert(response[1]);
                return;
            }
            else if (parseInt(response[0]) == 0)
            {
                alert("Ada jam yang bentrok");
                $("#spInfo").html(response[1]);
                return;
            }

            opener.onDataChanged();
            window.close();
        },
        error: function (xhr)
        {
            setGui("ready");
            alert(xhr.responseText);
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