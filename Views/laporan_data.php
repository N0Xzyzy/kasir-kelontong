<?php
// laporan_data.php
// Fungsi yang mengembalikan array $dataFinal dan totals berdasarkan GET params.
// Include this file where needed, after including koneksi and session_start().

function get_laporan_data($conn) {
    // ambil params
    $periode = $_GET['periode'] ?? 'hari';
    $tgl_mulai = $_GET['mulai'] ?? '';
    $tgl_akhir = $_GET['akhir'] ?? '';
    $tgl_tunggal = $_GET['tanggal'] ?? '';

    // sanitize minimal (prepared statements would be better; for simplicity we validate date format)
    $validate_date = function($d) {
        if (!$d) return false;
        $t = date_parse($d);
        return checkdate($t['month'], $t['day'], $t['year']);
    };

    $where = "";

    if ($validate_date($tgl_tunggal)) {
        $tgl = mysqli_real_escape_string($conn, $tgl_tunggal);
        $where = "WHERE tanggal = '$tgl'";
    } elseif ($validate_date($tgl_mulai) && $validate_date($tgl_akhir)) {
        $m = mysqli_real_escape_string($conn, $tgl_mulai);
        $n = mysqli_real_escape_string($conn, $tgl_akhir);
        $where = "WHERE tanggal BETWEEN '$m' AND '$n'";
    }

    // queries for pemasukan and pengeluaran (aggregate by periode)
    switch ($periode) {
        case 'bulan':
            $sqlMasuk = "SELECT YEAR(tanggal) AS tahun, MONTH(tanggal) AS bulan, SUM(jumlah) AS pemasukan
                        FROM pemasukan
                        $where
                        GROUP BY YEAR(tanggal), MONTH(tanggal)
                        ORDER BY tahun DESC, bulan DESC";
            $sqlKeluar = "SELECT YEAR(tanggal) AS tahun, MONTH(tanggal) AS bulan, SUM(total) AS pengeluaran
                        FROM pengeluaran
                        $where
                        GROUP BY YEAR(tanggal), MONTH(tanggal)
                        ORDER BY tahun DESC, bulan DESC";
            break;

        case 'tahun':
            $sqlMasuk = "SELECT YEAR(tanggal) AS tahun, SUM(jumlah) AS pemasukan
                        FROM pemasukan
                        $where
                        GROUP BY YEAR(tanggal)
                        ORDER BY tahun DESC";
            $sqlKeluar = "SELECT YEAR(tanggal) AS tahun, SUM(total) AS pengeluaran
                        FROM pengeluaran
                        $where
                        GROUP BY YEAR(tanggal)
                        ORDER BY tahun DESC";
            break;

        case 'hari':
        default:
            $sqlMasuk = "SELECT tanggal, SUM(jumlah) AS pemasukan
                        FROM pemasukan
                        $where
                        GROUP BY tanggal
                        ORDER BY tanggal DESC";
            $sqlKeluar = "SELECT tanggal, SUM(total) AS pengeluaran
                        FROM pengeluaran
                        $where
                        GROUP BY tanggal
                        ORDER BY tanggal DESC";
            break;
    }

    $resMasuk = mysqli_query($conn, $sqlMasuk);
    $resKeluar = mysqli_query($conn, $sqlKeluar);

    $dataMasuk = [];
    $dataKeluar = [];

    if ($resMasuk) {
        while ($r = mysqli_fetch_assoc($resMasuk)) {
            if ($periode == 'hari') $key = $r['tanggal'];
            elseif ($periode == 'bulan') $key = $r['bulan'].'-'.$r['tahun'];
            else $key = $r['tahun'];
            $dataMasuk[$key] = (float)$r['pemasukan'];
        }
    }

    if ($resKeluar) {
        while ($r = mysqli_fetch_assoc($resKeluar)) {
            if ($periode == 'hari') $key = $r['tanggal'];
            elseif ($periode == 'bulan') $key = $r['bulan'].'-'.$r['tahun'];
            else $key = $r['tahun'];
            $dataKeluar[$key] = (float)$r['pengeluaran'];
        }
    }

    $allKeys = array_unique(array_merge(array_keys($dataMasuk), array_keys($dataKeluar)));
    // sorting keys: if daily (yyyy-mm-dd) sort desc by date; else natural sort
    if ($periode == 'hari') {
        usort($allKeys, function($a,$b){ return strtotime($b) - strtotime($a); });
    } else {
        sort($allKeys);
        $allKeys = array_reverse($allKeys); // show newest first consistent with earlier ORDER BY
    }

    $dataFinal = [];
    foreach ($allKeys as $k) {
        $p = $dataMasuk[$k] ?? 0;
        $q = $dataKeluar[$k] ?? 0;
        $dataFinal[] = [
            'periode' => $k,
            'pemasukan' => $p,
            'pengeluaran' => $q,
            'laba' => $p - $q
        ];
    }

    $totals = [
        'totalPemasukan' => array_sum(array_column($dataFinal,'pemasukan')),
        'totalPengeluaran' => array_sum(array_column($dataFinal,'pengeluaran')),
        'totalLaba' => array_sum(array_column($dataFinal,'laba'))
    ];

    return ['data' => $dataFinal, 'totals' => $totals, 'periode' => $periode];
}
