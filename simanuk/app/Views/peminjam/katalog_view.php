<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php
// --- DATA DUMMY ---
$items = [
   [
      'nama' => 'Proyektor Epson EB-S41',
      'kategori' => 'Elektronik',
      'gambar' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
      'status' => 'Tersedia'
   ],
   [
      'nama' => 'Laptop Dell Latitude',
      'kategori' => 'Elektronik',
      'gambar' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
      'status' => 'Dipinjam'
   ],
   [
      'nama' => 'Kabel HDMI',
      'kategori' => 'ATK',
      'gambar' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
      'status' => 'Tersedia'
   ],
   [
      'nama' => 'Bola Sepak',
      'kategori' => 'Olahraga',
      'gambar' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
      'status' => 'Tersedia'
   ],
   [
      'nama' => 'Bola Sepak',
      'kategori' => 'Olahraga',
      'gambar' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80',
      'status' => 'Tersedia'
   ]
];
?>

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

         <div class="mb-8 grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-4 items-center">

            <div class="md:col-span-7">
               <div class="relative">
                  <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                     <i class="fas fa-search text-gray-400"></i>
                  </div>
                  <input type="text" placeholder="Cari proyektor, kabel HDMI, dll..."
                     class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
               </div>
            </div>

            <div class="md:col-span-5 flex flex-col items-stretch md:items-end space-y-3">
               <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3 w-full md:w-auto">
                  <select class="border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:border-blue-500 w-full md:w-auto">
                     <option>Semua Kategori</option>
                  </select>
                  <select class="border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:border-blue-500 w-full md:w-auto">
                     <option>Lokasi</option>
                  </select>
               </div>

               <a href="#" class="bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 w-full md:w-auto text-center whitespace-nowrap">
                  Ajukan Pengembalian
               </a>
            </div>

         </div>
         <!-- GRID KATALOG (DESKTOP) -->
         <div class="hidden sm:grid sm:grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($items as $item): ?>
               <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col">
                  <div class="relative">
                     <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="w-full h-48 object-cover">
                     <?php if ($item['status'] == 'Tersedia'): ?>
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Tersedia</span>
                     <?php else: ?>
                        <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Dipinjam</span>
                     <?php endif; ?>
                  </div>
                  <div class="p-4 flex-1 flex flex-col">
                     <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($item['nama']) ?></h3>
                        <p class="text-sm text-gray-600"><?= htmlspecialchars($item['kategori']) ?></p>
                     </div>
                     <a href="#" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Lihat Detail
                     </a>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>

         <!-- SLIDER (MOBILE) -->
         <div class="sm:hidden flex overflow-x-auto space-x-4 p-1 snap-x snap-mandatory">
            <?php foreach ($items as $item): ?>
               <div class="min-w-[16rem] snap-center shrink-0">
                  <div class="bg-white rounded-lg shadow-lg overflow-hidden flex flex-col h-full">
                     <div class="relative">
                        <img src="<?= htmlspecialchars($item['gambar']) ?>" alt="<?= htmlspecialchars($item['nama']) ?>" class="w-full h-48 object-cover">
                        <?php if ($item['status'] == 'Tersedia'): ?>
                           <span class="absolute top-2 left-2 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">Tersedia</span>
                        <?php else: ?>
                           <span class="absolute top-2 left-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full">Dipinjam</span>
                        <?php endif; ?>
                     </div>
                     <div class="p-4 flex-1 flex flex-col">
                        <div class="flex-1">
                           <h3 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($item['nama']) ?></h3>
                           <p class="text-sm text-gray-600"><?= htmlspecialchars($item['kategori']) ?></p>
                        </div>
                        <a href="#" class="mt-4 block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                           Lihat Detail
                        </a>
                     </div>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>

      </main>
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