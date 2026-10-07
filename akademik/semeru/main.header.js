$(document).ready(function()
{
    $('.menu-item').on('click', function()
    {
        $('.menu-item').removeClass('active');
        $(this).addClass('active');

        // Get clicked menu id
        let clickedId = this.id;
        showPage(clickedId);
    });
});

function showPage(mnPage)
{
    switch (mnPage)
    {
        case "mnHome":
            parent.maincontent.location.href = "home/home.php";
            break;
        case "mnReferensi":
            parent.maincontent.location.href = "referensi/referensi.php";
            break;
        case "mnPsb":
            parent.maincontent.location.href = "psb/psb.php";
            break;
        case "mnGuru":
            parent.maincontent.location.href = "guru/gurupelajaran.php";
            break;
        case "mnJadwal":
            parent.maincontent.location.href = "jadwal/jadwalkalender.php";
            break;
        case "mnSiswa":
            parent.maincontent.location.href = "siswa/kesiswaan.php";
            break;
        case "mnPresensi":
            parent.maincontent.location.href = "presensi/presensi.php";
            break;
        case "mnPenilaian":
            parent.maincontent.location.href = "penilaian/penilaian.php";
            break;
        case "mnReposisi":
            parent.maincontent.location.href = "reposisi/reposisi.php";
            break;            
        case "mnPengaturan":
            parent.maincontent.location.href = "pengaturan/pengaturan.php";
            break;
    }
}

function confirmLogout()
{
    if (!confirm("Keluar dari JIBAS Akademik?"))
        return;

    $.ajax({
        url: "logout.php",
        success: function (json)
        {
            top.window.location = 'login.php';
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })
}