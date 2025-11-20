<?php
// export_pdf.php
include '../Config/koneksi.php';
include 'laporan_data.php';
session_start();

$results = get_laporan_data($conn);
$data = $results['data'];
$totals = $results['totals'];

// check dompdf
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Dompdf belum terpasang. Jalankan: composer require dompdf/dompdf";
    exit;
}

require __DIR__ . '/../vendor/autoload.php';
use Dompdf\Dompdf;

$html = '<h2>Laporan Keuangan</h2>';
$html .= '<table border="1" cellpadding="6" cellspacing="0" width="100%">';
$html .= '<thead><tr><th>Periode</th><th>Pemasukan</th><th>Pengeluaran</th><th>Laba</th></tr></thead><tbody>';
foreach ($data as $r) {
    $html .= '<tr>';
    $html .= '<td>'.$r['periode'].'</td>';
    $html .= '<td>Rp '.number_format($r['pemasukan'],0,',','.').'</td>';
    $html .= '<td>Rp '.number_format($r['pengeluaran'],0,',','.').'</td>';
    $html .= '<td>Rp '.number_format($r['laba'],0,',','.').'</td>';
    $html .= '</tr>';
}
$html .= '</tbody>';
$html .= '<tfoot><tr><td><strong>TOTAL</strong></td>';
$html .= '<td><strong>Rp '.number_format($totals['totalPemasukan'],0,',','.').'</strong></td>';
$html .= '<td><strong>Rp '.number_format($totals['totalPengeluaran'],0,',','.').'</strong></td>';
$html .= '<td><strong>Rp '.number_format($totals['totalLaba'],0,',','.').'</strong></td>';
$html .= '</tr></tfoot></table>';

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('laporan_'.date('Ymd_His').'.pdf', ["Attachment" => 1]);
exit;
