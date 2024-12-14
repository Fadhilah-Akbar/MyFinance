<?php 
include 'database.php';

session_start();

// Ambil data dari form
$username = $_POST['username'];
$password = $_POST['password'];

// Query untuk mengambil user berdasarkan username
$sql = "SELECT * FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Jika username ditemukan
    $user = $result->fetch_assoc();

    // Verifikasi password
    if (password_verify($password, $user['password'])) {
        // Jika password cocok
        
        $_SESSION['username'] = $user['username'];
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
            'text' => 'Username atau Password salah! '
        ];
        header("Location: ../index.php");
        exit();
    }
} else {
    // Username tidak ditemukan
    $_SESSION['message'] = [
        'type' => 'danger',
        'text' => 'Username atau Password salah! '
    ];
    header("Location: ../index.php");
    exit();
}

$stmt->close();
$conn->close();
?>
