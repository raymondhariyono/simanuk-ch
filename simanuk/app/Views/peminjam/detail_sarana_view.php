<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<?php if (isset($breadcrumbs)) : ?>
   <?= render_breadcrumb($breadcrumbs); ?>
<?php endif; ?>
<div class="max-w-7xl mx-auto p-6 grid grid-cols-3 gap-6">
   <!-- KIRI -->
   <div class="col-span-2 flex flex-col gap-6">

      <!-- GAMBAR -->
      <div class="bg-white rounded-lg p-5 shadow-sm">
         <div class="w-full h-96 bg-gray-200 rounded-lg mb-4"></div>

         <div class="flex gap-3">
            <div class="w-20 h-20 bg-gray-200 border rounded-lg"></div>
            <div class="w-20 h-20 bg-gray-200 border rounded-lg"></div>
            <div class="w-20 h-20 bg-gray-200 border rounded-lg"></div>
            <div class="w-20 h-20 bg-gray-200 border rounded-lg"></div>
         </div>
      </div>

      <!-- DETAIL SARPRAS -->
      <div class="bg-white rounded-lg p-6 shadow-sm">

         <h2 class="text-xl font-semibold mb-1"><?= esc($sarana['nama_sarana']) ?></h2>
         <p class="text-gray-600 mb-3"><?= esc($sarana['kode_sarana']) ?></p>

         <div class="flex gap-2 mb-4">
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($sarana['nama_kategori']) ?></span>
            <span class="px-3 py-1 bg-green-100 text-green-600 font-bold rounded text-sm"><?= esc($sarana['status_ketersediaan']) ?></span>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($sarana['nama_lokasi']) ?></span>
         </div>

         <div class="border-b mb-4"></div>

         <h2 class="text-lg font-semibold text-gray-800 mb-2">Spesifikasi</h2>
         
         <?php if (!empty($sarana['spesifikasi']) && is_array($sarana['spesifikasi'])) : ?>
            <ul class="space-y-2 text-gray-600">
               <?php foreach ($sarana['spesifikasi'] as $key => $value) : ?>
                  <li><span class="font-semibold"><?= esc(ucfirst(str_replace('_', ' ', $key))); ?>:</span> <?= esc($value); ?></li>
               <?php endforeach; ?>
            </ul>
         <?php else : ?>
            <p class="text-gray-500">Tidak ada data spesifikasi yang tersedia.</p>
         <?php endif; ?>

      </div>

   </div>

   <!-- KANAN -->
   <div class="col-span-1">
      <div class="bg-white rounded-lg p-6 shadow-sm border border-gray-200">

         <h3 class="text-lg font-semibold mb-4">Jadwal Peminjaman</h3>

         <div class="flex items-center justify-between mb-4">
            <button class="px-3 py-1 border rounded">{"<"}< /button>
                  <p class="font-medium">November 2025</p>
                  <button class="px-3 py-1 border rounded">{">"}</button>
         </div>

         <div class="grid grid-cols-7 gap-2 text-center text-sm mb-6">
            <div class="py-2 text-gray-500">1</div>
            <div class="py-2 bg-blue-500 text-white rounded">2</div>
            <div class="py-2 text-gray-500">3</div>
            <div class="py-2 text-gray-500">4</div>
            <div class="py-2 text-gray-500">5</div>
            <div class="py-2 text-gray-500">6</div>
            <div class="py-2 text-gray-500">7</div>
         </div>

         <div class="space-y-2">
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
               <span class="text-sm text-gray-700">Dipinjam</span>
            </div>

            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
               <span class="text-sm text-gray-700">Dipinjam sebagian hari</span>
            </div>
         </div>

      </div>
   </div>

</div>

<?= $this->endSection(); ?>