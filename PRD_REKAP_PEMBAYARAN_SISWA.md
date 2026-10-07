# PRD Rekap Pembayaran Siswa - JIBAS 36.0

Tanggal: 7 Oktober 2026. Status: dibuat sebelum implementasi; implementasi dan verifikasi selesai pada 7 Oktober 2026. Dokumen ini menjadi acuan patch Keuangan klasik dan Keuangan Rinjani; hasil eksekusi dicatat di README_REKAP_PEMBAYARAN_SISWA.md.

## Tujuan

Menyediakan dua antarmuka yang mengikuti navigasi masing-masing versi JIBAS, dengan satu mesin perhitungan dan kebijakan privasi. Manajer hanya dapat memilih siswa yang sedang atau pernah terdaftar di departemen yang diberikan kepadanya. Setelah siswa memenuhi syarat, seluruh riwayat keuangan siswa pada semua departemen/tahun buku ditampilkan. Landlord memiliki akses penuh tanpa penetapan departemen.

## Matriks akses

| Pengguna | Menu | Siswa yang dapat diakses | Isi laporan |
|---|---|---|---|
| Landlord, level 0 | Tampil | Semua siswa | Seluruh riwayat satu NIS |
| Manajer, level 1, departemen ditetapkan | Tampil | Siswa kelas terakhir atau riwayat kelas pada departemen tersebut | Seluruh riwayat satu NIS, termasuk departemen lain |
| Manajer tanpa departemen valid | Menu dapat menjelaskan perlunya penetapan; data ditolak | Tidak ada | Tidak tersedia |
| Staf, level 2, atau level lain | Tidak tampil | Tidak ada | Semua endpoint ditolak |

Status aktif/nonaktif/alumni tidak membatasi pencarian. Keberadaan pembayaran pada suatu departemen saja tidak membuktikan hak melihat siswa. Nama tidak dipakai untuk menggabungkan NIS berbeda. Hak akses diverifikasi di server pada pencarian, laporan, cetak, Excel, dan surat, termasuk ketika URL/parameter dimanipulasi.

## Penyimpanan penetapan departemen

Gunakan field existing `jbsuser.hakakses.departemen` untuk baris `modul=KEUANGAN`, `tingkat=1`. Pengaturan pengguna harus dapat menyimpan departemen manajer pada Keuangan klasik maupun Rinjani. Tidak diperlukan tabel baru. Nilai NULL/kosong/ALL pada manajer bukan izin global untuk fitur rekap; manajer lama harus ditetapkan departemennya oleh administrator. Jika ada beberapa baris hak akses manajer, izin rekap merupakan gabungan departemen valid tersebut. Hak rekap dibaca ulang dari database agar perubahan/pencabutan izin tidak bergantung pada sesi lama.

Perilaku global `getAccess()` dan modul keuangan lain tidak diubah menjadi terbatas secara diam-diam. Pembatasan baru diterapkan khusus pada rekap melalui kebijakan bersama. Perubahan penetapan disediakan pada halaman khusus yang hanya dapat dipakai landlord, ditautkan dari pengaturan pengguna kedua antarmuka; halaman ini memperbarui field hakakses manajer tanpa mengubah password atau peran.

Pengelolaan daftar/penambahan/pengubahan/penghapusan akun Keuangan legacy dan Rinjani dibatasi ke landlord agar manajer tidak bisa mengubah peran/departemennya melalui jalur administrasi lama. Ganti password sendiri tetap tersedia. Ini merupakan perubahan hak administrasi yang disengaja untuk menjaga sumber penetapan privasi.

## Antarmuka

- Klasik: pertahankan entry point dan pola header/panel pencarian/panel laporan.
- Rinjani: tambah laporan pada menu Penerimaan/Laporan dengan breadcrumb, font, warna, tombol, dan layout yang sesuai aset Rinjani terbaru. Gunakan endpoint Rinjani untuk laporan/cetak/Excel/surat dengan model bersama.
- Dropdown pencarian hanya berisi departemen yang diizinkan bagi manajer, semua departemen untuk landlord. Pilihan semua pada manajer berarti gabungan izin, bukan seluruh sekolah.
- Semua keluaran mengidentifikasi departemen dan tahun buku keuangan; pencarian dibatasi kelayakan siswa, bukan pemotongan transaksi.

## Perhitungan dan keluaran

Pertahankan aturan patch sebelumnya: tagihan tanpa angsuran tetap tampil, tunai terpisah dari diskon, sisa/kelebihan per tagihan, sukarela bukan kewajiban, dan pembayaran tahun berikutnya tetap pada tahun tagihan asal. Cetak dan XLSX memakai model yang sama. Surat hanya untuk tagihan tercatat yang terselesaikan tanpa masalah integritas; landlord tetap mengikuti kebenaran perhitungan surat.

## Kompatibilitas provider

Baseline yang ditemukan: JIBAS 36.0, Rinjani KEU-36.0.1403. Pertahankan update provider dashboard, Akademik Semeru, dan aset baru. Review perubahan provider dan uji lint/query/endpoints yang terkait. Catat upgrade provider sebagai baseline tersendiri agar diff patch mudah ditinjau. Jangan mengunggah konfigurasi database lokal, log, token, atau data sekolah.

## Kriteria penerimaan

1. Landlord melihat semua siswa tanpa assignment; staf tidak melihat tautan dan ditolak pada kedua versi.
2. Manajer A menemukan siswa dengan riwayat A walaupun sekarang C; laporan tetap mencakup A/C/D.
3. Manajer B tidak menemukan/membuka siswa yang tidak pernah terdaftar di B; manipulasi NIS/departemen pada seluruh keluaran ditolak.
4. Manajer tanpa assignment ditolak, dan pencabutan assignment berlaku pada permintaan berikutnya.
5. Alumni/nonaktif dan nol awal NIS tetap didukung.
6. Assignment hanya bisa diubah landlord dengan validasi server, CSRF, dan prepared statements.
7. Kedua UI menghasilkan nominal/status/cakupan sama, memakai satu implementasi kebijakan/model.
8. Test model, query terisolasi, endpoint, HTTP, lint serta regression provider yang relevan lolos; keterbatasan data nyata/visual dicatat jujur.
9. README root dan README_REKAP_PEMBAYARAN_SISWA.md diperbarui dengan alur assignment, lokasi kedua patch, bukti uji, dan batas.
10. Perubahan di-commit dan push otomatis sesuai instruksi pengguna; kegagalan akses/token dilaporkan.

## Urutan pelaksanaan

PRD -> review dan simpan baseline provider -> implementasi kebijakan/assignment -> integrasi klasik -> integrasi Rinjani -> pengujian privacy dan regression -> dokumentasi -> commit/push.

## Hasil eksekusi

Kedua antarmuka memakai satu kebijakan dan mesin laporan. Penetapan landlord, penolakan staf/manajer tanpa izin, riwayat akademik serta seluruh keluaran diverifikasi melalui 308 pemeriksaan. Pengelolaan akun kedua versi dibatasi landlord. Rincian file, pemasangan dan batas verifikasi tersedia pada README_REKAP_PEMBAYARAN_SISWA.md. Data transaksi nyata dan cetak kertas belum diverifikasi.
