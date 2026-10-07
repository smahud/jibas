$(document).ready(function() {
    if ($("#tabContent").length)
        Tables('tabContent', 1, 0);

    if ($("#tabTanggal").length)
        Tables('tabTanggal', 0, 0);
})

function showData(selTanggal)
{
    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    let qsb = new QsBuilder();
    qsb.add("op", "content");
    qsb.add("seltanggal", selTanggal);
    qsb.addInput("statusdata", "statusdata");
    qsb.addInput("idkegiatan", "idkegiatan");
    
    $.ajax({
        url: "lapkegiatan.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            dvTableContent.html(data).hide().fadeIn(300);

            if ($("#tabContent").length)
                Tables('tabContent', 1, 0);
        }, 
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function showUserInfo(userCol, userId)
{
    let qsb = new QsBuilder();
    qsb.add(userCol, userId);

    if (userCol == "nis")
        newWindow('../../library/infosiswa.dialog.php?'+qsb.createQs(), 'InfoSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
    else if (userCol == "nip")
        newWindow('../../library/infopegawai.dialog.php?'+qsb.createQs(), 'InfoPegawai','620','520','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function refresh()
{
    document.location.reload();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("kegiatan", "kegiatan");
    qsb.addInput("statusdata", "statusdata");
    qsb.addInput("tglawal", "tglawal");
    qsb.addInput("tglakhir", "tglakhir");
    
    newWindow("lapkegiatan.content.cetak.php?" + qsb.createQs(), "CetakLaporanKegiatan", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}

function getPageContent(section)
{
    if (section === "content")
    {
        if ($("#dvTableContent").length)
            return $("#dvTableContent").html();

        return "-";
    }
}


function showDashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}
