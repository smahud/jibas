$(document).ready(function () {
    applyTables();
});

function applyTables()
{
    if ($("#table").length)
        Tables('table', 1, 0);
}

function dashboardSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add('nis', nis);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../../dashboard/dashboard.php?" + qsb.createQs();
    newWindow(url, 'DashboardSiswa' + nis, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');

    //document.location.href = "../../dashboard/dashboard.php?" + qsb.createQs();
}

function profilSiswa(nis)
{
    let qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../../library/infosiswa.dialog.php?' + qsb.createQs(), 'InformasiSiswa' + nis,'620','520','resizable=1,scrollbars=1,status=0,toolbar=0')
}


function detailSiswa(nis) 
{
    let qsb = new QsBuilder()
    qsb.add('nis', nis)

	newWindow('../../siswa/siswa.detail.php?'+qsb.createQs(), 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function refresh()
{
    document.location.reload();
}

function cetakExcel()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idtahunajaran", "idtahunajaran");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("idsemester", "idsemester");
    qsb.addInput("semester", "semester");
    qsb.addInput("idtingkat", "idtingkat");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("idkelas", "idkelas");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idpelajaran", "idpelajaran");
    qsb.addInput("pelajaran", "pelajaran");
    
    newWindow("legger.rapor.content.all.excel.php?" + qsb.createQs(), "CetakLaporanLeggerNilai", "900", "700", "resizable=1,scrollbars=1,status=0,toolbar=0");  
}