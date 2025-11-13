<!-- sidebar -->
<!-- saya mempunyai header lain yang berdiri sendiri, saya ingin header yang saya miliki tersebut berada di paling depan, maksudnya bagian sidebar saya nantinya akan ditimpa oleh header saya, dan sidebarnya akan otomatis terturun karena ditimpa atau mengikuti headernya -->
<aside>
   <div class="">
      <!-- menu dinamis berdasarkan grup (role) -->
      <!-- gunakan if (auth()->user()->inGroup('Admin') dan tambahkan if lainnya sesuai kebutuhan -->
      <!-- admin -->

      <!-- buat semua tag 'a'nya menjadi seperti di bawah ini -->
      <!-- 'ikon tiap tag 'a' judul halaman dengan warna grey -->

      <!-- ketika halaman dihover, buat warnanya menjadi blue-700 -->
      <!-- ketika halaman diklik dan diakses, buat warna teksnya menjadi biru tua, lalu warna background pada halaman yang diakses full berwarna biru muda -->

      <!-- buat bagian kanan sidebarnya ada shadownya dan kelihatan bayangan berwarna grey yang lebih tua sedikit -->

      <a href="">Dashboard</a>
      <a href="">Kelola Inventaris</a>
      <a href="">Kelola Peminjaman</a>
      <a href="">Kelola Pengembalian</a>
      <a href="">Kelola Laporan Kerusakan</a>
      <a href="">Manajemen Akun Pengguna</a>
      
      <!-- TU -->
      <a href="">Dashboard</a>
      <a href="">Verifikasi Peminjaman</a>
      <a href="">Verifikasi Pengembalian</a>
      <a href="">Kelola Laporan Kerusakan</a>
      <a href="">Generate Laporan</a>

      <!-- note: Untuk Admin dan TU, pada bagian kelola dan verifikasi itu mirip2 saja isinya nanti  -->

      <!-- Peminjam -->
      <a href="">Dashboard</a>
      <a href="">Katalog Barang & SarPras</a>
      <a href="">Histori Peminjaman</a>
      <a href="">Histori Pengembalian</a>
      <a href="">Lapor & Laporan Kerusakan</a>

      <!-- Pimpinan -->
      <a href="">Dashboard</a>
      <a href="">Lihat Laporan</a>
      <a href="">Generate Laporan</a>

      <!-- menu umum untuk semua role -->
      <a href="">Profil Saya</a>
      <a href="">Logout</a>
   </div>
</aside>