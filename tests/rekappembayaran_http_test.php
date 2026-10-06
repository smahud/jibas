<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$base = 'http://localhost/jibas/keuangan/';
$checks=0;
function requestCheck($path,$cookie,$expected) {
 global $base,$checks;
 $curl=curl_init($base.$path);
 curl_setopt_array($curl,array(CURLOPT_RETURNTRANSFER=>true,CURLOPT_COOKIE=>$cookie,CURLOPT_TIMEOUT=>10));
 $body=curl_exec($curl); $code=curl_getinfo($curl,CURLINFO_HTTP_CODE); curl_close($curl);
 if ($code!==$expected || preg_match('/Fatal error|Warning:|Parse error/', $body)) throw new Exception('HTTP test failed: '.$path.' status='.$code);
 $checks++; return $body;
}
$paths=array('laprekappembayaran_siswa_main.php','laprekappembayaran_siswa_header.php','laprekappembayaran_siswa_pilih.php','laprekappembayaran_siswa_blank.php','laprekappembayaran_siswa_content.php','laprekappembayaran_siswa_cetak.php','laprekappembayaran_siswa_excel.php','laprekappembayaran_siswa_surat_lunas.php','library/cari_siswa_rekappembayaran.php');
foreach ($paths as $path) requestCheck($path,'',401);
foreach (array('2','1') as $level) {
 session_name('jbskeu'); session_id('rekaptest'.bin2hex(random_bytes(16))); session_start();
 $_SESSION=array('namakeuangan'=>'Temporary Test','tingkatkeuangan'=>$level,'departemenkeuangan'=>'ALL');
 $cookie='jbskeu='.session_id(); session_write_close();
 try {
  foreach ($paths as $path) {
   $expected=$level==='2'?403:(in_array($path,array('laprekappembayaran_siswa_content.php','laprekappembayaran_siswa_cetak.php','laprekappembayaran_siswa_excel.php','laprekappembayaran_siswa_surat_lunas.php'))?400:200);
   requestCheck($path,$cookie,$expected);
  }
  if ($level==='1') {
   requestCheck('laprekappembayaran_siswa_content.php?nis=fixture-not-exist',$cookie,404);
   requestCheck('laprekappembayaran_siswa_content.php?nis%5B%5D=1',$cookie,400);
   $body=requestCheck('laprekappembayaran_siswa_pilih.php?cari=1&nama=ab',$cookie,200);
   if (strpos($body,'minimal 3 karakter')===false) throw new Exception('Validation message missing');
  }
 } finally { session_start(); $_SESSION=array(); session_destroy(); }
}
echo 'PASS: '.$checks." Apache HTTP checks\n";