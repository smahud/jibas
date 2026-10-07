<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
if (!function_exists('proc_open')) exit("Jalankan PHP CLI dengan -d disable_functions=\n");
$checks=0;
function privacyEndpoint($variant,$endpoint,$nis,$actor,$expected,$extra=array())
{
    global $checks;
    $process=proc_open(array_merge(array(PHP_BINARY,__DIR__.'/rekappembayaran_endpoint_fixture.php',$endpoint,$nis,$variant,$actor),$extra),
        array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w')),$pipes);
    fclose($pipes[0]); $body=stream_get_contents($pipes[1]); fclose($pipes[1]);
    $error=stream_get_contents($pipes[2]); fclose($pipes[2]); $code=proc_close($process);
    if ($code!==0 || $error!=='STATUS='.$expected || preg_match('/Fatal error|Warning:|Parse error/',$body))
        throw new RuntimeException($variant.' '.$endpoint.' '.$actor.' expected '.$expected.': '.$error);
    $checks++; return $body;
}
function privacyCheck($condition,$message)
{
    global $checks; $checks++; if (!$condition) throw new RuntimeException($message);
}
foreach (array('classic','rinjani') as $variant) {
    foreach (array('main','header','blank','pilih','content','cetak','excel','surat_lunas') as $endpoint) {
        privacyEndpoint($variant,$endpoint,'001','staff',403);
        privacyEndpoint($variant,$endpoint,'001','managerNone',403);
    }
    foreach (array('content','cetak','excel','surat_lunas') as $endpoint) {
        $body=privacyEndpoint($variant,$endpoint,'001','managerB',404);
        privacyCheck(strpos($body,'Siswa Contoh')===false,'NIS di luar scope membocorkan identitas.');
        privacyEndpoint($variant,$endpoint,'001','managerC',404); // Ada transaksi MI, tidak ada riwayat akademik MI.
    }
    $body=privacyEndpoint($variant,'content','001','managerA',200);
    privacyCheck(strpos($body,'Rp 365')!==false && strpos($body,'Departemen: MI')!==false,'Scope RA memotong riwayat MI.');
    if ($variant==='rinjani') privacyCheck(strpos($body,'rinjani/style/style.css')!==false && strpos($body,'rekapsiswa.cetak.php')!==false,'UI Rinjani salah aset/route.');
    privacyEndpoint($variant,'cetak','001','managerA',200);
    privacyCheck(substr(privacyEndpoint($variant,'excel','001','managerA',200),0,4)==="PK\x03\x04",'XLSX tercemar.');
    privacyEndpoint($variant,'surat_lunas','001','managerA',409);
    privacyEndpoint($variant,'surat_lunas','002','managerC',200);
    privacyEndpoint($variant,'surat_lunas','002','managerA',404);
    privacyEndpoint($variant,'content','002','landlord',200);
    privacyEndpoint($variant,'header','001','landlord',200);
    $body=privacyEndpoint($variant,'header','001','managerA',200);
    privacyCheck(strpos($body,'value="MI"')===false && strpos($body,'value="RA"')!==false,'Dropdown melampaui scope.');
    $body=privacyEndpoint($variant,'pilih','001','managerA',200,array('RA'));
    privacyCheck(strpos($body,'nis=001')!==false,'Pencarian riwayat RA gagal.');
    privacyEndpoint($variant,'pilih','001','managerA',403,array('MI'));
    $body=privacyEndpoint($variant,'pilih','001','managerB',200);
    privacyCheck(strpos($body,'Siswa Contoh')===false,'Pencarian di luar scope membuka siswa.');
    privacyEndpoint($variant,'akses','001','managerA',403);
    privacyEndpoint($variant,'akses','001','landlord',200);
    privacyEndpoint($variant,'akses','001','landlord',403,array('badcsrf'));
    privacyEndpoint($variant,'akses','001','landlord',303,array('validcsrf','MI'));
    privacyEndpoint($variant,'akses','001','landlord',400,array('validcsrf','ALL'));
}
echo 'PASS: '.$checks." privacy checks across classic and Rinjani\n";
