<?php
session_start();

// Cek apakah session user_id ada
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika user belum login
    header("Location: ../index.php");
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$timezone = new DateTimeZone('Asia/Jakarta');
$start = new DateTime($data['start']);
$start = $start->setTimezone($timezone)->format('d M Y');
$end = new DateTime($data['end']);
$end = $end->setTimezone($timezone)->format('d M Y');

$spend = '0';
$income = '0';

if (!$data) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    exit();
}

require_once __DIR__ . '../../..//vendor/autoload.php';

use Mpdf\Mpdf;

try {
    // Buat instance mPDF
    $mpdf = new Mpdf([
        'margin_top' => 20,
        'margin_bottom' => 20,
    ]);

    $mpdf->SetHTMLHeader('
        <div style="font-size: 12px;"> Download Date : '
            . date('d F Y') .
        '<br>'
            . $_SESSION['fullname'] . 
        '</div>
    ', 'O');

    $mpdf->SetHTMLFooter('
        <div style="text-align: center; font-size: 12px;">
            Copyright &copy; 2024 - 2025 <span style="font-weight:bold;">Orca</span>
            <div>Cashflow Version 1.0.1 </div>
        </div>
    ');
    // Tambahkan konten ke PDF
    $html = '
                <h1 style="text-align:center;">CashFlow Report</h1>
                <table>
                <tr><td class="normal">Period date </td><td class="normal">:</td><td class="normal">' . htmlspecialchars($start) . ' - ' . htmlspecialchars($end) . '</td></tr>
                </table>
                <table style="margin-top:5px;width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
                <thead>
                    <tr style="background-color: #f2f2f2; text-align: left;">
                        <th style="border: 1px solid #ddd; padding: 8px;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Tanggal</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Keterangan</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Nominal</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Kategori</th>
                    </tr>
                </thead>
                <tbody>';
    
                foreach ($data['data'] as $index => $row) {
                    if($row['type'] =='spend'){
                        $spend += $row['nominal'];
                        $html .= '<tr style="background-color:#FF6666">
                            <td style="border: 1px solid #ddd; padding: 8px;">' . ($index + 1) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['date']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['detail']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['category']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['nominal']) . '</td>
                        </tr>';
                    }else{
                        $income += $row['nominal'];
                        $html .= '<tr style="background-color:#90EE90">
                            <td style="border: 1px solid #ddd; padding: 8px;">' . ($index + 1) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['date']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['detail']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['category']) . '</td>
                            <td style="border: 1px solid #ddd; padding: 8px;">' . htmlspecialchars($row['nominal']) . '</td>
                        </tr>';
                    }
                }

                $total = $income - $spend;
                $totalBackgroundColor = $total > 0 ? '#90EE90' : '#FF6666';

                $html .= '<tr style="background-color:#FF6666">
                            <td style="border: 1px solid #ddd; text-align: right; padding:8px;" colspan="4">Spending</td>
                            <td style="border: 1px solid #ddd; padding: 8px;"> Rp.' . $spend . '</td>
                        </tr>
                        <tr style="background-color:#90EE90">
                            <td style="border: 1px solid #ddd; text-align: right; padding:8px;" colspan="4">Income</td>
                            <td style="border: 1px solid #ddd; padding: 8px;"> Rp.' . $income . '</td>
                        </tr>
                        <tr style="background-color:' . $totalBackgroundColor . '">
                            <td style="border: 1px solid #ddd; text-align: right; padding:8px;" colspan="4">Total</td>
                            <td style="border: 1px solid #ddd; padding: 8px;"> Rp.' . $total . '</td>
                        </tr>
                        </tbody></table>';
    $mpdf->WriteHTML($html);
    $mpdf->Output('CashFlow-Report-' . $_SESSION['fullname'] . '-.pdf', 'I');  // 'I' untuk langsung tampil di browser
} catch (\Mpdf\MpdfException $e) {
    echo $e->getMessage();
}
