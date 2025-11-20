<?php
// export_csv.php
include '../Config/koneksi.php';
include 'laporan_data.php';
session_start();

$results = get_laporan_data($conn);
$data = $results['data'];
$totals = $results['totals'];

$filename = "laporan_".date('Ymd_His').".csv";
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename='.$filename);

$out = fopen('php://output', 'w');
fputcsv($out, ['Periode','Pemasukan','Pengeluaran','Laba']);

foreach ($data as $r) {
    fputcsv($out, [$r['periode'], $r['pemasukan'], $r['pengeluaran'], $r['laba']]);
}

fputcsv($out, []);
fputcsv($out, ['TOTAL', $totals['totalPemasukan'], $totals['totalPengeluaran'], $totals['totalLaba']]);
fclose($out);
exit;
