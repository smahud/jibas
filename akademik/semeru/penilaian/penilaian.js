function toggleMenuExim()
{
    let qsb = new QsBuilder();
    qsb.add("op", "set_exim");

    $.ajax({
        url: "../library/session.setter.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            let result = JSON.parse(data);
            if (parseInt(result[0]) == 1)
            {
                $("#dvMenuExim").fadeIn(300);
                $("#spMenuExim").hide();    
            }
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function toggleMenuCbe()
{
    let qsb = new QsBuilder();
    qsb.add("op", "set_cbe");

    $.ajax({
        url: "../library/session.setter.php",
        type: "POST",
        data: qsb.createQs(),
        success: function(data) 
        {
            let result = JSON.parse(data);
            if (parseInt(result[0]) == 1)
            {
                $("#dvMenuCbe").fadeIn(300);
                $("#spMenuCbe").hide();    
            }
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}