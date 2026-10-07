function showDaftarSiswa(tanggal)
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftarsiswa");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
    qsb.add("tanggal", tanggal);

    $("#dvLoading").show();
    $("#dvTableSiswa").html("memuat ..");

    $.ajax({
        url: "ultah.content.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function(data)
        {
            $("#dvTableSiswa").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    })
    
}

function profilSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);

    newWindow('../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function detailSiswa(replid) 
{
	newWindow('siswa.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function dashboardSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("bulan", "bulan");
    qsb.addInput("tahun", "tahun");
    qsb.addInput("tanggal", "tanggal");

    let addr = "ultah.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakUltahSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function getPageContent(section)
{
    if (section == "content")
    {
        if ($("#dvTableSiswa").length)
            return $("#dvTableSiswa").html();

        return "-";
    }
}