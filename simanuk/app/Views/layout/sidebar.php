<!-- Sidebar -->
<aside class="w-64 bg-gray-800 text-white flex-shrink-0 no-scrollbar overflow-y-auto">
   <div class="p-4 border-b border-gray-700">
      <h2 class="text-2xl font-bold text-white">SIMANUK</h2>
      <span class="text-sm text-gray-400">Manajemen Sarpras</span>
   </div>

   <nav class="py-4">
      <!-- dinamis berdasarkan grup (role) -->
      <?php if (auth()->user()->inGroup('Admin')) : ?>
         <!-- === MENU ADMIN === -->
         <a href="<?= site_url('admin/dashboard') ?>" class="block px-4 py-2 hover:bg-gray-700">Dashboard</a>
         <a href="<?= site_url('admin/inventaris') ?>" class="block px-4 py-2 hover:bg-gray-700">Kelola Inventaris</a>
         <a href="<?= site_url('admin/laporan/kerusakan') ?>" class="block px-4 py-2 hover:bg-gray-700">Kelola Laporan Kerusakan</a>
         <a href="<?= site_url('admin/peminjaman/masuk') ?>" class="block px-4 py-2 hover:bg-gray-700">Daftar Peminjaman Aktif</a>
         <a href="<?= site_url('admin/verifikasi/pengembalian') ?>" class="block px-4 py-2 hover:bg-gray-700">Verifikasi Pengembalian</a>
         <a href="<?= site_url('admin/laporan/generate') ?>" class="block px-4 py-2 hover:bg-gray-700">Generate Laporan</a>

      <?php elseif (auth()->user()->inGroup('TU')) : ?>
         <!-- === MENU TATA USAHA === -->
         <a href="<?= site_url('tu/dashboard') ?>" class="block px-4 py-2 hover:bg-gray-700">Dashboard</a>
         <a href="<?= site_url('tu/verifikasi/peminjaman') ?>" class="block px-4 py-2 hover:bg-gray-700">Verifikasi Peminjaman</a>
         <a href="<?= site_url('tu/laporan/kerusakan') ?>" class="block px-4 py-2 hover:bg-gray-700">Tindak Lanjut Kerusakan</a>
         <a href="<?= site_url('tu/kelola/akun') ?>" class="block px-4 py-2 hover:bg-gray-700">Kelola Akun Pengguna</a>
         <a href="<?= site_url('tu/laporan/generate') ?>" class="block px-4 py-2 hover:bg-gray-700">Generate Laporan</a>

      <?php elseif (auth()->user()->inGroup('Peminjam')) : ?>
         <!-- === MENU PEMINJAM === -->
         <a href="<?= site_url('peminjam/dashboard') ?>" class="block px-4 py-2 hover:bg-gray-700">Dashboard</a>
         <a href="<?= site_url('peminjam/katalog') ?>" class="block px-4 py-2 hover:bg-gray-700">Katalog Sarpras</a>
         <a href="<?= site_url('peminjam/ajukan') ?>" class="block px-4 py-2 hover:bg-gray-700">Peminjaman</a>
         <a href="<?= site_url('peminjam/riwayat') ?>" class="block px-4 py-2 hover:bg-gray-700">Pengembalian</a>
         <a href="<?= site_url('peminjam/lapor/kerusakan') ?>" class="block px-4 py-2 hover:bg-gray-700">Buat Laporan Kerusakan</a>

      <?php elseif (auth()->user()->inGroup('Pimpinan')) : ?>
         <!-- === MENU PIMPINAN === -->
         <a href="<?= site_url('pimpinan/dashboard') ?>" class="block px-4 py-2 hover:bg-gray-700">Dashboard</a>
         <a href="<?= site_url('pimpinan/laporan/view') ?>" class="block px-4 py-2 hover:bg-gray-700">Lihat Laporan</a>
      <?php endif; ?>

      <!-- Menu Umum untuk Semua Role -->
      <div class="border-t border-gray-700 mt-4 pt-4">
         <a href="<?= site_url('profile') ?>" class="block px-4 py-2 hover:bg-gray-700">Profil Saya</a>

         <!-- Link Logout (UC02) -->
         <a href="<?= url_to('logout') ?>" class="block px-4 py-2 text-red-400 hover:bg-red-700 hover:text-white">
            Logout
         </a>
      </div>
   </nav>
</aside>