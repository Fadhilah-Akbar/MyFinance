<?php 
$page = "Master Role";
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
          <li class="breadcrumb-item active" aria-current="page">Master Role</li>
        </ol>
      </div>
    </div>

    <!-- Card for Income Chart -->
    <div class="card shadow my-4">
      <div class="card-body">
        <h4>Master Role</h4>
        <div class="card">
          <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <div>
                  <a class="btn btn-primary" href="add_role.php" type="button">
                      <i class="fas fa-plus"></i> Tambah Role
                  </a>
              </div>
              <div>
                  <input type="search" name="search" class="form-control" placeholder="Search...">
              </div>
            </div>

            <table id="roleTable" class="table table-striped table-bordered my-3" style="width:100%">
              <thead>
                <tr>
                  <th class="text-center" style="width:90px;">No</th>
                  <th class="text-center">Role Name</th>
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
    table = $('#roleTable').DataTable({
      "ajax": {
        "url": "config/role/fetch.php", // Replace with the path to your PHP script
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
          "data": "name",
          "className": 'text-center' // Center the role name column
        },
        { 
          "data": null,
          "className": 'text-center', // Center the action buttons
          "render": function (data, type, row) {
            // Action buttons, customize as needed
            return `<a href="edit_role.php?id=${row.id}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="config/role/delete.php?id=${row.id}" class="btn btn-sm btn-danger">Delete</a>`;
          }
        }
      ],
      "lengthMenu": [2, 5, 10, 25, 50, 100],
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
