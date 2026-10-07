var dialogBox = null;

$(document).ready(function ()
{
    dialogBox = new DialogBox("#divDialog", 600, 400);

    if ($("#tabDashboardCalonSiswa").length)
        $("#tabDashboardCalonSiswa").tabs();

    document.addEventListener('keydown', function(event) 
    {
        if (event.key === 'Escape') 
            window.close(); 
    });

});