<?php include 'header.php'; ?>
<div class="container center-container">
  <div class="form-container">
    <h3 class="text-center">Register</h3>
    <form id="registerForm" action="config/register.php" method="POST">
      <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control" id="name" name="name" required minlength="3" 
               oninvalid="this.setCustomValidity('Nama lengkap minimal 3 huruf.')" 
               oninput="this.setCustomValidity('')">
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="text" class="form-control" id="email" name="email" required 
                pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" 
                oninvalid="this.setCustomValidity('email tidak valid.')" 
                oninput="this.setCustomValidity('')">
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required 
               pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%!&*])[A-Za-z\d@#$%!&*]{8,}$" 
               oninvalid="this.setCustomValidity('Password harus minimal 8 karakter, minimal 1 simbol, 1 huruf besar, 1 huruf kecil, dan 1 angka.')" 
               oninput="this.setCustomValidity('')">
      </div>
      <div class="mb-3">
        <label for="confirm-password" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="confirm-password" name="confirm-password" required
               oninvalid="this.setCustomValidity('Konfirmasi password harus sama dengan password.')" 
               oninput="this.setCustomValidity('')">
      </div>
      <button type="submit" class="btn btn-primary w-100">Daftar</button>
      <p class="text-center mt-3">Sudah punya akun? <a href="index.php">Login</a></p>
    </form>
  </div>
</div>
<?php include 'footer.php'; ?>

<script>
document.getElementById('registerForm').addEventListener('submit', function(event) {
  const form = event.target;
  const password = form['password'].value;
  const confirmPassword = form['confirm-password'].value;

  // Validasi konfirmasi password agar sesuai dengan password
  if (password !== confirmPassword) {
    form['confirm-password'].setCustomValidity('Konfirmasi password harus sama dengan password.');
    form['confirm-password'].reportValidity();
    event.preventDefault();
  } else {
    form['confirm-password'].setCustomValidity('');
  }
});
</script>
