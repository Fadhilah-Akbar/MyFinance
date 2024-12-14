<?php 
include 'database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $fullname = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $role_id = 2;
    // Validasi sederhana untuk memastikan password dan konfirmasinya sesuai
    if ($password !== $confirmPassword) {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Password dan konfirmasi password tidak sesuai.'
        ];
        header("Location: ../register.php");
        exit();
    }

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Siapkan query SQL untuk insert data
    $sql = "INSERT INTO user (username, password, fullname, role_id) VALUES (?, ?, ?, ?)";

    // Persiapkan statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bind_param("sssi", $username, $hashedPassword, $fullname, $role_id);

        // Eksekusi statement
        if ($stmt->execute()) {
            $_SESSION['message'] = [
                'type' => 'success',
                'text' => 'Registrasi berhasil! Silakan login.'
            ];
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['message'] = [
                'type' => 'danger',
                'text' => 'Terjadi kesalahan saat menyimpan data: ' . $stmt->error
            ];
            header("Location: ../register.php");
            exit();
        }

        // Tutup statement
        $stmt->close();
    } else {
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Terjadi kesalahan pada server: ' . $conn->error
        ];
        header("Location: ../register.php");
        exit();
    }

    // Tutup koneksi
    $conn->close();
} else {
    echo "Akses ditolak.";
}
?>