<aside class="w-64 bg-white h-full flex flex-col border-r border-gray-200">

   <nav class="flex-grow p-4 space-y-2 overflow-y-auto no-scrollbar">

      <?php if (auth()->user()->inGroup('Admin')) : ?>
         <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('dashboard') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Dashboard
         </a>
         <a href="<?= site_url('admin/inventaris') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('inventaris') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Kelola Inventaris
         </a>
         <a href="<?= site_url('admin/peminjaman') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('peminjaman') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Kelola Peminjaman
         </a>
         <a href="<?= site_url('admin/pengembalian') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('pengembalian') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Kelola Pengembalian
         </a>
         <a href="<?= site_url('admin/laporan-kerusakan') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('laporan-kerusakan') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Dashboard
         </a>
         <a href="<?= site_url('admin/manajemen-akun') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('manajemen-akun') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Dashboard
         </a>

      <?php elseif (auth()->user()->inGroup('TU')) : ?>
         <a href="<?= site_url('tu/dashboard') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('dashboard') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Dashboard
         </a>
         <a href="<?= site_url('tu/verifikasi-peminjaman') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('verifikasi-peminjaman') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Verifikasi Peminjaman
         </a>
         <a href="<?= site_url('tu/verifikasi-pengembalian') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('verifikasi-pengembalian') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Verifikasi Pengembalian
         </a>
         <a href="<?= site_url('tu/kelola-laporan-kerusakan') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('kelola-laporan-kerusakan') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Kelola Laporan Kerusakan
         </a>

      <?php elseif (auth()->user()->inGroup('Peminjam')) : ?>
         <a href="<?= site_url('peminjam/sarpras') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('sarpras') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Katalog Sarpras
         </a>
         <a href="<?= site_url('peminjam/histori-peminjaman') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('histori-peminjaman') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Histori Peminjaman
         </a>
         <a href="<?= site_url('peminjam/histori-pengembalian') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('histori-pengembalian') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Histori Pengembalian
         </a>
         <a href="<?= site_url('peminjam/laporan-kerusakan') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('laporan-kerusakan') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Pengaduan Kerusakan
         </a>

      <?php elseif (auth()->user()->inGroup('Pimpinan')) : ?>
         <a href="<?= site_url('pimpinan/dashboard') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('dashboard') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Dashboard
         </a>
         <a href="<?= site_url('pimpinan/lihat-laporan') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('lihat-laporan') ?>">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            Lihat Laporan
         </a>
      <?php endif; ?>

   </nav>

   <div class="p-4 border-t border-gray-200 space-y-2">
      <a href="<?= site_url('profile') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('profile') ?>">
         <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
         </svg>
         Profil Saya
      </a>
      <a href="<?= site_url('profile') ?>" class="<?= getLinkClasses('profile') ?>">
         <svg class="w-6 h-6 <?= getIconClasses('profile') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
         </svg>
         <span>Profil Saya</span>
      </a>

      <a href="<?= site_url('logoout') ?>" class="flex items-center px-3 py-2.5 text-sm font-bold uppercase rounded-lg group transition-colors <?= is_active_menu('logout') ?>">
         <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
         </svg>
         Logout
      </a>
   </div>
</aside>