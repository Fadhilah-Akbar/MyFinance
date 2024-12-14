<?php 
include 'database.php';

session_start();

    // Ambil data dari form
    $fullname = trim($_POST['name']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);

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
    $sql = "INSERT INTO user (username, password, fullname) VALUES (?, ?, ?)";

    // Persiapkan statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameter
        $stmt->bind_param("sss", $username, $hashedPassword, $fullname);

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
?>