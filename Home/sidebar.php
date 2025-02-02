
<!-- Sidebar -->
<div id="sidebar">
  <h4 class="text-center mt-3">Dashboard</h4>
  <div class="p-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="income.php">Income</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="spend.php">Spend</a>
      </li>
    <?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2):?>
      <li class="nav-item">
        <a class="nav-link" href="role.php">Master Role</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kategori.php">Master Category</a>
      </li>
      <?php endif; ?>
      <?php if($_SESSION['role_id'] == 1):?>
      <li class="nav-item">
        <a class="nav-link" href="user.php">Account Management</a>
      </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="config/logout.php">Logout</a>
      </li>
    </ul>
  </div>
</div>