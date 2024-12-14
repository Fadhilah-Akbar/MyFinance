<?php 
$page = "Master Role";
include 'header.php'; 
include 'sidebar.php'; 
?>

<!-- Main Content -->
<div id="content">
  <!-- Navbar -->
  <nav class="navbar navbar-light bg-light px-3">
    <span class="hamburger-btn" onclick="toggleSidebar()">&#9776;</span>
    <span class="ms-auto">Nama Akun: <strong><?= $_SESSION['fullname']; ?></strong></span>
  </nav>

  <!-- Dashboard Content -->
  <div class="container mt-4">
    <!-- Card for Welcome Section -->
    <div class="card shadow">
      <div class="card-body pb-0">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="role.php">Master Role</a></li>
          <li class="breadcrumb-item active" aria-current="page">Tambah Role</li>
        </ol>
      </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4 bg-light">
      <div class="card-body">
        <h4>Tambah Role</h4>
        <div class="card">
          <div class="card-body p-3">
            <form action="config/role/add.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Role Name</label>
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
        
      </div>
    </div>

<?php include 'footer.php'; ?>
