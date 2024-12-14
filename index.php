<?php 
include 'header.php'; 
?>
  
<div class="container center-container">
  <div class="form-container">
    <h3 class="text-center">Login</h3>
    <form action="config/login.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-primary w-100">Login</button>
      <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar</a></p>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>