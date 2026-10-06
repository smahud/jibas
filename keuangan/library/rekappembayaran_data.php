<?php

function RpRows($db, $sql, $types = '', $params = array())
{
    $statement = mysqli_prepare($db, $sql);
    if (!$statement) throw new RuntimeException('Query laporan gagal.', mysqli_errno($db));
    try {
        if ($types !== '' && !mysqli_stmt_bind_param($statement, $types, ...$params))
            throw new RuntimeException('Parameter query gagal.');
        if (!mysqli_stmt_execute($statement)) throw new RuntimeException('Query laporan gagal.', mysqli_stmt_errno($statement));
        $result = mysqli_stmt_get_result($statement);
        if (!$result) throw new RuntimeException('Hasil query gagal.');
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $rows;
    } finally {
        mysqli_stmt_close($statement);
    }
}

function RpDepartments($db)
{
    // Departemen nonaktif tetap bisa dicari untuk siswa/alumni dan riwayat lama.
    return RpRows($db, 'SELECT departemen FROM jbsakad.departemen ORDER BY urutan, departemen');
}

function RpSearchStudents($db, $nis, $name, $department, $page)
{
    if ($nis === '' && mb_strlen($name, 'UTF-8') < 3)
        throw new InvalidArgumentException('Isi NIS lengkap atau nama minimal 3 karakter.');
    $sql = 'SELECT s.nis, s.nama, s.aktif, s.alumni, k.kelas, t.tingkat, t.departemen
        FROM jbsakad.siswa s
        LEFT JOIN jbsakad.kelas k ON k.replid = s.idkelas
        LEFT JOIN jbsakad.tingkat t ON t.replid = k.idtingkat WHERE 1=1';
    $params = array();
    if ($nis !== '') {
        $sql .= ' AND s.nis = ?';
        $params[] = $nis;
    }
    if ($name !== '') {
        // ESCAPE '=' menjadikan %, _ dan = literal, bukan wildcard dari input.
        $sql .= " AND s.nama LIKE ? ESCAPE '='";
        $params[] = '%' . str_replace(array('=', '%', '_'), array('==', '=%', '=_'), $name) . '%';
    }
    if ($department !== '') {
        $sql .= ' AND (t.departemen = ? OR EXISTS (
            SELECT 1 FROM jbsakad.riwayatkelassiswa r
            JOIN jbsakad.kelas rk ON rk.replid=r.idkelas
            JOIN jbsakad.tingkat rt ON rt.replid=rk.idtingkat
            WHERE r.nis=s.nis AND rt.departemen=?) OR EXISTS (
            SELECT 1 FROM jbsfina.besarjtt b
            LEFT JOIN jbsfina.tahunbuku tb ON b.info2=CAST(tb.replid AS CHAR)
            LEFT JOIN jbsfina.datapenerimaan d ON d.replid=b.idpenerimaan
            WHERE b.nis=s.nis AND COALESCE(NULLIF(tb.departemen,\'\'),d.departemen)=?) OR EXISTS (
            SELECT 1 FROM jbsfina.penerimaaniuran p
            LEFT JOIN jbsfina.jurnal j ON j.replid=p.idjurnal
            LEFT JOIN jbsfina.tahunbuku tb ON tb.replid=j.idtahunbuku
            LEFT JOIN jbsfina.datapenerimaan d ON d.replid=p.idpenerimaan
            WHERE p.nis=s.nis AND COALESCE(NULLIF(tb.departemen,\'\'),d.departemen)=?))';
        for ($i = 0; $i < 4; $i++) $params[] = $department;
    }
    $sql .= ' ORDER BY s.nama, s.nis LIMIT 51 OFFSET ' . ((int)$page * 50);
    return RpRows($db, $sql, str_repeat('s', count($params)), $params);
}

function RpLoadReport($db, $nis)
{
    // Ambil satu snapshot untuk seluruh query agar edit cicilan bersamaan tidak
    // menghasilkan campuran data pada ringkasan, detail, atau keputusan surat.
    if (!mysqli_begin_transaction($db, MYSQLI_TRANS_START_READ_ONLY | MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT))
        throw new RuntimeException('Snapshot laporan tidak dapat dimulai.');
    try {
        $report = RpReadReport($db, $nis);
        if (!mysqli_commit($db)) throw new RuntimeException('Snapshot laporan tidak dapat diselesaikan.');
        return $report;
    } catch (Throwable $e) {
        mysqli_rollback($db);
        throw $e;
    }
}

function RpReadReport($db, $nis)
{
    $students = RpRows($db, 'SELECT s.nis, s.nama, s.aktif, s.alumni, k.kelas, t.tingkat, t.departemen
        FROM jbsakad.siswa s LEFT JOIN jbsakad.kelas k ON k.replid=s.idkelas
        LEFT JOIN jbsakad.tingkat t ON t.replid=k.idtingkat WHERE s.nis=?', 's', array($nis));
    if (!$students) return null;
    // Tidak JOIN penerimaanjtt: tagihan tanpa pembayaran harus tetap muncul.
    // Tahun tagihan besarjtt.info2 tetap dipakai saat cicilan dibayar di tahun buku berikutnya.
    $charges = RpRows($db, "SELECT b.replid AS id, b.besar, b.lunas, b.keterangan, d.nama,
        d.departemen AS departemen_jenis, tb.replid AS idtahunbuku, tb.tahunbuku, tb.tanggalmulai,
        COALESCE(NULLIF(tb.departemen,''),d.departemen) AS departemen
        FROM jbsfina.besarjtt b LEFT JOIN jbsfina.datapenerimaan d ON d.replid=b.idpenerimaan
        LEFT JOIN jbsfina.tahunbuku tb ON b.info2=CAST(tb.replid AS CHAR)
        WHERE b.nis=? ORDER BY d.nama,b.replid", 's', array($nis));
    // Query detail sekali, bukan satu query per tagihan (N+1).
    $installments = RpRows($db, 'SELECT p.replid AS id, p.idbesarjtt, p.jumlah, p.info1 AS diskon_raw,
        p.tanggal, p.keterangan, p.petugas, j.replid AS journal_id, j.nokas,
        tb.tahunbuku AS tahunbuku_transaksi
        FROM jbsfina.penerimaanjtt p JOIN jbsfina.besarjtt b ON b.replid=p.idbesarjtt
        LEFT JOIN jbsfina.jurnal j ON j.replid=p.idjurnal
        LEFT JOIN jbsfina.tahunbuku tb ON tb.replid=j.idtahunbuku
        WHERE b.nis=? ORDER BY p.tanggal,p.replid', 's', array($nis));
    $voluntary = RpRows($db, "SELECT p.replid AS id, p.idpenerimaan, p.jumlah, p.tanggal, p.keterangan,
        p.petugas, d.nama, d.departemen AS departemen_jenis, j.replid AS journal_id, j.nokas,
        tb.replid AS idtahunbuku, tb.tahunbuku, tb.tanggalmulai,
        COALESCE(NULLIF(tb.departemen,''),d.departemen) AS departemen
        FROM jbsfina.penerimaaniuran p LEFT JOIN jbsfina.datapenerimaan d ON d.replid=p.idpenerimaan
        LEFT JOIN jbsfina.jurnal j ON j.replid=p.idjurnal
        LEFT JOIN jbsfina.tahunbuku tb ON tb.replid=j.idtahunbuku
        WHERE p.nis=? ORDER BY p.tanggal,p.replid", 's', array($nis));
    return RpBuildReport($students[0], $charges, $installments, $voluntary);
}

function RpSchoolIdentity($db, $department)
{
    // Utamakan identitas departemen siswa, lalu identitas umum jika belum diisi.
    $rows = RpRows($db, 'SELECT replid,nama,alamat1,alamat2,telp1,email,departemen
        FROM jbsumum.identitas ORDER BY (departemen=?) DESC,(departemen=\'\' OR departemen IS NULL) DESC,replid LIMIT 1', 's', array((string)$department));
    return $rows ? $rows[0] : array('nama' => 'Sekolah', 'alamat1' => '', 'alamat2' => '', 'telp1' => '', 'email' => '');
}
