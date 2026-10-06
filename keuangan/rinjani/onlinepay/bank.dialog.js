var validQris = true;

$(document).ready(function()
{
    $("#bank").focus();

    let qrisError = $("#qriserror");

    $("#qris").on("change", function() 
    {
        $("#qrisvalid").val(0);
        validQris = true;
        qrisError.html("");

        const file = this.files[0];
        if (!file) 
        {
            $("#qrisvalid").val(-1);
            qrisError.html("tidak dapat membaca file");
            return;            
        }
        
        const allowedTypes = ["image/jpeg", "image/png"];
        if ($.inArray(file.type, allowedTypes) === -1) 
        {
            $("#qrisvalid").val(-1);
            qrisError.html("hanya menerima ekstensi JPG atau PNG");
            validQris = false;
            return;
        }

        const reader = new FileReader();
        reader.onload = function (event) 
        {
            const img = new Image();
            img.onload = function () 
            {
                if (this.width < 300 || this.height < 300)
                {
                    $("#qrisvalid").val(-1);
                    qrisError.html("ukuran gambar terlalu kecil, minimal 300x300 pixel")
                    validQris = false;
                    return;
                }

                if (this.height > 700 || this.height > 700)
                {
                    $("#qrisvalid").val(-1);
                    qrisError.html("ukuran gambar terlalu besar, maksimal 700x700 pixel");
                    validQris = false;
                    return;
                }

                const canvas = document.createElement("canvas");
                const ctx = canvas.getContext("2d");

                canvas.width = this.width;
                canvas.height = this.height;

                ctx.drawImage(img, 0, 0);

                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, canvas.width, canvas.height);

                if (code) 
                {
                    //console.log("QR FOUND:", code.data);
                    $("#qrisvalid").val(1);
                } 
                else 
                {
                    qrisError.html("QR code tidak ditemukan di gambar");
                    validQris = false;
                    $("#qrisvalid").val(-1);
                    return;
                }
            };

            img.onerror = function () 
            {
                qrisError.html("gambar tidak valid");
                validQris = false;
                $("#qrisvalid").val(-1);
                return;
            };

            img.src = event.target.result; 
        };

        reader.readAsDataURL(file); 
    });

});

showRekAkunDialog = function(kategori, subKategori)
{
    let qsb = new QsBuilder();
    qsb.add("kategori", kategori);
    qsb.add("subkategori", subKategori);

    let addr = '../library/rekakun.dialog.php?' + qsb.createQs();
    newWindow(addr, 'RekAkunDialog3', '760', '560', 'resizable=1,scrollbars=1,status=0,toolbar=0');
};

acceptRekAkunDialog = function(kategori, subKategori, kodeRek, namaRek)
{
    if (kategori === "HARTA")
    {
        $("#inforekkas").val(kodeRek + " " + namaRek);
        $("#rekkas").val(kodeRek);
    }
    else if (kategori === "PENDAPATAN")
    {
        $("#inforekpendapatan").val(kodeRek + " " + namaRek);
        $("#rekpendapatan").val(kodeRek);
    }
};

function simpanBank() 
{
    var bank = $.trim($("#bank").val());
    if (bank.length < 3)
    {
        alert("Nama bank minimal 3 karater");
        $("#bank").focus();
        return;
    }

    var bankLoc = $.trim($("#bankloc").val());
    if (bankLoc.length < 5)
    {
        alert("Lokasi bank minimal 5 karater");
        $("#bankloc").focus();
        return;
    }

    var bankName = $.trim($("#bankname").val());
    if (bankName.length < 5)
    {
        alert("Nama pemilik rekening minimal 5 karater");
        $("#bankname").focus();
        return;
    }

    var bankNo = $.trim($("#bankno").val());
    if (bankNo.length < 5)
    {
        alert("Nomor rekening minimal 5 karater");
        $("#bankno").focus();
        return;
    }

    let qrisValid = parseInt($("#qrisvalid").val());
    if (qrisValid === -1)
    {
        alert("QR code tidak valid");
        return;
    }

    let urutan = $.trim($("#urutan").val());
    if (urutan.length === 0 || isNaN(urutan) || parseInt(urutan) < 0)
    {
        alert("Urutan harus diisi dengan bilangan positif");
        $("#urutan").focus();
        return;
    }

    let rekKas = $.trim($("#rekkas").val());
    if(rekKas.length === 0)
    {
        alert("Belum ada data rekening kas");
        $("#inforekkas").focus();
        return;
    }

    let rekPendapatan = $.trim($("#rekpendapatan").val());
    if (rekPendapatan.length === 0)
    {
        alert("Belum ada data rekening pendapatan");
        $("#inforekpendapatan").focus();
        return;
    }

    if (!confirm("Data sudah benar?"))
        return;

    let btSimpan = $("#btSimpan");
    let btTutup = $("#btTutup");

    btSimpan.prop("disabled", true);
    btTutup.prop("disabled", true);
    
    var formData = new FormData();
    formData.append("op", "simpan");
    formData.append("departemen", $.trim($("#departemen").val()));
    formData.append("idbank", $.trim($("#idbank").val()));
    formData.append("bank", $.trim($("#bank").val()));
    formData.append("bankloc", $.trim($("#bankloc").val()));
    formData.append("bankname", $("#bankname").val());
    formData.append("bankno", $("#bankno").val());
    formData.append("vano", "");
    formData.append("qris", $("#qris")[0].files[0]);
    formData.append("qrisname", $("#qrisname").val());
    formData.append("qrisid", $("#qrisid").val());
    formData.append("urutan", $("#urutan").val());
    formData.append("rekkas", $("#rekkas").val());
    formData.append("rekpendapatan", $("#rekpendapatan").val());
    formData.append("keterangan", $("#keterangan").val());

    $.ajax({
        url: "bank.dialog.ajax.php",
        type: 'POST',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (json)
        {
            let ls = JSON.parse(json);
            if (parseInt(ls[0]) < 0)
            {
                btSimpan.prop("disabled", false);
                btTutup.prop("disabled", false);

                alert(ls[1]);
                return;
            }

            opener.refresh();
            opener.dataSync();
            
            close();
        },
        error: function (xhr)
        {
            btSimpan.prop("disabled", false); 
            btTutup.prop("disabled", true);  

            alert(xhr.responseText);
        }
    });

   
}