<?php
require_once __DIR__ . '/rekappembayaran_model.php';

class RpAccessDenied extends RuntimeException {}

function RpIsLandlord($session)
{
    return isset($session['login'], $session['tingkatkeuangan'])
        && $session['login'] === 'landlord' && (string)$session['tingkatkeuangan'] === '0';
}

function RpResolveScope($db, $session)
{
    if (RpIsLandlord($session)) return null; // null berarti unrestricted, hanya landlord.
    if (!RpCanAccess($session) || (string)($session['tingkatkeuangan'] ?? '') !== '1'
        || empty($session['login']) || !is_string($session['login']))
        throw new RpAccessDenied('Hak akses rekap tidak tersedia.');
    $rows = RpRows($db, "SELECT DISTINCT d.departemen FROM jbsuser.hakakses h
        JOIN jbsakad.departemen d ON d.departemen=h.departemen
        JOIN jbsuser.login l ON l.login=h.login AND l.aktif=1
        JOIN jbssdm.pegawai p ON p.nip=h.login AND p.aktif=1
        WHERE h.login=? AND h.modul='KEUANGAN' AND h.tingkat=1
          AND h.departemen IS NOT NULL AND h.departemen<>'' AND h.departemen<>'ALL'
        ORDER BY d.urutan,d.departemen", 's', array($session['login']));
    $scope = array_column($rows, 'departemen');
    if (!$scope) throw new RpAccessDenied('Departemen rekap belum ditetapkan atau hak akses telah dicabut. Hubungi landlord.');
    return $scope;
}

function RpEligibility($scope, &$params)
{
    if ($scope === null) return '1=1';
    if (!is_array($scope) || !$scope) throw new RpAccessDenied('Departemen rekap tidak tersedia.');
    $placeholders = implode(',', array_fill(0, count($scope), '?'));
    $params = array_merge($params, $scope, $scope);
    // Tidak memakai transaksi/tagihan sebagai bukti pernah terdaftar.
    return '(t.departemen IN (' . $placeholders . ') OR EXISTS (
        SELECT 1 FROM jbsakad.riwayatkelassiswa r
        JOIN jbsakad.kelas rk ON rk.replid=r.idkelas
        JOIN jbsakad.tingkat rt ON rt.replid=rk.idtingkat
        WHERE r.nis=s.nis AND rt.departemen IN (' . $placeholders . ')))';
}

function RpValidateDepartment($scope, $department)
{
    if ($scope !== null && $department !== '' && !in_array($department, $scope, true))
        throw new RpAccessDenied('Departemen pencarian di luar hak akses.');
    return $department !== '' ? array($department) : $scope;
}

function RpSetManagerDepartment($db, $session, $id, $department)
{
    if (!RpIsLandlord($session)) throw new RpAccessDenied('Hanya landlord dapat menetapkan departemen manajer.');
    if ($id < 1) throw new InvalidArgumentException('ID hak akses tidak valid.');
    mysqli_begin_transaction($db);
    try {
        $rows = RpRows($db, "SELECT replid FROM jbsuser.hakakses WHERE replid=? AND modul='KEUANGAN' AND tingkat=1 FOR UPDATE", 'i', array($id));
        if (!$rows) throw new InvalidArgumentException('Hak akses manajer tidak ditemukan.');
        if ($department !== '' && ($department === 'ALL' || !RpRows($db,
            'SELECT departemen FROM jbsakad.departemen WHERE departemen=?', 's', array($department))))
            throw new InvalidArgumentException('Departemen tidak valid.');
        $statement = mysqli_prepare($db, "UPDATE jbsuser.hakakses SET departemen=NULLIF(?,'') WHERE replid=? AND modul='KEUANGAN' AND tingkat=1");
        if (!$statement) throw new RuntimeException('Penetapan gagal.');
        try {
            mysqli_stmt_bind_param($statement, 'si', $department, $id);
            if (!mysqli_stmt_execute($statement)) throw new RuntimeException('Penetapan gagal.');
        } finally { mysqli_stmt_close($statement); }
        if (!mysqli_commit($db)) throw new RuntimeException('Penetapan gagal diselesaikan.');
    } catch (Throwable $e) { mysqli_rollback($db); throw $e; }
}
