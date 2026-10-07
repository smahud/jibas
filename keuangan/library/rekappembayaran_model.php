<?php
/** Rekap pembayaran lintas departemen. GPL-3.0-or-later, mengikuti JIBAS. */

function RpCanAccess($session)
{
    // Level 0: administrator (landlord), 1: manajer, 2: staf departemen.
    return isset($session['namakeuangan'], $session['tingkatkeuangan'])
        && ((string)$session['tingkatkeuangan'] === '1'
            || ((string)$session['tingkatkeuangan'] === '0' && ($session['login'] ?? '') === 'landlord'));
}

function RpText($input, $key, $maxLength)
{
    if (!isset($input[$key])) return '';
    if (!is_string($input[$key])) throw new InvalidArgumentException('Parameter tidak valid.');
    $value = trim($input[$key]);
    if (mb_strlen($value, 'UTF-8') > $maxLength || preg_match('/[\x00-\x1F\x7F]/', $value))
        throw new InvalidArgumentException('Parameter terlalu panjang atau tidak valid.');
    return $value;
}

function RpMoney($value)
{
    // Semua nominal jbsfina adalah rupiah bulat (decimal(15,0)). Jangan pakai float.
    if ($value === null || $value === '') return 0;
    if (!preg_match('/^-?[0-9]{1,15}$/D', (string)$value))
        throw new InvalidArgumentException('Nominal bukan rupiah bulat yang valid.');
    return (int)$value;
}

function RpTotals()
{
    return array('tagihan' => 0, 'tunai' => 0, 'diskon' => 0, 'sisa' => 0, 'kelebihan' => 0, 'sukarela' => 0);
}

function RpAddTotals(&$target, $source)
{
    foreach ($target as $key => $value) $target[$key] += $source[$key];
}

function RpIssue(&$report, $message)
{
    if (!in_array($message, $report['issues'], true)) $report['issues'][] = $message;
}

function RpGroup(&$report, $row)
{
    $department = trim((string)($row['departemen'] ?? ''));
    if ($department === '') $department = 'Departemen tidak diketahui';
    $yearId = (string)($row['idtahunbuku'] ?? '');
    $year = (string)($row['tahunbuku'] ?? '');
    if ($year === '') $year = 'Tahun buku tidak diketahui';
    // Serialisasi kunci menghindari benturan nama departemen/tahun.
    $key = json_encode(array($department, $yearId));
    if (!isset($report['groups'][$key])) {
        $report['groups'][$key] = array('departemen' => $department, 'idtahunbuku' => $yearId,
            'tahunbuku' => $year, 'mulai' => $row['tanggalmulai'] ?? '',
            'wajib' => array(), 'sukarela' => array(), 'totals' => RpTotals(), 'valid' => true);
    }
    return $key;
}

function RpBuildReport($student, $charges, $installments, $voluntary)
{
    $report = array('student' => $student, 'groups' => array(), 'departments' => array(),
        'totals' => RpTotals(), 'issues' => array(), 'notes' => array(), 'charge_count' => count($charges),
        'department_statuses' => array());
    $byCharge = array();
    foreach ($installments as $payment) $byCharge[(string)$payment['idbesarjtt']][] = $payment;
    foreach ($charges as $charge) {
        $id = (string)$charge['id'];
        $key = RpGroup($report, $charge);
        $item = $charge;
        $item['payments'] = $byCharge[$id] ?? array();
        $item['totals'] = RpTotals();
        $item['valid'] = true;
        if (empty($charge['idtahunbuku']) || empty($charge['departemen']) || empty($charge['nama'])) {
            RpIssue($report, 'Tagihan #' . $id . ': jenis penerimaan, departemen, atau tahun buku tidak ditemukan.');
            $item['valid'] = false;
        }
        if (!empty($charge['departemen_jenis']) && !empty($charge['departemen'])
            && $charge['departemen_jenis'] !== $charge['departemen']) {
            RpIssue($report, 'Tagihan #' . $id . ': departemen jenis penerimaan berbeda dari tahun buku.');
            $item['valid'] = false;
        }
        try {
            $item['totals']['tagihan'] = RpMoney($charge['besar']);
            if ($item['totals']['tagihan'] < 0) throw new InvalidArgumentException();
        } catch (InvalidArgumentException $e) {
            RpIssue($report, 'Tagihan #' . $id . ': nominal tagihan tidak valid.');
            $item['valid'] = false;
        }
        foreach ($item['payments'] as &$payment) {
            try {
                $payment['tunai'] = RpMoney($payment['jumlah']);
                $payment['diskon'] = RpMoney($payment['diskon_raw']);
                if ($payment['tunai'] < 0 || $payment['diskon'] < 0) throw new InvalidArgumentException();
                $item['totals']['tunai'] += $payment['tunai'];
                $item['totals']['diskon'] += $payment['diskon'];
            } catch (InvalidArgumentException $e) {
                $payment['tunai'] = $payment['diskon'] = null;
                RpIssue($report, 'Angsuran #' . $payment['id'] . ': nominal tunai/diskon tidak valid.');
                $item['valid'] = false;
            }
            if (empty($payment['journal_id']) || empty($payment['tahunbuku_transaksi'])) {
                RpIssue($report, 'Angsuran #' . $payment['id'] . ': jurnal atau tahun buku transaksi tidak ditemukan.');
                $item['valid'] = false;
            }
        }
        unset($payment);
        $balance = $item['totals']['tagihan'] - $item['totals']['tunai'] - $item['totals']['diskon'];
        $item['totals']['sisa'] = max(0, $balance);
        $item['totals']['kelebihan'] = max(0, -$balance);
        $item['status'] = !$item['valid'] ? 'PERLU VERIFIKASI'
            : ($balance > 0 ? 'BELUM LUNAS' : ($item['totals']['tagihan'] === 0 ? 'GRATIS' : 'LUNAS'));
        if ($item['valid'] && (((int)$charge['lunas'] > 0) !== ($balance <= 0)))
            $report['notes'][] = 'Tagihan #' . $id . ': flag lunas lama berbeda dari hasil perhitungan transaksi.';
        RpAddTotals($report['groups'][$key]['totals'], $item['totals']);
        if (!$item['valid']) $report['groups'][$key]['valid'] = false;
        $report['groups'][$key]['wajib'][] = $item;
    }
    foreach ($voluntary as $payment) {
        $key = RpGroup($report, $payment);
        $type = (string)$payment['idpenerimaan'];
        if (!isset($report['groups'][$key]['sukarela'][$type]))
            $report['groups'][$key]['sukarela'][$type] = array('nama' => $payment['nama'], 'jumlah' => 0, 'payments' => array());
        try {
            $payment['tunai'] = RpMoney($payment['jumlah']);
            if ($payment['tunai'] < 0) throw new InvalidArgumentException();
            $report['groups'][$key]['sukarela'][$type]['jumlah'] += $payment['tunai'];
            $report['groups'][$key]['totals']['sukarela'] += $payment['tunai'];
        } catch (InvalidArgumentException $e) {
            $payment['tunai'] = null;
            RpIssue($report, 'Iuran sukarela #' . $payment['id'] . ': nominal tidak valid.');
            $report['groups'][$key]['valid'] = false;
        }
        if (empty($payment['journal_id']) || empty($payment['idtahunbuku']) || empty($payment['nama'])
            || empty($payment['departemen'])) {
            RpIssue($report, 'Iuran sukarela #' . $payment['id'] . ': jenis penerimaan, jurnal, atau tahun buku tidak ditemukan.');
            $report['groups'][$key]['valid'] = false;
        }
        if (!empty($payment['departemen_jenis']) && !empty($payment['departemen'])
            && $payment['departemen_jenis'] !== $payment['departemen']) {
            RpIssue($report, 'Iuran sukarela #' . $payment['id'] . ': departemen jenis penerimaan berbeda dari tahun buku.');
            $report['groups'][$key]['valid'] = false;
        }
        $report['groups'][$key]['sukarela'][$type]['payments'][] = $payment;
    }
    uasort($report['groups'], function ($a, $b) {
        return strcmp($a['departemen'], $b['departemen']) ?: strcmp($a['mulai'], $b['mulai'])
            ?: strcmp($a['tahunbuku'], $b['tahunbuku']) ?: strcmp($a['idtahunbuku'], $b['idtahunbuku']);
    });
    $departmentMeta = array();
    foreach ($report['groups'] as &$group) {
        $department = $group['departemen'];
        if (!isset($report['departments'][$department])) $report['departments'][$department] = RpTotals();
        if (!isset($departmentMeta[$department])) $departmentMeta[$department] = array('valid' => true, 'charges' => 0);
        $departmentMeta[$department]['valid'] = $departmentMeta[$department]['valid'] && $group['valid'];
        $departmentMeta[$department]['charges'] += count($group['wajib']);
        $group['status'] = !$group['valid'] ? 'PERLU VERIFIKASI' : (!count($group['wajib'])
            ? 'BELUM ADA TAGIHAN' : ($group['totals']['sisa'] > 0 ? 'BELUM LUNAS' : 'LUNAS'));
        RpAddTotals($report['departments'][$department], $group['totals']);
        RpAddTotals($report['totals'], $group['totals']);
    }
    unset($group);
    foreach ($departmentMeta as $department => $meta)
        $report['department_statuses'][$department] = !$meta['valid'] ? 'PERLU VERIFIKASI' : (!$meta['charges']
            ? 'BELUM ADA TAGIHAN' : ($report['departments'][$department]['sisa'] > 0 ? 'BELUM LUNAS' : 'LUNAS'));
    $report['can_certify'] = $report['charge_count'] > 0 && $report['totals']['sisa'] === 0 && !$report['issues'];
    $report['status'] = $report['issues'] ? 'PERLU VERIFIKASI'
        : ($report['charge_count'] === 0 ? 'BELUM ADA TAGIHAN' : ($report['totals']['sisa'] > 0 ? 'BELUM LUNAS' : 'LUNAS'));
    return $report;
}
