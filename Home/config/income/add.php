<?php
// Mulai sesi
session_start();

// Include the database connection
include '../database.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $judul = isset($_POST['name']) ? trim($_POST['name']) : '';
    $jumlah = isset($_POST['nominal']) ? trim($_POST['nominal']) : '';
    $kategori = isset($_POST['kategori_id']) ? trim($_POST['kategori_id']) : '';
    $user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : '';
    $jenis = isset($_POST['jenis']) ? trim($_POST['jenis']) : '';
    $date = isset($_POST['date']) ? $_POST['date'] : date("Y-m-d");
    // Validasi data
    if (!empty($judul) && !empty($jumlah) && !empty($kategori) && !empty($user_id) && !empty($jenis) && !empty($date)) {
        // Persiapkan pernyataan SQL untuk menambahkan data
        $stmt = $conn->prepare("INSERT INTO cash_flow (judul, nominal, kategori_id, user_id, jenis, date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiss", $judul, $jumlah, $kategori, $user_id, $jenis, $date);

        // Eksekusi statement
        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Data pemasukan berhasil ditambahkan!'
            ];
        } else {
            $_SESSION['message'] = [
                'type' => 'error',
                'text' => 'Terjadi kesalahan: ' . $stmt->error
            ];
        }

        // Tutup statement
        $stmt->close();
    } else {
        $_SESSION['message'] = [
            'type' => 'error',
            'text' => 'Semua field harus diisi.'
        ];
    }
}

$conn->close();
header("Location: ../../income.php");
exit();
?>
