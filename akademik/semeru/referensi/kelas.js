var helpBox = null;

$(document).ready(function ()
{
    helpBox = new DialogBox("#divHelpDialog", 600, 500);
    applyTables();

    onChangeFilter();
});

function applyTables()
{
    if ($("#table").length)
        Tables("table", 1, 0);
}

function showHelp()
{
    $.ajax({
        url: "../help/rf_kelas.html?r=" + Math.random(),
        success: function (content)
        {
            helpBox.show(content);

            setTimeout(function () {
                $("#divHelpDialog").scrollTop(0);
            }, 750)
        },
        error: function(xhr)
        {
            alert(xhr.responseText);
        }
    })
}

function onChangeFilter()
{
    // reset to first page when filter changes
    $("#page").val(1);
    onChangePage();
}

function onDepartemenChange()
{
    // reset view while loading new select data
    $("#dvTableContent").html("");

    let departemen = $("#departemen").val();
    let qsb = new QsBuilder();
    qsb.add("op", "loadselects");
    qsb.add("departemen", departemen);

    $.ajax({
        url: "kelas.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let response = JSON.parse(json);
            $("#dvTahunAjaran").html(response.htmlTahunAjaran);
            $("#dvTingkat").html(response.htmlTingkat);

            // Refresh listing after the selects are updated
            onChangeFilter();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
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
    let totalPage = parseInt($("#totalpage").val());
    if (page === totalPage)
        return;

    page += 1;
    $("#page").val(page);

    onChangePage();
}

function onChangePage()
{
    let qsb = new QsBuilder();
    qsb.add("op", "daftar");
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");
    qsb.addInput("page", "page");
    qsb.addInput("urut", "urut");
    qsb.addInput("urutan", "urutan");

    let dvTableContent = $("#dvTableContent");
    dvTableContent.html("memuat ..");

    $.ajax({
        url: "kelas.ajax.php",
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

function refresh()
{
    onChangePage();
}

function setAktif(replid, newAktif)
{
    let msg = "";
    if (newAktif === 0)
        msg = "Apakah anda yakin ingin menonaktifkan kelas ini?";
    else
        msg = "Apakah anda yakin ingin mengaktifkan kelas ini?";

    if (!confirm(msg))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "setaktif");
    qsb.add("replid", replid);
    qsb.add("aktif", newAktif);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");

    $.ajax({
        url: "kelas.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            /*
            let img = $("#status-" + replid);
            if (newAktif === 0)
                img.attr("src", "../images/ico/nonaktif.png");
            else
                img.attr("src", "../images/ico/aktif.png");
            */
            onChangePage();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    }); 
}

function edit(replid)
{
    let departemen = $("#departemen").val();
    let tahunajaran = $("#tahunajaran").val();
    let tingkat = $("#tingkat").val();

    newWindow('kelas.dialog.php?replid=' + replid + '&departemen=' + encodeURIComponent(departemen) + '&tahunajaran=' + encodeURIComponent(tahunajaran) + '&tingkat=' + encodeURIComponent(tingkat), 'UbahKelas','550','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function tambah()
{
    let isValid = Vldr.HasOption("departemen", "Departemen") && 
                  Vldr.HasOption("tahunajaran", "Tahun Ajaran") && 
                  Vldr.HasOption("tingkat", "Tingkat");
    
    if (!isValid)
        return;

    let departemen = $("#departemen").val();
    let tahunajaran = $("#tahunajaran").val();
    let tingkat = $("#tingkat").val();

    newWindow('kelas.dialog.php?departemen=' + encodeURIComponent(departemen) + '&tahunajaran=' + encodeURIComponent(tahunajaran) + '&tingkat=' + encodeURIComponent(tingkat), 'TambahKelas','550','500','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function hapus(replid)
{
    if (!confirm("Apakah anda yakin ingin menghapus kelas ini?"))
        return;

    let qsb = new QsBuilder();
    qsb.add("op", "hapus");
    qsb.add("replid", replid);
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");

    $.ajax({
        url: "kelas.ajax.php",
        method: "POST",
        data: qsb.createQs(),
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                alert(ls[1]);
                return;
            }

            onChangePage();
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    });
}

function lihatSiswa(replid, nama)
{
    let qsb = new QsBuilder();
    qsb.add("idkelas", replid);
    qsb.add("kelas", nama);

    let addr = "kelas.siswa.php?" + qsb.createQs();
    newWindow(addr, 'LihatSiswa','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
}

function changeSort(field)
{
    let current = $("#urut").val();
    let currentDir = $("#urutan").val();

    if (current === field)
        currentDir = (currentDir === "ASC") ? "DESC" : "ASC";
    else
        currentDir = "ASC";

    $("#urut").val(field);
    $("#urutan").val(currentDir);

    onChangePage();
}

function cetak()
{
    let qsb = new QsBuilder();
    qsb.addInput("departemen", "departemen");
    qsb.addInput("tahunajaran", "tahunajaran");
    qsb.addInput("tingkat", "tingkat");

    let addr = "kelas.cetak.php?" + qsb.createQs();
    newWindow(addr, 'CetakKelas','790','650','resizable=1,scrollbars=1,status=0,toolbar=0');
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
