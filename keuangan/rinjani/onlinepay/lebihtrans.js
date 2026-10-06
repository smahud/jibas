$(document).ready(function () {
    if ($("#tabLebihTrans").length)
        Tables('tabLebihTrans', 1, 0);
});

showDatePicker1 = function ()
{
    var selDate = $("#dttanggal1").val();
    $("#tanggal1").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal1").val(date);
            $("#tanggal1").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

showDatePicker2 = function ()
{
    var selDate = $("#dttanggal2").val();
    $("#tanggal2").datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: selDate,
        onSelect: function (date) {
            $("#dttanggal2").val(date);
            $("#tanggal2").val(dateutil_formatInaDate(date));
            clearContent();
        }
    }).focus();
};

clearContent = function ()
{
    $("#dvLebihTrans").html("");
};

showRincian = function (idPgTransLebih)
{
    var addr = "lebihtrans.rincian.php?idpgtranslebih=" + idPgTransLebih;
    newWindow(addr, 'RincianLebihTrans', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

prosesLebihTrans = function (idPgTransLebih)
{
    var addr = "lebihtrans.proses.php?idpgtranslebih=" + idPgTransLebih;
    newWindow(addr, 'ProsesLebihTrans', '850', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showBuktiTf = function (idPgTransLebih)
{
    var addr = "lebihtrans.buktitf.php?idpgtranslebih=" + idPgTransLebih;
    newWindow(addr, 'BuktiLebihTrans', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

showLebihTrans = function ()
{
    var qs = "op=23489768463274832";
    qs += "&departemen=" + encodeURIComponent($("#departemen").val());
    qs += "&tanggal1=" + encodeURIComponent($("#dttanggal1").val());
    qs += "&tanggal2=" + encodeURIComponent($("#dttanggal2").val());
    qs += "&status=" + $("#status").val();

    var dvLebihTrans = $("#dvLebihTrans");
    dvLebihTrans.html("memuat ..");

    $.ajax({
        url: "lebihtrans.ajax.php",
        method: "POST",
        data: qs,
        success: function (result)
        {
            dvLebihTrans.html(result).hide().fadeIn(500);

            if ($("#tabLebihTrans").length)
                Tables('tabLebihTrans', 1, 0);
        },
        error: function (xhr)
        {
            alert(xhr.responseText);
        }
    })

};

refreshLebihTrans = function ()
{
    showLebihTrans();
};

showRincianJurnal = function (stNoJurnal)
{
    var addr = "rincianjurnal.php?stnojurnal=" + encodeURIComponent(stNoJurnal);
    newWindow(addr, 'RincianJurnal', '750', '750', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};


function showLebihPembayaranHelp()
{
     $.ajax({    
        url: "../help/op_lebihpembayaran.html?r=" + Math.random(),
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


function showInfoSiswa(nis)
{
    var qsb = new QsBuilder();
    qsb.add("nis", nis);

    newWindow('../library/infosiswa.dialog.php?'+qsb.createQs(), 'InformasiSiswa','620','520','resizable=1,scrollbars=1,status=0,toolbar=0'		)
}
