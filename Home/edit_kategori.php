<?php 
$page = "Master Role";
include 'header.php'; 
include 'sidebar.php'; 

// Ambil id dari URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    $_SESSION['message'] = [
        'type' => 'error',
        'text' => 'ID tidak valid atau tidak ditemukan.'
    ];
    header("Location: kategori.php");
    exit;
}
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
          <li class="breadcrumb-item"><a href="role.php">Master Category</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
        </ol>
      </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4 bg-light">
      <div class="card-body">
        <h4>Edit Category</h4>
        <div class="card">
          <div class="card-body p-3">
            <form action="config/kategori/edit.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" id="category_name" name="name" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Category Name</label>
              </div>
              <input type="hidden" id="category_id" name="id" class="form-control" id="floatingInput" placeholder="name@example.com">
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
        
      </div>
    </div>

<?php include 'footer.php'; ?>
<script>
$(document).ready(function() {
    var category_id = <?= json_encode($id); ?>;

    // Fetch role data by ID using AJAX
    $.ajax({
        url: 'config/kategori/fetch_by_id.php',
        type: 'GET',
        data: { id: category_id },
        dataType: 'json',
        success: function(response) {
            if (response) {
                $('#category_id').val(response.id);
                $('#category_name').val(response.nama_kategori);
            } else {
                alert('Data kategori tidak ditemukan.');
            }
        },
        error: function() {
            alert('Gagal mengambil data kategori.');
        }
    });
});
</script>