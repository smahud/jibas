$(document).ready(function()
{
    applyTables();
});

function applyTables()
{
    if ($("#tableUjian").length)
        Tables('tableUjian', 1, 0);
}

function onPrevPage()
{
    let page = parseInt($("#page").val());
    if (page === 1)
        return;

    page -= 1;
    $("#page").val(page);

    onChangePage();
}

function onNextPage()
{
    let page = parseInt($("#page").val());
    let nPage = parseInt($("#npage").val());
    if (page === nPage)
        return;

    page += 1;
    $("#page").val(page);

    onChangePage();
}

function refresh()
{
    document.location.reload();
}

function onChangePage()
{
    let qsb = new QsBuilder();
    qsb.add("op", "hasilujian");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("jumlah", "jumlah");
    qsb.addInput("jenis", "jenis");
    qsb.addInput("nsiswa", "nsiswa");
    qsb.addInput("idremedujian", "idremedujian");
    qsb.addInput("idujian", "idujian");
    qsb.addInput("idujianinujianserta", "idujianinujianserta");
    qsb.addInput("kkm", "kkm");
    qsb.addInput("skalanilai", "skalanilai");
    qsb.addInput("page", "page");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "nilaipel.laporan.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (response)
        {
            dvTableContent.html(response).hide().fadeIn(400);

            applyTables();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("pelajaran", "pelajaran");
    qsb.addInput("ujian", "ujian");
    qsb.addInput("tanggal", "tanggal");
    qsb.addInput("pengujian", "pengujian");
    
    newWindow("nilaipel.laporan.cetak.php?" + qsb.createQs(), "CetakLaporanNilaiPelajaran", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
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

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
    //document.location.href = "../../dashboard/dashboard.php?replid=" + replid;
}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}


function detailSiswa(replid) 
{
	newWindow('../../siswa/siswa.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}