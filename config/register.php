<?php 
include 'database.php';
include 'mailer.php'; 

session_start();

function setMessageAndRedirect($type, $text, $location) {
    $_SESSION['message'] = ['type' => $type, 'text' => $text];
    header("Location: $location");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $fullname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm-password']);
    $role_id = 2;
    $verified = 0;

    if ($password !== $confirmPassword) {
        setMessageAndRedirect('danger', 'Password dan konfirmasi password tidak sesuai.', '../register.php');
    }

    try {
        $stmtCheck = $conn->prepare("SELECT id FROM user WHERE email = ?");
        $stmtCheck->bind_param("s", $email);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            $stmtCheck->close();
            setMessageAndRedirect('warning', 'Email sudah terdaftar, silakan gunakan email lain.', '../register.php');
        }
        $stmtCheck->close();

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $token = bin2hex(random_bytes(32));
        $token_expiry = date("Y-m-d H:i:s", strtotime("+1 day"));

        $stmtToken = $conn->prepare("INSERT INTO token (email, token, expired) VALUES (?, ?, ?)");
        $stmtToken->bind_param("sss", $email, $token, $token_expiry);

        if (!$stmtToken->execute()) {
            $stmtToken->close();
            throw new Exception('Terjadi kesalahan saat menyimpan token verifikasi.');
        }
        $stmtToken->close();

        $stmtUser = $conn->prepare("INSERT INTO user (email, password, fullname, role_id, verified) VALUES (?, ?, ?, ?, ?)");
        $stmtUser->bind_param("sssii", $email, $hashedPassword, $fullname, $role_id, $verified);

        if ($stmtUser->execute()) {
            $stmtUser->close();

            $verification_link = "http://MyFinance-orca.ct.ws/config/verify.php?token=" . $token;
            $subject = "Verifikasi Email Registrasi";

            if (sendEmail($email, $subject, $fullname, $verification_link)) {
                setMessageAndRedirect('success', 'Registrasi berhasil! Periksa email Anda untuk verifikasi.', '../index.php');
            } else {
                setMessageAndRedirect('warning', 'Registrasi berhasil, tetapi gagal mengirim email verifikasi.', '../index.php');
            }
        } else {
            $stmtUser->close();
            throw new Exception('Terjadi kesalahan saat menyimpan data: ' . $stmtUser->error);
        }
    } catch (Exception $e) {
        setMessageAndRedirect('danger', $e->getMessage(), '../register.php');
    } finally {
        $conn->close();
    }
} else {
    setMessageAndRedirect('danger', 'Terjadi kesalahan pada aplikasi', '../index.php');
}
?>