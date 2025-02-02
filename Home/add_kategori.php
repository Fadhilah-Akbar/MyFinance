<?php 
$page = "Master Category";
include 'header.php'; 
include 'sidebar.php'; 
?>

<!-- Main Content -->
<div id="content">
  <?php include 'topbar.php' ?>

  <!-- Dashboard Content -->
  <div class="container mt-4">
    <!-- Card for Welcome Section -->
    <div class="card shadow">
      <div class="card-body pb-0">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item"><a href="Kategori.php">Master Category</a></li>
          <li class="breadcrumb-item active" aria-current="page">Add Category</li>
        </ol>
      </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4 bg-light">
      <div class="card-body">
        <h4>Add Category</h4>
        <div class="card">
          <div class="card-body p-3">
            <form action="config/kategori/add.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Category Name</label>
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
        
      </div>
    </div>

<?php include 'footer.php'; ?>
