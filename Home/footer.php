<footer>
  <div class="container">
    <p class="text-center">&copy; 2024 Orca Dev. All rights reserved.</p>
  </div>
</footer>

<style>
  footer {
    background-color: #f8f9fa; /* Warna latar belakang footer */
    padding: 10px 0; /* Padding untuk footer */
    position: relative; /* Mengatur posisi relatif */
    bottom: 0; /* Memastikan footer selalu di bawah */
    width: 100%; /* Lebar penuh */
  }
</style>

<script>
  // Script untuk menampilkan dan menyembunyikan sidebar
  function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("active");
    document.getElementById("content").classList.toggle("active");
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>