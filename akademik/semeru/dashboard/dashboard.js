var dialogBox = null;

$(document).ready(function ()
{
    dialogBox = new DialogBox("#divDialog", 600, 400);

    if ($("#tabDashboardSiswa").length)
        $("#tabDashboardSiswa").tabs();
});
