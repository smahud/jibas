function showPenentuan(no)
{
    let data64 = $("#data" + no).val();

    parent.daftar.location.href = "rapor.nilai.penentuan.php?data=" + data64;
}