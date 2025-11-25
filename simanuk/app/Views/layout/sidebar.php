<aside class="w-64 bg-white h-full flex flex-col border-r border-gray-200">

   <nav class="flex-grow p-4 space-y-2 overflow-y-auto no-scrollbar">

      <?php if (auth()->user()->inGroup('Admin')) : ?>
         <a href="<?= site_url('admin/dashboard') ?>" class="<?= getLinkClasses('admin/dashboard') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/dashboard') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
         </a>

         <a href="<?= site_url('admin/inventaris') ?>" class="<?= getLinkClasses('admin/inventaris') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/inventaris') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10.5 11.25h3M12 15h.008" />
            </svg>
            <span>Kelola Inventarisasi</span>
         </a>

         <a href="<?= site_url('admin/peminjaman') ?>" class="<?= getLinkClasses('admin/peminjaman') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/peminjaman') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0l9-9 9 9" />
            </svg>
            <span>Kelola Peminjaman</span>
         </a>

         <a href="<?= site_url('admin/pengembalian') ?>" class="<?= getLinkClasses('admin/pengembalian') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/pengembalian') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
            <span>Kelola Pengembalian</span>
         </a>

         <a href="<?= site_url('admin/laporan-kerusakan') ?>" class="<?= getLinkClasses('admin/laporan-kerusakan') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/laporan-kerusakan') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <span>Kelola Laporan Kerusakan</span>
         </a>

         <a href="<?= site_url('admin/manajemen-akun') ?>" class="<?= getLinkClasses('admin/manajemen-akun') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/manajemen-akun') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM8.625 15.125a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0z" />
            </svg>
            <span>Manajemen Akun Pengguna</span>
         </a>
         <a href="<?= site_url('admin/master') ?>" class="<?= getLinkClasses('admin/master') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('admin/master') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Data Master (Kategori & Lokasi)</span>
         </a>

      <?php elseif (auth()->user()->inGroup('TU')) : ?>
         <a href="<?= site_url('tu/dashboard') ?>" class="<?= getLinkClasses('tu/dashboard') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('tu/dashboard') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
         </a>
         <a href="<?= site_url('tu/verifikasi-peminjaman') ?>" class="<?= getLinkClasses('tu/verifikasi-peminjaman') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('tu/verifikasi-peminjaman') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            <span>Verifikasi Peminjaman</span>
         </a>
         <a href="<?= site_url('tu/verifikasi-pengembalian') ?>" class="<?= getLinkClasses('tu/verifikasi-pengembalian') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('tu/verifikasi-pengembalian') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M10.125 2.25h-4.5c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125v-9M10.125 2.25h.375m0 0l3.004 3.004m-3.004-3.004v3.75m0 0h3.75m-3.75 0h.375m0 0l3.004 3.004M16.125 21.75l1.623-2.435a1.125 1.125 0 011.609-.31l1.13 1.13a1.125 1.125 0 001.761-.31l1.623-2.435m-5.132 4.075L16.125 21.75m-2.625-4.125l1.623-2.435a1.125 1.125 0 011.609-.31l1.13 1.13a1.125 1.125 0 001.761-.31l1.623-2.435m-5.132 4.075L13.5 17.625" />
            </svg>
            <span>Verifikasi Pengembalian</span>
         </a>
         <a href="<?= site_url('tu/kelola-laporan-kerusakan') ?>" class="<?= getLinkClasses('tu/kelola-laporan-kerusakan') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('tu/kelola-laporan-kerusakan') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.73-.664 1.182-.827l3.218-.804a4.06 4.06 0 004.06-4.06l-6.25 6.25L11.42 15.17zM6.75 12.87l-3.696 3.696A4.06 4.06 0 011.5 12.25l6.25-6.25a4.06 4.06 0 014.06 4.06l-.804 3.218a5.25 5.25 0 01-.827 1.182l-3.03 2.496z" />
            </svg>
            <span>Kelola Laporan Kerusakan</span>
         </a>

      <?php elseif (auth()->user()->inGroup('Peminjam')) : ?>
         <a href="<?= site_url('peminjam/sarpras') ?>" class="<?= getLinkClasses('peminjam/sarpras') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('peminjam/sarpras') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
            <span>Katalog Sarpras</span>
         </a>
         <a href="<?= site_url('peminjam/histori-peminjaman') ?>" class="<?= getLinkClasses('peminjam/histori-peminjaman') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('peminjam/histori-peminjaman') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
            </svg>
            <span>Histori Peminjaman</span>
         </a>
         <a href="<?= site_url('peminjam/histori-pengembalian') ?>" class="<?= getLinkClasses('peminjam/histori-pengembalian') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('peminjam/histori-pengembalian') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            <span>Histori Pengembalian</span>
         </a>
         <a href="<?= site_url('peminjam/laporan-kerusakan') ?>" class="<?= getLinkClasses('peminjam/laporan-kerusakan') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('peminjam/laporan-kerusakan') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
            </svg>
            <span>Pengaduan Kerusakan</span>
         </a>

      <?php elseif (auth()->user()->inGroup('Pimpinan')) : ?>
         <a href="<?= site_url('pimpinan/dashboard') ?>" class="<?= getLinkClasses('pimpinan/dashboard') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('pimpinan/dashboard') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span>Dashboard</span>
         </a>
         <a href="<?= site_url('pimpinan/lihat-laporan') ?>" class="<?= getLinkClasses('pimpinan/lihat-laporan') ?>">
            <svg class="w-6 h-6 <?= getIconClasses('pimpinan/lihat-laporan') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
               <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
            </svg>
            <span>Lihat Laporan</span>
         </a>
      <?php endif; ?>

   </nav>

   <div class="p-4 border-t border-gray-200 space-y-2">

      <a href="<?= site_url('profile') ?>" class="<?= getLinkClasses('profile') ?>">
         <svg class="w-6 h-6 <?= getIconClasses('profile') ?>" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
         </svg>
         <span>Profil Saya</span>
      </a>

      <a href="<?= url_to('logout') ?>"
         class="flex items-center space-x-3 p-3 rounded-lg font-medium text-red-600 bg-red-50 hover:bg-red-100 transition-colors duration-200">
         <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
         </svg>
         <span>Logout</span>
      </a>
   </div>
</aside>