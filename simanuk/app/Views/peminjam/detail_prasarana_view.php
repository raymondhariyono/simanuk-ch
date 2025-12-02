<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<?php if (isset($breadcrumbs)) : ?>
   <div class="max-w-7xl mx-auto px-4 md:px-6 pt-4">
      <?= render_breadcrumb($breadcrumbs); ?>
   </div>
<?php endif; ?>

<div class="max-w-7xl mx-auto p-4 md:p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
   
   <div class="lg:col-span-2 flex flex-col gap-6 order-2 lg:order-1">

      <div class="bg-white rounded-xl p-4 md:p-5 shadow-sm border border-gray-100">
         <div class="w-full h-64 md:h-96 bg-gray-50 rounded-lg mb-4 overflow-hidden flex items-center justify-center border border-gray-200 relative group">
            <?php if (!empty($fotoPrasarana)) : ?>
               <img id="mainImage"
                  src="<?= base_url($fotoPrasarana[0]['url_foto']) ?>"
                  alt="Foto <?= esc($prasarana['nama_prasarana']) ?>"
                  class="w-full h-full object-contain transition-transform duration-300 group-hover:scale-105">
            <?php else : ?>
               <div class="text-gray-400 flex flex-col items-center">
                  <svg class="w-12 h-12 md:w-16 md:h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                  </svg>
                  <span class="text-sm">Tidak ada foto</span>
               </div>
            <?php endif; ?>
         </div>

         <?php if (!empty($fotoPrasarana) && count($fotoPrasarana) > 1) : ?>
            <div class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
               <?php foreach ($fotoPrasarana as $foto) : ?>
                  <div class="w-16 h-16 md:w-20 md:h-20 flex-shrink-0 cursor-pointer border-2 border-transparent hover:border-blue-500 rounded-lg overflow-hidden transition-all"
                     onclick="changeImage('<?= base_url($foto['url_foto']) ?>')">
                     <img src="<?= base_url($foto['url_foto']) ?>"
                        class="w-full h-full object-cover"
                        alt="Thumbnail">
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>
      </div>

      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">

         <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1"><?= esc($prasarana['nama_prasarana']) ?></h2>
         <p class="text-sm text-gray-500 mb-4 font-mono"><?= esc($prasarana['kode_prasarana']) ?></p>

         <div class="flex flex-wrap gap-2 mb-6">
            <span class="px-3 py-1 bg-blue-50 text-blue-700 font-semibold rounded-full text-xs md:text-sm border border-blue-100">
                <?= esc($prasarana['nama_kategori']) ?>
            </span>
            
            <?php 
            $statusClass = match($prasarana['status_ketersediaan']) {
                'Tersedia' => 'bg-green-100 text-green-700 border-green-200',
                'Dipinjam' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'Perawatan', 'Renovasi' => 'bg-orange-100 text-orange-700 border-orange-200',
                default => 'bg-red-100 text-red-700 border-red-200'
            };
            ?>
            <span class="px-3 py-1 font-semibold rounded-full text-xs md:text-sm border <?= $statusClass ?>">
                <?= esc($prasarana['status_ketersediaan']) ?>
            </span>

            <span class="px-3 py-1 bg-gray-100 text-gray-700 font-semibold rounded-full text-xs md:text-sm border border-gray-200">
                <i class="fas fa-map-marker-alt mr-1"></i> <?= esc($prasarana['nama_lokasi']) ?>
            </span>
         </div>

         <div class="border-b border-gray-100 mb-6"></div>

         <h3 class="text-lg font-bold text-gray-900 mb-3">Fasilitas</h3>

         <?php if (!empty($prasarana['fasilitas']) && is_array($prasarana['fasilitas'])) : ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
               <?php foreach ($prasarana['fasilitas'] as $key => $value) : ?>
                  <div class="flex items-center space-x-2 text-gray-700">
                     <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                     <span><?= esc($value); ?></span>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php else : ?>
            <p class="text-gray-500 italic text-sm">Tidak ada data fasilitas yang tersedia.</p>
         <?php endif; ?>

      </div>
   </div>

   <div class="lg:col-span-1 order-1 lg:order-2">
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 lg:sticky lg:top-24">

         <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Cek Ketersediaan
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

               $classes = "h-8 w-8 flex items-center justify-center rounded-full text-xs font-medium transition-all duration-200 mx-auto cursor-default ";

               if ($isBooked) {
                  $classes .= "bg-red-100 text-red-600 cursor-not-allowed";
                  $tooltip = "title='Sudah dipinjam'";
               } elseif ($isToday) {
                  $classes .= "bg-blue-600 text-white shadow-md ring-2 ring-blue-200";
                  $tooltip = "title='Hari Ini'";
               } else {
                  $classes .= "text-gray-600 hover:bg-green-100 hover:text-green-600 cursor-pointer hover:font-bold";
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
               <span class="text-xs text-gray-600">Dipinjam</span>
            </div>
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-white border border-gray-300 rounded-full"></div>
               <span class="text-xs text-gray-600">Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
               <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
               <span class="text-xs text-gray-600">Hari Ini</span>
            </div>
         </div>

         <div class="mt-6">
            <a href="<?= site_url('peminjam/peminjaman/new') ?>"
               class="block w-full text-center py-3 px-4 border border-transparent rounded-xl shadow-lg shadow-blue-200 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:-translate-y-0.5">
               Ajukan Peminjaman
            </a>
         </div>

      </div>
   </div>

</div>

<script src="<?= base_url('js/peminjam/detail_aset.js') ?>"></script>

<?= $this->endSection(); ?>