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
         <div class="w-full h-96 bg-gray-100 rounded-lg mb-4 overflow-hidden flex items-center justify-center border border-gray-200 relative">
            <?php if (!empty($fotoPrasarana)) : ?>
               <img id="mainImage"
                  src="<?= base_url($fotoPrasarana[0]['url_foto']) ?>"
                  alt="Foto <?= esc($prasarana['nama_prasarana']) ?>"
                  class="w-full h-full object-contain">
            <?php else : ?>
               <div class="text-gray-400 flex flex-col items-center">
                  <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  <span>Tidak ada foto</span>
               </div>
            <?php endif; ?>
         </div>

         <?php if (!empty($fotoPrasarana) && count($fotoPrasarana) > 1) : ?>
            <div class="flex gap-3 overflow-x-auto pb-2">
               <?php foreach ($fotoPrasarana as $foto) : ?>
                  <div class="w-20 h-20 flex-shrink-0 cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-lg overflow-hidden transition-all"
                     onclick="changeImage('<?= base_url($foto['url_foto']) ?>')">
                     <img src="<?= base_url($foto['url_foto']) ?>"
                        class="w-full h-full object-cover"
                        alt="Thumbnail">
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>

      <!-- DETAIL SARPRAS -->
      <div class="bg-white rounded-lg p-6 shadow-sm">

         <h2 class="text-xl font-semibold mb-1"><?= esc($prasarana['nama_prasarana']) ?></h2>
         <p class="text-gray-600 mb-3"><?= esc($prasarana['kode_prasarana']) ?></p>

         <div class="flex gap-2 mb-4">
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($prasarana['nama_kategori']) ?></span>
            <?php if ($prasarana['status_ketersediaan'] == 'Tersedia'): ?>
               <span class="px-3 py-1 bg-green-100 text-green-600 font-bold rounded text-sm">Tersedia</span>
            <?php elseif ($prasarana['status_ketersediaan'] == 'Dipinjam'): ?>
               <span class="px-3 py-1 bg-yellow-100 text-yellow-600 font-bold rounded text-sm">Dipinjam</span>
            <?php elseif ($prasarana['status_ketersediaan'] == 'Perawatan'): ?>
               <span class="px-3 py-1 bg-yellow-300 text-yellow-800 font-bold rounded text-sm">Perawatan</span>
            <?php else : ?>
               <span class="px-3 py-1 bg-red-100 text-red-600 font-bold rounded text-sm">Tidak Tersedia</span>
            <?php endif; ?>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($prasarana['nama_lokasi']) ?></span>
         </div>

         <div class="border-b mb-4"></div>

         <h2 class="text-lg font-semibold text-gray-800 mb-2">fasilitas</h2>

         <?php if (!empty($prasarana['fasilitas']) && is_array($prasarana['fasilitas'])) : ?>
            <ul class="space-y-2 text-gray-600">
               <?php foreach ($prasarana['fasilitas'] as $key => $value) : ?>
                  <li><span class="font-semibold">- <?= esc($value); ?></li>
               <?php endforeach; ?>
            </ul>
         <?php else : ?>
            <p class="text-gray-500">Tidak ada data fasilitas yang tersedia.</p>
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

<script>
   function changeImage(src) {
      document.getElementById('mainImage').src = src;
   }
</script>

<?= $this->endSection(); ?>