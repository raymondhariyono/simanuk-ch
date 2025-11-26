<?php extract($__data ?? []); ?>
<form action="<?= current_url() ?>" method="get" class="mb-8 flex flex-wrap gap-4 items-center bg-white p-4 rounded-lg shadow-sm border border-gray-100">

   <div class="relative flex-grow" style="min-width: 300px;">
      <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
         <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
         </svg>
      </div>
      <input type="text" name="keyword" value="<?= esc($filters['keyword'] ?? '') ?>"
         placeholder="Cari nama atau kode..."
         class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
   </div>

   <div class="flex items-center space-x-2">
      <select name="kategori" class="border border-gray-300 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
         <option value="">Semua Kategori</option>
         <?php foreach ($kategoriList as $k) : ?>
            <option value="<?= $k['id_kategori'] ?>" <?= ($filters['kategori'] ?? '') == $k['id_kategori'] ? 'selected' : '' ?>>
               <?= esc($k['nama_kategori']) ?>
            </option>
         <?php endforeach; ?>
      </select>
   </div>

   <div class="flex items-center space-x-2">
      <select name="lokasi" class="border border-gray-300 rounded-lg py-2 px-3 focus:ring-blue-500 focus:border-blue-500 text-sm">
         <option value="">Semua Lokasi</option>
         <?php foreach ($lokasiList as $l) : ?>
            <option value="<?= $l['id_lokasi'] ?>" <?= ($filters['lokasi'] ?? '') == $l['id_lokasi'] ? 'selected' : '' ?>>
               <?= esc($l['nama_lokasi']) ?>
            </option>
         <?php endforeach; ?>
      </select>
   </div>

   <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
      Filter
   </button>

   <?php if (!empty($filters['keyword']) || !empty($filters['kategori']) || !empty($filters['lokasi'])): ?>
      <a href="<?= $actionUrl ?>" class="text-gray-500 hover:text-gray-700 text-sm underline">Reset</a>
   <?php endif; ?>
</form>