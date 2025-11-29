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
            <?php if (!empty($fotoSarana)) : ?>
               <img id="mainImage"
                  src="<?= base_url($fotoSarana[0]['url_foto']) ?>"
                  alt="Foto <?= esc($sarana['nama_sarana']) ?>"
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

         <?php if (!empty($fotoSarana) && count($fotoSarana) > 1) : ?>
            <div class="flex gap-3 overflow-x-auto pb-2">
               <?php foreach ($fotoSarana as $foto) : ?>
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

         <h2 class="text-xl font-semibold mb-1"><?= esc($sarana['nama_sarana']) ?></h2>
         <p class="text-gray-600 mb-3"><?= esc($sarana['kode_sarana']) ?></p>

         <div class="flex gap-2 mb-4">
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($sarana['nama_kategori']) ?></span>
            <?php if ($sarana['status_ketersediaan'] == 'Tersedia'): ?>
               <span class="px-3 py-1 bg-green-100 text-green-600 font-bold rounded text-sm">Tersedia</span>
            <?php elseif ($sarana['status_ketersediaan'] == 'Dipinjam'): ?>
               <span class="px-3 py-1 bg-yellow-100 text-yellow-600 font-bold rounded text-sm">Dipinjam</span>
            <?php elseif ($sarana['status_ketersediaan'] == 'Perawatan'): ?>
               <span class="px-3 py-1 bg-yellow-300 text-yellow-800 font-bold rounded text-sm">Perawatan</span>
            <?php else : ?>
               <span class="px-3 py-1 bg-red-100 text-red-600 font-bold rounded text-sm">Tidak Tersedia</span>
            <?php endif; ?>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 font-bold rounded text-sm"><?= esc($sarana['nama_lokasi']) ?></span>
         </div>

         <div class="mb-4">
            <span class="font-bold"><?= esc($sarana['nama_sarana']) ?></span> berjumlah <?= esc($sarana['jumlah']) ?>
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

   <div class="col-span-1">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 sticky top-24">

         <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Ketersediaan
         </h3>

         <div class="flex items-center justify-between mb-4 bg-gray-50 p-1 rounded-lg">
            <a href="?bulan=<?= $calendar['bulan'] - 1 ?>&tahun=<?= $calendar['tahun'] ?>"
               class="p-1 rounded hover:bg-white hover:shadow text-gray-500 transition">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
               </svg>
            </a>

            <span class="text-sm font-bold text-gray-700 select-none">
               <?= $calendar['namaBulan'] ?> <?= $calendar['tahun'] ?>
            </span>

            <a href="?bulan=<?= $calendar['bulan'] + 1 ?>&tahun=<?= $calendar['tahun'] ?>"
               class="p-1 rounded hover:bg-white hover:shadow text-gray-500 transition">
               <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
               </svg>
            </a>
         </div>

         <div class="grid grid-cols-7 gap-1 text-center mb-2">
            <?php foreach (['Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb', 'Mg'] as $h): ?>
               <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider"><?= $h ?></div>
            <?php endforeach; ?>
         </div>

         <div class="grid grid-cols-7 gap-1 text-center text-sm">

            <?php for ($i = 0; $i < $calendar['paddingAwal']; $i++) : ?>
               <div></div>
            <?php endfor; ?>

            <?php for ($day = 1; $day <= $calendar['jumlahHari']; $day++) : ?>
               <?php
               $currentDateStr = sprintf('%04d-%02d-%02d', $calendar['tahun'], $calendar['bulan'], $day);
               $isBooked = isset($calendar['bookedDates'][$currentDateStr]);
               $isToday = ($currentDateStr == date('Y-m-d'));

               // Kelas CSS Dasar
               $classes = "h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium transition-all duration-200 mx-auto cursor-default ";

               if ($isBooked) {
                  // Jika dibooking: Merah / Oranye
                  $classes .= "bg-red-100 text-red-600 cursor-not-allowed";
                  $tooltip = "title='Sudah dibooking'";
               } elseif ($isToday) {
                  // Jika hari ini (dan tersedia): Biru solid
                  $classes .= "bg-blue-600 text-white shadow-md ring-2 ring-blue-200";
                  $tooltip = "title='Hari Ini'";
               } else {
                  // Tersedia: Abu-abu, hover hijau
                  $classes .= "text-gray-600 hover:bg-green-100 hover:text-green-600 cursor-pointer";
                  $tooltip = "title='Tersedia'";
               }
               ?>

               <div <?= $tooltip ?> class="<?= $classes ?>">
                  <?= $day ?>
               </div>
            <?php endfor; ?>
         </div>

         <div class="mt-6 pt-4 border-t border-gray-100 space-y-2">
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-red-100 rounded-full border border-red-200"></div>
               <span class="text-xs text-gray-600 font-medium">Tidak Tersedia (Dipinjam)</span>
            </div>
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-white border border-gray-300 rounded-full"></div>
               <span class="text-xs text-gray-600 font-medium">Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
               <span class="text-xs text-gray-600 font-medium">Hari Ini</span>
            </div>
         </div>

         <div class="mt-6">
            <a href="<?= site_url('peminjam/peminjaman/new') ?>"
               class="block w-full text-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
               Ajukan Peminjaman
            </a>
         </div>

      </div>
   </div>

</div>

<script src="<?= base_url('js/peminjam/detail_aset.js') ?>"></script>

<?= $this->endSection(); ?>