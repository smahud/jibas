function toggleMenuSptfgr()
{
    let qsb = new QsBuilder();
    qsb.add("op", "set_sptfgr");

    $.ajax({
        url: "../library/session.setter.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            let result = JSON.parse(data);
            if (parseInt(result[0]) == 1)
            {
                $("#dvMenuSptfgr").fadeIn(300);
                $("#spMenuSptfgr").hide();    
            }
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}