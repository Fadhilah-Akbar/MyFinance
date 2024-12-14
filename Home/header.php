<?php
session_start();

// Cek apakah session user_id ada
if (!isset($_SESSION['user_id'])) {
    // Redirect ke halaman login jika user belum login
    header("Location: ../index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cash-Flow | <?= $page; ?></title>
  <link rel="icon" type="image/png" href="asset/img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="asset/img/favicon/favicon.svg" />
  <link rel="shortcut icon" href="asset/img/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="asset/img/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="MyWebSite" />
  <link rel="manifest" href="asset/img/favicon/site.webmanifest" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css" />

  <!-- chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- jQuery Library -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>

  <style>
    .alert-position {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050; /* Pastikan berada di atas elemen lain */
    }
    #sidebar {
      width: 250px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: -250px;
      background-color: #343a40;
      transition: all 0.3s;
      color: #fff;
      z-index: 1000;
    }
    #sidebar.active {
      left: 0;
    }
    #sidebar .nav-link {
      color: #fff;
    }
    #sidebar .nav-link:hover {
      background-color: #495057;
    }
    #content {
      margin-left: 0;
      transition: margin-left 0.3s;
    }
    #content.active {
      margin-left: 250px;
    }
    .hamburger-btn {
      font-size: 1.5rem;
      cursor: pointer;
    }
  </style>
</head>
<body>

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
    }, 3000);
  });
</script>
