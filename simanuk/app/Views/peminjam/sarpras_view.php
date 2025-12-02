<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen">
   <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
      <main class="flex-1 overflow-y-auto p-4 md:p-8">

         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
               <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Katalog Fakultas Teknik</h1>
               <p class="text-sm md:text-base text-gray-600 mt-1">
                  Cari dan pinjam sarana & prasarana yang tersedia.
               </p>
            </div>

            <div class="w-full md:w-auto">
               <a href="<?= site_url('peminjam/peminjaman/new') ?>"
                  class="bg-blue-600 text-white py-2 px-4 rounded-lg font-semibold hover:bg-blue-700 w-full md:w-auto text-center whitespace-nowrap flex items-center justify-center shadow-sm transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                     <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Ajukan Peminjaman
               </a>
            </div>
         </div>

         <div class="mb-6">
            <?= $this->include('components/filter_bar', [
               'actionUrl'    => site_url('peminjam/sarpras'),
               'kategoriList' => $kategoriList,
               'lokasiList'   => $lokasiList,
               'filters'      => $filters
            ]) ?>
         </div>

         <div class="mb-6 border-b border-gray-200 overflow-x-auto no-scrollbar">
            <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center min-w-max">
               <li class="mr-2">
                  <button class="inline-block p-4 border-b-2 rounded-t-lg group transition-all duration-300 text-blue-600 border-blue-600 active whitespace-nowrap"
                     id="tab-sarana-btn" onclick="switchTab('sarana')">
                     <span class="flex items-center gap-2">
                        Sarana
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                           <?= $pager_sarana->getTotal('sarana') ?>
                        </span>
                     </span>
                  </button>
               </li>
               <li class="mr-2">
                  <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 group transition-all duration-300 whitespace-nowrap"
                     id="tab-prasarana-btn" onclick="switchTab('prasarana')">
                     <span class="flex items-center gap-2">
                        Prasarana
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                           <?= $pager_prasarana->getTotal('prasarana') ?>
                        </span>
                     </span>
                  </button>
               </li>
            </ul>
         </div>

         <div id="tab-sarana-content">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">
               <?php if (empty($sarana)) : ?>
                  <div class="col-span-full text-center py-10 text-gray-500 bg-white rounded-lg shadow border border-gray-100">
                     <p>Tidak ada data sarana yang ditemukan.</p>
                  </div>
               <?php else : ?>
                  <?php foreach ($sarana as $s): ?>
                     <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300">
                        <div class="relative h-48 bg-gray-100 flex items-center justify-center overflow-hidden group">
                           <?php if (!empty($s['url_foto'])) : ?>
                              <img src="<?= base_url($s['url_foto']) ?>"
                                 alt="<?= esc($s['nama_sarana']) ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                           <?php else : ?>
                              <div class="text-gray-400 flex flex-col items-center">
                                 <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                 </svg>
                                 <span class="text-xs">No Image</span>
                              </div>
                           <?php endif; ?>

                           <span class="absolute top-2 right-2 px-2 py-1 text-[10px] uppercase font-bold text-white rounded shadow-sm 
                                       <?php
                                       if ($s['status_ketersediaan'] == 'Tersedia') echo 'bg-green-500';
                                       elseif ($s['status_ketersediaan'] == 'Dipinjam') echo 'bg-yellow-500';
                                       elseif ($s['status_ketersediaan'] == 'Perawatan') echo 'bg-orange-500';
                                       else echo 'bg-red-500';
                                       ?>">
                              <?= $s['status_ketersediaan'] ?>
                           </span>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                           <div class="flex-1">
                              <h3 class="text-lg font-bold text-gray-900 truncate" title="<?= htmlspecialchars($s['nama_sarana']) ?>">
                                 <?= htmlspecialchars($s['nama_sarana']) ?>
                              </h3>
                              <p class="text-sm text-gray-500 mb-2 flex items-center">
                                 <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                 </svg>
                                 <?= htmlspecialchars($s['nama_kategori']) ?>
                              </p>
                              <p class="text-xs text-gray-400 font-mono bg-gray-50 inline-block px-1 rounded">
                                 <?= esc($s['kode_sarana']) ?>
                              </p>

                              <div class="text-xs mt-2 space-y-1">
                                 <div class="flex justify-between text-green-700 font-medium">
                                    <span>Jumlah Tersedia:</span>
                                    <span><?= $s['jumlah'] ?> Unit</span>
                                 </div>
                                 <?php if (isset($s['stok_rusak']) && $s['stok_rusak'] > 0): ?>
                                    <div class="flex justify-between text-orange-600">
                                       <span>Dalam Perbaikan:</span>
                                       <span><?= $s['stok_rusak'] ?> Unit</span>
                                    </div>
                                 <?php endif; ?>
                              </div>
                           </div>
                           <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($s['kode_sarana'])) ?>"
                              class="mt-4 block w-full text-center bg-white border border-blue-600 text-blue-600 py-2 rounded-lg hover:bg-blue-600 hover:text-white font-medium transition-all text-sm">
                              Lihat Detail
                           </a>
                        </div>
                     </div>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>

            <div class="flex justify-end mt-8">
               <?= $pager_sarana->links('sarana', 'tailwind_pagination') ?>
            </div>
         </div>

         <div id="tab-prasarana-content" class="hidden">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-6">
               <?php if (empty($prasarana)) : ?>
                  <div class="col-span-full text-center py-10 text-gray-500 bg-white rounded-lg shadow border border-gray-100">
                     <p>Tidak ada data prasarana yang ditemukan.</p>
                  </div>
               <?php else : ?>
                  <?php foreach ($prasarana as $p): ?>
                     <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col hover:shadow-md transition-shadow duration-300">
                        <div class="relative h-48 bg-gray-100 flex items-center justify-center overflow-hidden group">
                           <?php if (!empty($p['url_foto'])) : ?>
                              <img src="<?= base_url($p['url_foto']) ?>"
                                 alt="<?= esc($p['nama_prasarana']) ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                           <?php else : ?>
                              <div class="text-gray-400 flex flex-col items-center">
                                 <svg class="w-10 h-10 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                 </svg>
                                 <span class="text-xs">No Image</span>
                              </div>
                           <?php endif; ?>

                           <span class="absolute top-2 right-2 px-2 py-1 text-[10px] uppercase font-bold text-white rounded shadow-sm 
                                       <?php
                                       if ($p['status_ketersediaan'] == 'Tersedia') echo 'bg-green-500';
                                       elseif ($p['status_ketersediaan'] == 'Dipinjam') echo 'bg-yellow-500';
                                       elseif ($p['status_ketersediaan'] == 'Renovasi') echo 'bg-orange-500';
                                       else echo 'bg-red-500';
                                       ?>">
                              <?= $p['status_ketersediaan'] ?>
                           </span>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                           <div class="flex-1">
                              <h3 class="text-lg font-bold text-gray-900 truncate" title="<?= htmlspecialchars($p['nama_prasarana']) ?>">
                                 <?= htmlspecialchars($p['nama_prasarana']) ?>
                              </h3>
                              <p class="text-sm text-gray-500 mb-1 flex items-center">
                                 <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                 </svg>
                                 <?= htmlspecialchars($p['nama_kategori']) ?>
                              </p>
                              <p class="text-xs text-gray-500 flex items-center mt-2">
                                 <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                 </svg>
                                 Kapasitas: <span class="font-bold"><?= esc($p['kapasitas_orang']) ?></span> Orang
                              </p>
                           </div>
                           <a href="<?= site_url('/peminjam/sarpras/detail/' . esc($p['kode_prasarana'])) ?>"
                              class="mt-4 block w-full text-center bg-white border border-blue-600 text-blue-600 py-2 rounded-lg hover:bg-blue-600 hover:text-white font-medium transition-all text-sm">
                              Lihat Detail
                           </a>
                        </div>
                     </div>
                  <?php endforeach; ?>
               <?php endif; ?>
            </div>

            <div class="flex justify-end mt-8">
               <?= $pager_prasarana->links('prasarana', 'tailwind_pagination') ?>
            </div>
         </div>

      </main>
   </div>
</div>

<script src="<?= base_url('js/peminjam/katalog_sarpras.js') ?>"></script>

<?= $this->endSection(); ?>