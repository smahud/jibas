var dialogBox = null;

$(document).ready(function()
{
    dialogBox = new DialogBox("#divDialog", 500, 500);

    if ($("#table").length)
        Tables('table', 1, 0);
});

window.addEventListener('pageshow', (event) => 
{
    let dataSiswaSaved = sessionStorage.getItem('dataSiswaSaved');

    if (dataSiswaSaved == 'newdata')
    {
        onNewData();

        sessionStorage.removeItem('dataSiswaSaved');
    }
    
    if (dataSiswaSaved == 'datachanged') 
    {
        let lastSelect = sessionStorage.getItem("lastSelect");
        if (lastSelect.length > 0)
        {
            let dataSelect = JSON.parse(lastSelect);
            $("#idkelas").val(dataSelect[0]);
            $("#orderby").val(dataSelect[1]);
            $("#page").val(dataSelect[2]);

            sessionStorage.removeItem('lastSelect');
        }

        onDataChange();

        sessionStorage.removeItem('dataSiswaSaved');
    }
});

function onPrevPage()
{
    let page = parseInt($("#page").val());
    if (page === 1)
        return;

    $("#page").val(page - 1);
    onChangePage();    
}

function onNextPage()
{
    let page = parseInt($("#page").val());
    let npage = parseInt($("#npage").val());

    if (page === npage)
        return;

    $("#page").val(page + 1);
    onChangePage();
}

function refresh()
{
    onChangePage();
}

function onChangePage()
{
    let qsb = new QsBuilder();
    qsb.add("op", "changepage");
    qsb.addInput("page", "page");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("idkelas", "idkelas");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'siswa.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(data) 
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);

            if ($("#table").length)
                Tables('table', 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function onNewData()
{
    $("#orderby").val(3);
    onChangeOrderBy();
    
    fetchPageControl();
}

function fetchPageControl()
{
    let qsb = new QsBuilder();
    qsb.add("op", "pagecontrol");
    qsb.addInput("idkelas", "idkelas");

    $("#dvPageControl").html("memuat ..");

    $.ajax({
        url: 'siswa.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(data) 
        {
            $("#dvPageControl").html(data).hide().fadeIn(300);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function onDataChange()
{
    onChangePage();
}

function onChangeOrderBy()
{
    let qsb = new QsBuilder();
    qsb.add("op", "changepage");
    qsb.add("page", "1");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("idkelas", "idkelas");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'siswa.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(data) 
        {
            $("#dvTableContent").html(data).hide().fadeIn(300);

            if ($("#table").length)
                Tables('table', 1, 0);
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function showImageSiswa(replid)
{
    let nama = $("#nama" + replid).html();
    let nis = $("#nis" + replid).html();
    let panggilan = $("#panggilan" + replid).html();
    let foto = $("#img" + replid).attr("src");

    let content = "<div style='text-align: center;'>";
    content += "<img  src='" + foto + "'><br><br>";
    content += "<span class='fs-14 fst-bold'>" + nama + "</span><br>";
    content += "<span class='ff-consolas fs-13'>" + nis + "</span><br>";
    content += "<span class='fst-italic fs-12 fg-secondary'>" + panggilan + "</span>";
    content += "</div>";
    
    dialogBox.show(content);
}

function gantiFoto(replid)
{
    document.location.href = "gantifoto.php?replid=" + replid;
}

function tambahSimple()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");

    newWindow('siswa.dialog.php?' + qsb.createQs(), 'TambahSiswa','550','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ubahMudah(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");

    newWindow('siswa.dialog.php?' + qsb.createQs(), 'UbahSiswa','550','550','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambahLengkap()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");

    sessionStorage.removeItem('dataSiswaSaved');
    document.location.href = "siswa.lengkap.php?" + qsb.createQs();
}

function ubahLengkap(replid)
{
    let lastSelect = [];
    lastSelect.push($("#idkelas").val());
    lastSelect.push($("#orderby").val());
    lastSelect.push($("#page").val());
    
    sessionStorage.setItem("lastSelect", JSON.stringify(lastSelect));

    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");
    qsb.add("nis", $("#nis" + replid).text());
    qsb.add("nama", $("#nama" + replid).text());

    sessionStorage.removeItem('dataSiswaSaved');
    document.location.href = "siswa.lengkap.php?" + qsb.createQs();
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

function setNewAktif(replid, newAktif)
{
    let message = newAktif == 1 ?
        "Aktifkan kembali siswa ini?" :
        "Nonaktifkan siswa ini?";

    if (!confirm(message))
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: 'siswa.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(json) 
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                alert(arr[1]);
                return;
            }
            
            if (newAktif == 1)
            {
                $("#imAktif" + replid).prop("src", "../images/ico/aktif.png");
                $("#imAktif" + replid).prop("title", "aktif");
                $("#imAktif" + replid).attr("onclick", "setNewAktif(" + replid + ", 0)");
                $("#spInfoAktif" + replid).html("");
            }
            else
            {
                $("#imAktif" + replid).prop("src", "../images/ico/nonaktif.png");
                $("#imAktif" + replid).prop("title", "tidak aktif");
                $("#imAktif" + replid).attr("onclick", "setNewAktif(" + replid + ", 1)");
                $("#spInfoAktif" + replid).html("Tidak Aktif");
            }
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");

    let addr = "siswa.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakDaftarSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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

function excel()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("kelas", "kelas");
    qsb.addInput("idkelas", "idkelas");

    let addr = "siswa.content.excel.php?" + qsb.createQs();
    newWindow(addr, 'ExcelDaftarSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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


function hapusSiswa(nis)
{
    if (!confirm('HAPUS SISWA INI?'))
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("nis", nis);

    $.ajax({
        url: 'siswa.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(json) 
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                $("#dvLoading").hide();
                alert(arr[1]);
                showToastErrorBottom(arr[1]);
                return;
            }
            
            onDataChange();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        },
        complete: function()
        {
            $("#dvLoading").hide();
        }
    });
}