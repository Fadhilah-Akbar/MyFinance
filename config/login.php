<?php 
include 'database.php';

session_start();

// Ambil data dari form
$email = $_POST['email'];
$password = $_POST['password'];

// Query untuk mengambil user berdasarkan email
$sql = "SELECT * FROM user WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Jika email ditemukan
    $user = $result->fetch_assoc();

    if ($user['verified'] == 0) {
        $_SESSION['message'] = [
            'type' => 'warning',
            'text' => 'Akun belum diverifikasi! Silakan cek email Anda untuk verifikasi.'
        ];
        header("Location: ../index.php");
        exit();
    }

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Jika password cocok
        
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['role_id'] = $user['role_id'];
        $_SESSION['message'] = [
            'type' => 'success',
            'text' => 'Login berhasil! Selamat datang, ' . $user['fullname']
        ];

        // Redirect ke home
        header("Location: ../home/index.php");
        exit();
    } else {
        // Password salah
        $_SESSION['message'] = [
            'type' => 'danger',
            'text' => 'Email atau Password salah! '
        ];
        header("Location: ../index.php");
        exit();
    }
} else {
    // email tidak ditemukan
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Email atau Password salah! '
    ];
    header("Location: ../index.php");
    exit();
}

$stmt->close();
$conn->close();
?>
