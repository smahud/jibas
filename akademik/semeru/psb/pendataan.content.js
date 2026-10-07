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
            $("#idkelompok").val(dataSelect[0]);
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
    qsb.addInput("idkelompok", "idkelompok");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'pendataan.content.ajax.php',
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
    $("#orderby").val(0);

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("page", 1);
    
    document.location.href = "pendataan.content.php?" + qsb.createQs();
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
    qsb.addInput("idkelompok", "idkelompok");

    $("#dvLoading").show();
    $("#dvTableContent").html("memuat ..");

    $.ajax({
        url: 'pendataan.content.ajax.php',
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

function showInfoNilai()
{
    let nData = parseInt($("#ndata").val());
    if (nData == 0) 
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("orderby", "orderby");
    qsb.addInput("page", "page");
    
    document.location.href = "pendataan.infonilai.php?" + qsb.createQs();
}

function showImageSiswa(replid)
{
    let nama = $("#nama" + replid).html();
    let nopendaftaran = $("#nopendaftaran" + replid).html();
    let panggilan = $("#panggilan" + replid).html();
    let foto = $("#img" + replid).attr("src");

    let content = "<div style='text-align: center;'>";
    content += "<img  src='" + foto + "'><br><br>";
    content += "<span class='fs-14 fst-bold'>" + nama + "</span><br>";
    content += "<span class='ff-consolas fs-13'>" + nopendaftaran + "</span><br>";
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
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");

    newWindow('pendataan.dialog.php?' + qsb.createQs(), 'TambahSiswa','550','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function ubahMudah(replid)
{
    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");

    newWindow('pendataan.dialog.php?' + qsb.createQs(), 'UbahSiswa','550','450','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambahLengkap()
{
    let qsb = new QsBuilder();
    qsb.add("replid", 0);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");

    sessionStorage.removeItem('dataSiswaSaved');
    document.location.href = "pendataan.lengkap.php?" + qsb.createQs();
}

function ubahLengkap(replid)
{
    let lastSelect = [];
    lastSelect.push($("#idkelompok").val());
    lastSelect.push($("#orderby").val());
    lastSelect.push($("#page").val());
    
    sessionStorage.setItem("lastSelect", JSON.stringify(lastSelect));

    let qsb = new QsBuilder();
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.add("nopendaftaran", $("#nopendaftaran" + replid).text());
    qsb.add("nama", $("#nama" + replid).text());

    sessionStorage.removeItem('dataSiswaSaved');
    document.location.href = "pendataan.lengkap.php?" + qsb.createQs();
}

function detailSiswa(replid) 
{
	newWindow('pendataan.detail.php?replid='+replid, 'DetailSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0')
}

function setNewAktif(replid, newAktif)
{
    let message = newAktif == 1 ?
        "Aktifkan kembali calon siswa ini?" :
        "NON AKTIF KAN CALON SISWA INI?";

    if (!confirm(message))
        return;

    $("#dvLoading").show();

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("newaktif", newAktif);

    $.ajax({
        url: 'pendataan.content.ajax.php',
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
    let nData = parseInt($("#ndata").val());
    if (nData == 0) 
        return;
        
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("kelompok", "kelompok");

    let addr = "pendataan.content.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakDaftarCalonSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
    let nData = parseInt($("#ndata").val());
    if (nData == 0) 
        return;

    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("proses", "proses");
    qsb.addInput("idproses", "idproses");
    qsb.addInput("kelompok", "kelompok");
    qsb.addInput("idkelompok", "idkelompok");
    qsb.addInput("orderby", "orderby");

    let addr = "pendataan.content.excel.php?" + qsb.createQs();
    newWindow(addr, 'ExcelDaftarSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function dashboardCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../dashboard/dashboardcs.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '900', '600', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function profilCalonSiswa(replid)
{
    let qsb = new QsBuilder();
    qsb.add('replid', replid);
    qsb.add("showbackbutton", 0);
    qsb.add("showclosebutton", 1);

    let url = "../library/infocalonsiswa.dialog.php?" + qsb.createQs();
    newWindow(url, 'DashboardCalonSiswa' + replid, '620','520', 'resizable=0,scrollbars=0,status=0,toolbar=0');
}

function hapusSiswa(replid, nopendaftaran)
{
    if (!confirm("HAPUS CALON SISWA INI?"))
        return;
    
    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);
    qsb.add("nopendaftaran", nopendaftaran);
    
    $.ajax({
        url: 'pendataan.content.ajax.php',
        type: 'POST',
        data: qsb.createQs(),
        success: function(json)
        {
            let arr = JSON.parse(json);
            if (parseInt(arr[0]) < 0)
            {
                showToastErrorBottom(arr[1]);
                return;
            }
            
            onChangePage();
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    });
}