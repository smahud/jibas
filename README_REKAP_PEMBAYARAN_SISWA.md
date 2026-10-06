# Rekap Pembayaran Siswa - Feature Documentation

## Overview
New report feature under **Penerimaan > Laporan** that displays complete payment history for a student across all departments they've attended (e.g., RA → MI → MTs).

## Files Added/Modified

### Modified Files
| File | Change |
|------|--------|
| `keuangan/penerimaan.php` | Added menu link "Rekap Pembayaran Siswa" under Laporan section |

### New Report Files (`keuangan/`)
| File | Purpose |
|------|---------|
| `laprekappembayaran_siswa_main.php` | Main 3-frame layout (header + left panel + right panel) |
| `laprekappembayaran_siswa_header.php` | Header with Departemen dropdown + access control |
| `laprekappembayaran_siswa_pilih.php` | Left panel - loads search library |
| `laprekappembayaran_siswa_blank.php` | Initial blank right panel |
| `laprekappembayaran_siswa_content.php` | Main content - complete payment history |
| `laprekappembayaran_siswa_cetak.php` | Print: Rekap Pembayaran Siswa |
| `laprekappembayaran_siswa_surat_lunas.php` | Print: Surat Keterangan Lunas Tanggungan |
| `laprekappembayaran_siswa_excel.php` | Excel export |

### New Library File (`keuangan/library/`)
| File | Purpose |
|------|---------|
| `cari_siswa_rekappembayaran.php` | Student search by NIS (exact) or Name (min 3 chars) |

## Features

### 1. Access Control
- Only accessible by **Manajer Keuangan** with "ALL" department access (`getAccess() == "ALL"`)
- Other users see alert and are redirected to Penerimaan

### 2. Student Search (Left Panel)
- Search by **NIS** (exact match) or **Nama** (partial, minimum 3 characters)
- Departemen filter dropdown
- Results show: NIS, Nama, Departemen, Tingkat, Kelas

### 3. Complete Payment History (Right Panel)
Grouped by **Departemen → Tahun Buku**:
- **Iuran Wajib (JTT)**: Per jenis pembayaran with detail angsuran
- **Iuran Sukarela**: Per jenis pembayaran with detail angsuran
- **Per item**: Besar bayaran, total dibayar, diskon, sisa, status LUNAS/BELUM LUNAS
- **Per departemen**: Rekapitulasi subtotal + status keseluruhan
- **Grand Total**: Keseluruhan semua departemen

### 4. Two Print Options
| Button | Output |
|--------|--------|
| **Cetak Rekap** | Full payment history with grand totals, formatted for printing |
| **Surat Lunas** | Official letter with school header, student info, per-dept breakdown, signature lines |

### 5. Excel Export
Same data structure as print version in `.xls` format

## Bug Fixes Applied

### Fix 1: Database Connection Order (Header & Pilih)
**Problem**: `OpenDb()` called AFTER `getDepartemen()`, causing `mysqli_query(): Argument #1 ($mysql) must be of type mysqli, null given`

**Files Fixed**:
- `laprekappembayaran_siswa_header.php` - Moved `OpenDb()` before first `getDepartemen()`
- `laprekappembayaran_siswa_pilih.php` - Added `OpenDb()` before `getDepartemen()` + `CloseDb()` after

**Root Cause**: `getDepartemen()` in `library/departemen.php` calls `QueryDb()` which requires active DB connection

## Usage

### Access URL
```
http://localhost/jibas/keuangan/laprekappembayaran_siswa_main.php
```
Or via menu: **Keuangan → Penerimaan → Laporan → Rekap Pembayaran Siswa**

### Steps
1. Select **Departemen** (default: first accessible)
2. Enter **NIS** or **Nama Siswa** (min 3 chars) → Click **Cari**
3. Click student row in left panel
4. View complete payment history in right panel
5. Use buttons: **Refresh**, **Cetak Rekap**, **Surat Lunas**, **Excel**

## Naming Convention Compliance
Follows existing JIBAS patterns:
- Reports: `laprekappembayaran_siswa_*` (like `lapbayarsiswa_all_*`, `laprekap_*`)
- Search library: `cari_siswa_rekappembayaran.php` (like `cari_siswa.php`, `cari_calonsiswa.php`)

## Database Tables Used
- `jbsakad.siswa` - Student data
- `jbsakad.kelas`, `jbsakad.tingkat`, `jbsakad.departemen` - Academic structure
- `besarjtt`, `penerimaanjtt` - Iuran Wajib
- `penerimaaniuran` - Iuran Sukarela
- `datapenerimaan` - Payment types
- `jurnal`, `tahunbuku` - Journal & academic year
- `jbsumum.identitas` - School identity (for print headers)

## Testing Checklist
- [ ] Menu appears under Penerimaan > Laporan
- [ ] Access denied for non-Manajer Keuangan users
- [ ] Departemen dropdown loads correctly
- [ ] Search by NIS works (exact match)
- [ ] Search by Name works (min 3 chars)
- [ ] Student selection loads payment history
- [ ] History grouped by Departemen → Tahun Buku
- [ ] Iuran Wajib & Sukarela both display
- [ ] Per-installment detail visible
- [ ] LUNAS/BELUM LUNAS status correct
- [ ] Per-department subtotals correct
- [ ] Grand totals correct
- [ ] Cetak Rekap opens and prints
- [ ] Surat Lunas opens with school header
- [ ] Excel export downloads .xls file
- [ ] No PHP errors in logs