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

    <div class="card shadow my-4 bg-light">
      <div class="card-body">
        <h4>Edit Info Pengeluaran</h4>
        <div class="card">
          <div class="card-body p-3">
            <form action="config/spend/edit.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" id="name" name="name" class="form-control" id="floatingInput">
                <label for="floatingInput">Nama Pemasukan</label>
              </div>
              <div class="form-floating mb-3">
                <input type="number" id="nominal" name="nominal" class="form-control" id="floatingInput">
                <label for="floatingInput">Jumlah</label>
              </div>
              <div class="form-floating mb-3">
                <input type="date" id="date" name="date" class="form-control" id="floatingInput">
                <label for="floatingInput">Tanggal</label>
              </div>
              <div class="form-floating mb-3">
                <select name="kategori_id" class="form-select" id="kategoriSelect" aria-label="Floating label select example">
                </select>
                <label for="kategoriSelect">Kategori</label>
              </div>
              <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
              <input type="hidden" name="jenis" value="income">
              <input type="hidden" name="id" id="income_id">
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php include 'footer.php'; ?>
<script>
$(document).ready(function() {
    var data_id = <?= json_encode($id); ?>;

    // Fetch role data by ID using AJAX
    $.ajax({
        url: 'config/spend/fetch_by_id.php',
        type: 'GET',
        data: { id: data_id },
        dataType: 'json',
        success: function(response) {
            if (response) {
                $('#income_id').val(response.id);
                $('#name').val(response.judul);
                $('#nominal').val(response.nominal);
                $('#date').val(response.date);

                loadCategories(response.kategori_id);
            } else {
                alert('Data Pendapatan tidak ditemukan.');
            }
        },
        error: function() {
            alert('Gagal mengambil data Pendapatan.');
        }
    });

    function loadCategories(selectedCategoryId) {
        $.ajax({
            url: 'config/kategori/fetch.php', // Endpoint untuk mengambil semua kategori
            type: 'GET',
            dataType: 'json',
            success: function(categories) {
                if (categories && categories.length > 0) {
                    let options = '<option value="" disabled>Pilih Kategori</option>';
                    categories.forEach(function(category) {
                        options += `<option value="${category.id}" ${category.id == selectedCategoryId ? 'selected' : ''}>${category.nama_kategori}</option>`;
                    });
                    $('#kategoriSelect').html(options);
                } else {
                    alert('Kategori tidak ditemukan.');
                }
            },
            error: function() {
                alert('Gagal mengambil data kategori.');
            }
        });
    }
});
</script>