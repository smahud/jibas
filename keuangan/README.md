# Analisis modul Keuangan

Dokumen ini menjelaskan alur source yang diperiksa serta dasar pembangunan ulang Rekap Pembayaran Siswa. Tanggal verifikasi: 6 Oktober 2026. Ini bukan audit menyeluruh semua fitur Keuangan.

## Struktur aplikasi

Modul memakai PHP prosedural, mysqli, halaman HTML, dan JavaScript. Banyak laporan legacy memakai frameset: halaman `*_main.php` merangkai header filter, panel pemilihan, dan panel isi; `*_cetak.php`/`*_excel.php` membuat keluaran terpisah. Query lintas database menggunakan nama qualified seperti `jbsakad.siswa` dan `jbsfina.besarjtt`.

| Bagian | Entry point / direktori | Alur |
|---|---|---|
| Login dan sesi | `login.php`, `redirect.php`, `include/sessionchecker.php`, `include/sessioninfo.php` | Sesi bernama `jbskeu`, dengan nama pengguna, tingkat, dan akses departemen |
| Penerimaan | `penerimaan.php`, `pembayaran_*.php`, `penerimaan/` | Pendataan tagihan, angsuran wajib, sukarela, calon siswa, multi payment, dan batch payment |
| Pengeluaran | `pengeluaran.php`, `pengeluaran_*.php` | Pengeluaran dan jurnalnya, laporan per jenis serta pencarian transaksi |
| Jurnal | `library/jurnal.php`, `jurnal*.php`, `jurnalumum.php` | Pencatatan header jurnal dan pasangan debit/kredit |
| Laporan keuangan | `lapkeuangan.php`, `lapbukubesar_*`, `lapneraca*`, `laprugilaba_*`, `lapmodal_*`, `lapcashflow_*` | Buku besar, neraca percobaan, rugi laba, perubahan modal, neraca, arus kas |
| Referensi | `referensi.php`, `akunrek*`, `tahunbuku*`, `tutupbuku*` | Akun, tahun buku per departemen, dan penutupan buku |
| Tabungan | `tabungan/`, `tabunganp/` | Tabungan siswa dan pegawai |
| Integrasi / pendukung | `schoolpay/`, `onlinepay/`, `rinjani/`, `inventori/` | Pembayaran terintegrasi, varian layar pembayaran, dan inventori |

## Konfigurasi dan koneksi

`keuangan/include/config.php` memuat konfigurasi global melalui `include/mainconfig.php`, menjalankan mekanisme patch JIBAS, lalu menetapkan `$db_name = 'jbsfina'`. Konfigurasi global mencakup koneksi, aplikasi, sekolah, file sharing, dan zona waktu. Koneksi berasal dari `include/database.config.php` di root proyek.

`keuangan/include/db_functions.php` menyediakan `OpenDb()`, `QueryDb()`, `CloseDb()`, dan transaksi. Koneksi disimpan di global `$mysqlconnection`. `library/departemen.php::getDepartemen()` langsung menjalankan query, sehingga koneksi **harus dibuka sebelum** fungsi itu digunakan. Ini menjelaskan error koneksi null yang dicatat dokumentasi upgrade lama.

`config.php` juga mengubah `$_REQUEST` dengan penggantian karakter dan `addslashes`. Fitur baru memakai input `$_GET` yang divalidasi dan prepared statements, sehingga nama/NIS tidak melewati pengubahan teks tersebut. Whitespace dari include legacy ditampung dan dibuang sebelum mengirim header HTTP atau XLSX.

## Hak akses

`redirect.php` menetapkan:

| Tingkat | Peran | Akses menurut `getAccess()` legacy |
|---|---|---|
| `0` | Administrator `landlord` | `ALL` |
| `1` | Manajer Keuangan | `ALL` |
| `2` | Staf | Departemen dari hak akses |

Fitur rekap mengizinkan tingkat `0`/`1` dan menolak tingkat lainnya. Pemeriksaan berada di bootstrap yang dipakai **setiap endpoint**, termasuk cetak, ekspor, surat, dan library pencarian. Staf yang nilai departemennya `ALL` tetap ditolak. Pengguna belum login mendapat HTTP 401, pengguna tanpa izin mendapat 403.

## Model tagihan dan pembayaran

Pendataan besar pembayaran di `pembayaran_jtt.php` dan `penerimaan/inputbayar.func.php` menghasilkan jurnal piutang/pendapatan, kemudian baris `besarjtt`. Angsuran di `pembayaranjtt_add.php` menghasilkan jurnal serta baris `penerimaanjtt`. `library/jurnal.php::SimpanJurnal()` membuat header; `SimpanDetailJurnal()` membuat pasangan debit/kredit.

| Data | Relasi / makna yang dikonfirmasi dari skema dan source |
|---|---|
| `besarjtt.nis` | Siswa pemilik tagihan wajib |
| `besarjtt.idpenerimaan` | Jenis pembayaran pada `datapenerimaan` |
| `besarjtt.besar` | Nominal kewajiban rupiah bulat |
| `besarjtt.info1` | ID jurnal pendataan tagihan pada alur pendataan yang diperiksa |
| `besarjtt.info2` | ID tahun buku **tagihan**, disimpan sebagai varchar |
| `besarjtt.lunas` | Flag tersimpan; alur pendataan memakai `2` untuk gratis |
| `penerimaanjtt.idbesarjtt` | Tagihan yang dicicil |
| `penerimaanjtt.jumlah` | Nominal tunai; bukan tunai ditambah diskon |
| `penerimaanjtt.info1` | Diskon angsuran, disimpan sebagai varchar |
| `penerimaanjtt.idjurnal` | Header jurnal angsuran |
| `penerimaaniuran` | Penerimaan sukarela dengan NIS dan jenis penerimaan; tahun buku mengikuti jurnal |
| `jurnal.idtahunbuku` | Tahun buku **transaksi**; bisa berbeda dari tahun tagihan lama yang sedang dicicil |
| `tahunbuku.departemen` | Departemen tahun buku keuangan |
| `datapenerimaan.departemen` | Departemen jenis penerimaan |

Struktur akademik memakai `siswa.idkelas → kelas.idtingkat → tingkat.departemen`. `tingkat` **tidak memiliki `iddepartemen`**. `riwayatkelassiswa` menyimpan kelas-kelas terdahulu dengan NIS. Kelas saat ini tidak cukup untuk menentukan departemen pembayaran historis.

## Temuan pada upgrade terdahulu

Dokumentasi dan source upgrade yang dihapus masih tersedia dalam riwayat Git dan dibaca sebagai acuan kebutuhan. Implementasi yang diperiksa memakai relasi `tingkat.iddepartemen` yang tidak ada pada skema lokal. Pengelompokan riwayat mengikuti kelas siswa saat ini dan beberapa query awal memakai inner join angsuran: tagihan tanpa pembayaran bisa tidak masuk laporan. Perhitungan pembayaran wajib juga memakai tunai + diskon dalam satu nilai, sehingga perlu pemisahan label untuk membedakan penyelesaian kewajiban dari penerimaan kas.

Pembangunan ulang memisahkan akses/input, query, model perhitungan, tampilan bersama, dan penulis XLSX. Aturan lengkap ada di [README_REKAP_PEMBAYARAN_SISWA.md](../README_REKAP_PEMBAYARAN_SISWA.md). Nama URL lama dipertahankan; tampilan utama memakai tiga iframe agar tetap cocok dengan pola navigasi modul.

## Batas analisis dan integrasi

- Laporan adalah pembacaan data. Query fitur tidak mengubah flag lunas, memperbaiki jurnal, membuat tagihan, atau membutuhkan migrasi skema. Konfigurasi dan mekanisme patch global tetap mengikuti JIBAS.
- Pembacaan laporan memakai transaksi read-only dengan consistent snapshot. Jaminan snapshot antarkueri mengikuti dukungan engine database; tabel permanen nontransaksional seperti MyISAM tidak memperoleh jaminan snapshot InnoDB.
- Rekap mengikuti satu NIS. Jika NIS berubah saat pindah jenjang, riwayat pada NIS lain tidak digabung berdasarkan nama; diperlukan pemetaan identitas yang terverifikasi.
- Laporan tidak mengaudit keseimbangan `jurnaldetail`, seluruh rekening, tabungan, pembayaran calon siswa, atau seluruh integrasi gateway.
- Pola legacy seperti interpolasi SQL, sesi yang dibuka berulang, PHPExcel lama, dan query per baris masih ada pada halaman lain. Perubahan ini dibatasi pada fitur rekap.
- Skema dan query diperiksa pada database lokal yang dapat diakses, tetapi tabel siswa/tagihan/pembayaran lokal kosong saat verifikasi. Perilaku berisi data diuji dengan tabel sementara terisolasi, bukan data operasional sekolah.
