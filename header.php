<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home/index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cash-Flow</title>
  <link rel="icon" type="image/png" href="home/asset/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="home/asset/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="home/asset/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="home/asset/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="MyWebSite" />
  <link rel="manifest" href="home/asset/img/favicon/site.webmanifest" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* CSS untuk menempatkan form di tengah layar */
    .center-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .form-container {
      width: 100%;
      max-width: 400px;
      padding: 20px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      background-color: #ffffff;
    }
    .alert-position {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050; /* Pastikan berada di atas elemen lain */
    }
  </style>
</head>
<body class="bg-secondary">

<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-<?php echo $_SESSION['message']['type']; ?> alert-dismissible fade show alert-position w-25 mt-3" role="alert">
    <?php echo $_SESSION['message']['text']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

<script>
  // Tunggu hingga halaman selesai dimuat
  document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.querySelector('.alert');

    // Tutup otomatis setelah 3 detik
    setTimeout(() => {
      if (alertBox) {
        // Tambahkan kelas "fade" sebelum menghilangkan elemen agar smooth
        alertBox.classList.add('fade');
        alertBox.classList.remove('show'); // Hapus kelas 'show' agar transisi penutupan aktif
      }
    }, 5000);
  });
</script>
