<?php
include 'database.php';

session_start();

function setMessageAndRedirect($type, $text, $location) {
    $_SESSION['message'] = ['type' => $type, 'text' => $text];
    header("Location: $location");
    exit();
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Cek apakah token ada di database
    $sql = "SELECT email, expired FROM token WHERE token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($email, $expired);
            $stmt->fetch();

            if (new DateTime() > new DateTime($expired)) {
                setMessageAndRedirect('danger', 'Token sudah expired', '../index.php');
            } else {
                $conn->autocommit(FALSE); 

                $updateSql = "UPDATE user SET verified = 1 WHERE email = ?";
                if ($updateStmt = $conn->prepare($updateSql)) {
                    $updateStmt->bind_param("s", $email);
                    $updateStmt->execute();
                    
                    $deleteSql = "DELETE FROM token WHERE email = ?";
                    if ($deleteStmt = $conn->prepare($deleteSql)) {
                        $deleteStmt->bind_param("s", $email);
                        $deleteStmt->execute();
                        $conn->commit(); // Selesaikan transaksi
                        setMessageAndRedirect('success', 'Email Berhasil diverifikasi! Silahkan Login!', '../index.php');
                    } else {
                        $conn->rollback(); // Batalkan transaksi jika gagal
                        setMessageAndRedirect('danger', 'Database gagal terkoneksi!', '../index.php');
                    }
                } else {
                    $conn->rollback(); // Batalkan transaksi jika gagal
                    setMessageAndRedirect('danger', 'Terjadi kesalahan ketika memverifikasi email', '../index.php');
                }
            }
        } else {
            setMessageAndRedirect('danger', 'Token tidak tersedia', '../index.php');
        }
        $stmt->close();
    }
} else {
    setMessageAndRedirect('danger', 'Gagal melakukan verifikasi', '../index.php');
}

$conn->close();