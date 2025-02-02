<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Pastikan PHPMailer terinstall dengan composer

function sendEmail($to, $subject, $fullname, $link) {
    $mail = new PHPMailer(true);
    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti dengan SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = 'developer21web@gmail.com';
        $mail->Password = 'drgelmjfrkcrbpuv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Baca template email
        $message = file_get_contents('card-verify.html');

        // Ganti placeholder dengan data dinamis
        $message = str_replace('{fullname}', $fullname, $message);
        $message = str_replace('{verification_link}', $link, $message);

        // Pengaturan email
        $mail->setFrom('developer21web@gmail.com', 'MyFinance App');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
