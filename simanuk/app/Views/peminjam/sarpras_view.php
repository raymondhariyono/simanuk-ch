<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen">
   <!-- AREA KONTEN -->
   <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
      <!-- MAIN CONTENT -->
      <main class="flex-1 overflow-y-auto p-6 md:p-8 ">

         <!-- JUDUL & DESKRIPSI -->
         <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Katalog Fakultas Teknik</h1>
            <p class="text-gray-600 mt-1">
               Cari dan pinjam sarana & prasarana yang tersedia di Fakultas Teknik.
            </p>
         </div>

         <?php if (isset($breadcrumbs)) : ?>
            <?= render_breadcrumb($breadcrumbs); ?>
         <?php endif; ?>

         <?= $this->include('components/filter_bar', []) ?>
         <div class="mb-8 gap-x-2 gap-y-2 items-end">
            <div class="md:col-span-5 flex flex-col items-stretch md:items-end space-y-3">
               <a href="<?= site_url('peminjam/peminjaman/new') ?>" class="bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 w-full md:w-auto text-center whitespace-nowrap flex items-center justify-center">
                  + Ajukan Peminjaman
               </a>
            </div>

         </div>
         <!-- GRID KATALOG (DESKTOP) -->
         <div class="hidden sm:grid sm:grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($sarana as $b): ?>
               <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                  <div class="relative h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                     <?php if (!empty($b['url_foto'])) : ?>
                        <img src="<?= base_url($b['url_foto']) ?>"
                           alt="<?= esc($b['nama_sarana']) ?>"
                           class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                     <?php else : ?>
                        <div class="text-gray-400 flex flex-col items-center">
                           <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                           </svg>
                           <span class="text-xs">No Image</span>
                        </div>
                     <?php endif; ?>

                     <?php if ($b['status_ketersediaan'] == 'Tersedia'): ?>
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">Tersedia</span>
                     <?php elseif ($b['status_ketersediaan'] == 'Dipinjam'): ?>
                        <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Dipinjam</span>
                     <?php elseif ($b['status_ketersediaan'] == 'Perawatan'): ?>
                        <span class="absolute top-2 right-2 bg-yellow-600 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Perawatan</span>
                     <?php else : ?>
                        <span class="absolute top-2 right-2 bg-red-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Tidak Tersedia</span>
                     <?php endif; ?>
                  </div>

                  <div class="p-4 flex-1 flex flex-col">
                     <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($b['nama_sarana']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($b['nama_kategori']) ?></p>
                     </div>
                     <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($b['kode_sarana'])) ?>" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>

            <!-- KATALOG PRASARANA -->
            <?php foreach ($prasarana as $p): ?>
               <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                  <div class="relative h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                     <?php if (!empty($p['url_foto'])) : ?>
                        <img src="<?= base_url($p['url_foto']) ?>"
                           alt="<?= esc($p['nama_prasarana']) ?>"
                           class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                     <?php else : ?>
                        <div class="text-gray-400 flex flex-col items-center">
                           <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                           </svg>
                           <span class="text-xs">No Image</span>
                        </div>
                     <?php endif; ?>

                     <?php if ($p['status_ketersediaan'] == 'Tersedia'): ?>
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">Tersedia</span>
                     <?php elseif ($p['status_ketersediaan'] == 'Dipinjam'): ?>
                        <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Dipinjam</span>
                     <?php elseif ($p['status_ketersediaan'] == 'Perawatan'): ?>
                        <span class="absolute top-2 right-2 bg-yellow-600 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Perawatan</span>
                     <?php else : ?>
                        <span class="absolute top-2 right-2 bg-red-400 text-black text-xs font-bold px-2 py-1 rounded-full shadow">Tidak Tersedia</span>
                     <?php endif; ?>
                  </div>

                  <div class="p-4 flex-1 flex flex-col">
                     <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($p['nama_prasarana']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($p['nama_kategori']) ?></p>
                     </div>
                     <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($p['kode_prasarana'])) ?>" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>

         <!-- SLIDER (MOBILE) -->
         <div class="sm:hidden flex overflow-x-auto space-x-4 p-1 snap-x snap-mandatory">
            <?php foreach ($sarana as $b): ?>
               <div class="min-w-[16rem] snap-center shrink-0">
                  <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
                     <div class="relative">
                        <img src="" alt="<?= htmlspecialchars($b['nama_sarana']) ?>" class="w-full h-48 object-cover">
                        <?php if ($b['status_ketersediaan'] == 'Tersedia'): ?>
                           <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Tersedia</span>
                        <?php else: ?>
                           <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Dipinjam</span>
                        <?php endif; ?>
                     </div>
                     <div class="p-4 flex-1 flex flex-col">
                        <div class="flex-1">
                           <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($b['nama_sarana']) ?></h3>
                           <p class="text-sm text-gray-600"><?= htmlspecialchars($b['nama_kategori']) ?></p>
                        </div>
                        <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($b['kode_sarana'])) ?>" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                           Lihat Detail
                        </a>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>

            <!-- KATALOG PRASARANA -->
            <?php foreach ($prasarana as $p): ?>
               <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                  <div class="relative">
                     <img src="" alt="<?= htmlspecialchars($p['nama_prasarana']) ?>" class="w-full h-48 object-cover">
                     <?php if ($p['status_ketersediaan'] == 'Tersedia'): ?>
                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Tersedia</span>
                     <?php else: ?>
                        <span class="absolute top-2 right-2 bg-yellow-400 text-black text-xs font-bold px-2 py-1 rounded-full">Dipinjam</span>
                     <?php endif; ?>
                  </div>
                  <div class="p-4 flex-1 flex flex-col">
                     <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($p['nama_prasarana']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($p['nama_kategori']) ?></p>
                     </div>
                     <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($p['kode_prasarana'])) ?>" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>

      </main>
      <div class="flex justify-end">
         <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <?= $pager_sarana->links('sarana', 'pager_sarpras') ?>
         </div>
      </div>
   </div>
</div>

<script>
   const sidebar = document.getElementById('sidebar');
   const toggleBtn = document.getElementById('toggleSidebar');
   const closeBtn = document.getElementById('closeSidebar');

   if (sidebar && toggleBtn) {
      toggleBtn.addEventListener('click', () => sidebar.classList.remove('-translate-x-full'));
   }
   if (sidebar && closeBtn) {
      closeBtn.addEventListener('click', () => sidebar.classList.add('-translate-x-full'));
   }
</script>

<?= $this->endSection(); ?>