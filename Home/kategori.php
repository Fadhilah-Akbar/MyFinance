<?php 
$page = "Master Category";
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
          <li class="breadcrumb-item active" aria-current="page">Master Category</li>
        </ol>
      </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4">
      <div class="card-body">
        <h4>Master Category</h4>
        <div class="card">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                  <a class="btn btn-primary" href="add_kategori.php" type="button">
                      <i class="fas fa-plus"></i> Tambah Category
                  </a>
              </div>
              <div>
                  <input type="search" name="search" class="form-control" placeholder="Search...">
              </div>
            </div>

            <table id="kategoriTable" class="table table-striped table-bordered my-3" style="width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:90px;">No</th>
                  <th class="text-center">Category Name</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- Data will be populated here dynamically -->
              </tbody>
            </table>
          </div>
        </div>
        
      </div>
    </div>

<!-- jQuery and DataTables Initialization -->
<script>
  $(document).ready(function() {
    table = $('#kategoriTable').DataTable({
      "ajax": {
        "url": "config/kategori/fetch.php", // Replace with the path to your PHP script
        "dataSrc": ""
      },
      "columns": [
        { 
          "data": null, 
          "className": 'text-center',
          "render": function (data, type, row, meta) {
            return meta.row + 1; // Auto-generate row number
          }
        },
        { 
          "data": "nama_kategori",
          "className": 'text-center'
        },
        { 
          "data": null,
          "className": 'text-center', // Center the action buttons
          "render": function (data, type, row) {
            // Action buttons, customize as needed
            return `<a href="edit_kategori.php?id=${row.id}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="config/kategori/delete.php?id=${row.id}" class="btn btn-sm btn-danger">Delete</a>`;
          }
        }
      ],
      "lengthMenu": [5, 10, 25, 50, 100],
      "dom": 't<"d-flex justify-content-between"<"align-self-start"l><"text-center"i><"align-self-end"p>>',
      responsive: true,
      autoWidth: false,
    });

    $('input[type="search"]').on('keyup change', function() {
      table.search(this.value).draw();
    });
  });
</script>

<?php include 'footer.php'; ?>
