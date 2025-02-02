<?php 
$page = "Pendapatan";
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
          <li class="breadcrumb-item"><a href="income.php">Income</a></li>
          <li class="breadcrumb-item active" aria-current="page">Added</li>
        </ol>
      </div>
    </div>

    <div class="card shadow my-4 bg-light">
      <div class="card-body">
        <h4>Add Income History</h4>
        <div class="card">
          <div class="card-body p-3">
            <form action="config/income/add.php" method="POST">
              <div class="form-floating mb-3">
                <input type="text" name="name" class="form-control" id="floatingInput">
                <label for="floatingInput">Detail Information</label>
              </div>
              <div class="form-floating mb-3">
                <input type="number" name="nominal" class="form-control" id="floatingInput">
                <label for="floatingInput">Nominal</label>
              </div>
              <div class="form-floating mb-3">
                <input type="date" name="date" class="form-control" id="floatingDate">
                <label for="floatingDate">Date</label>
              </div>
              <div class="form-floating mb-3">
                <select name="kategori_id" class="form-select" id="kategoriSelect" aria-label="Floating label select example">
                  <!-- Option items will be dynamically added here -->
                </select>
                <label for="kategoriSelect">Category</label>
              </div>
              <input type="hidden" name="user_id" value="<?= $_SESSION['user_id']; ?>">
              <input type="hidden" name="jenis" value="income">
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script>
    $(document).ready(function() {
        $.ajax({
            url: 'config/kategori/fetch.php', // Path ke fetch.php
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Periksa apakah data yang diterima ada
                if (data && data.length > 0) {
                    var $select = $('#kategoriSelect');
                    $select.append('<option selected disabled>Select Category</option>'); // Opsi placeholder

                    // Tambahkan setiap kategori sebagai opsi
                    $.each(data, function(index, category) {
                        $select.append('<option value="' + category.id + '">' + category.nama_kategori + '</option>');
                    });
                } else {
                    console.log("Data kategori kosong atau tidak ditemukan.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Terjadi kesalahan: " + error);
            }
        });
    });

  document.getElementById('floatingDate').valueAsDate = new Date();
</script>
<?php include 'footer.php'; ?>
