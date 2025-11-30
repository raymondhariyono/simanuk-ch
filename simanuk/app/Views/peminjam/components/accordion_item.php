<div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300">

   <details class="group">
      <summary class="flex items-center justify-between p-6 cursor-pointer list-none bg-white relative z-10">
         <div class="flex-1 pr-4">
            <div class="flex items-center gap-3 mb-1">
               <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                  <?= date('d M Y', strtotime($h['tgl_pinjam_dimulai'])) ?>
               </span>

               <?php
               $statusColor = match ($h['status_peminjaman_global']) {
                  'Diajukan' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                  'Disetujui' => 'bg-blue-100 text-blue-800 border-blue-200',
                  'Dipinjam' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                  'Selesai' => 'bg-green-100 text-green-800 border-green-200',
                  'Ditolak', 'Dibatalkan' => 'bg-red-100 text-red-800 border-red-200',
                  default => 'bg-gray-100 text-gray-800 border-gray-200'
               };
               ?>
               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $statusColor ?>">
                  <?= esc($h['status_peminjaman_global']) ?>
               </span>
            </div>

            <h3 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">
               <?= esc($h['kegiatan']) ?>
            </h3>
            <div class="mt-1 flex items-center text-sm text-gray-500 gap-4">
               <span class="flex items-center">
                  <?= $h['durasi'] ?> Hari
               </span>
               <span class="flex items-center">
                  <?= count($h['items_sarana']) + count($h['items_prasarana']) ?> Aset
               </span>
            </div>
         </div>
         <div class="ml-4 flex-shrink-0">
            <svg class="h-6 w-6 text-gray-400 transform group-open:-rotate-180 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
         </div>
      </summary>

      <div class="border-t border-gray-100 bg-gray-50 p-6">
         <?php if (!empty($h['items_sarana'])) : ?>
            <div class="mb-4">
               <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Sarana</h4>
               <ul class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                  <?php foreach ($h['items_sarana'] as $item) : ?>
                     <li class="px-4 py-3 flex justify-between items-center text-sm">
                        <div>
                           <span class="font-medium text-gray-900 block"><?= esc($item['nama_sarana']) ?></span>
                           <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['kode_sarana']) ?></span>
                           <span class="font-small text-gray-600 block">Jumlah Unit Dipinjam: <?= $item['jumlah'] ?></span>
                           <!-- tambahkan: jumlah yang dipinjam berapa? -->
                        </div>

                        <div class="flex items-center gap-2">
                           <span class="text-xs font-bold px-2 py-1 rounded-full 
                                        <?= match ($item['status']) {
                                             'Selesai' => 'bg-green-100 text-green-800',
                                             'Ditolak' => 'bg-red-100 text-red-800',
                                             default => 'bg-gray-100 text-gray-600'
                                          } ?>">
                              <?= esc($item['status']) ?>
                           </span>

                           <?php if ($item['status'] == 'Diajukan'): ?>
                              <form action="<?= site_url('peminjam/peminjaman/delete-item/' . 'Sarana' . '/' . $item['id_detail_sarana']) ?>"
                                 method="post"
                                 onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                 <?= csrf_field() ?>
                                 <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-200 border border-red-300 rounded-lg text-xs font-medium transition-colors">
                                    Batal
                                 </button>
                              </form>
                           <?php endif; ?>

                           <?php if ($item['status'] == 'Dibatalkan'): ?>
                              <button type="button" onclick="openDetailPenolakanModal(this)" data-alasan="<?= esc($h['keterangan']) ?>" class="text-xs text-gray-600 underline">Lihat Alasan</button>
                           <?php endif; ?>

                           <?php if (in_array($item['status'], ['Disetujui', 'Dipinjam'])): ?>

                              <?php if (!empty($item['catatan_penolakan']) && empty($item['foto_sebelum'])): ?>
                                 <button type="button" data-reason="<?= esc($item['catatan_penolakan']) ?>" onclick="openRejectionModal(this)" class="text-red-600 text-xs underline">Lihat Revisi</button>
                              <?php endif; ?>

                              <?php if (empty($item['foto_sebelum'])): ?>
                                 <button onclick="openUploadModal('sebelum', 'Sarana', '<?= $item['id_detail_sarana'] ?>', '<?= esc($item['nama_sarana']) ?>')" class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded border border-yellow-300 hover:bg-yellow-200">
                                    Upload Foto SEBELUM M
                                 </button>
                              <?php elseif (empty($item['foto_sesudah'])): ?>
                                 <button onclick="openUploadModal('sesudah', 'Sarana', '<?= $item['id_detail_sarana'] ?>', '<?= esc($item['nama_sarana']) ?>')" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded border border-green-300 hover:bg-green-200">
                                    Kembalikan
                                 </button>
                              <?php else: ?>
                                 <span class="text-xs text-gray-400 italic">Menunggu Verifikasi</span>
                              <?php endif; ?>
                           <?php endif; ?>

                           <?php if ($item['status'] == 'Selesai'): ?>
                              <a href="#" class="text-xs text-blue-600 hover:underline">Lihat Detail</a>
                           <?php endif; ?>
                        </div>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         <?php endif; ?>

         <?php if (!empty($h['items_prasarana'])) : ?>
            <div>
               <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Prasarana</h4>
               <ul class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                  <?php foreach ($h['items_prasarana'] as $item) : ?>
                     <li class="px-4 py-3 flex justify-between items-center text-sm">
                        <div>
                           <span class="font-medium text-gray-900 block"><?= esc($item['nama_prasarana']) ?></span>
                           <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded"><?= esc($item['kode_prasarana']) ?></span>
                        </div>
                        <div class="flex items-center gap-2">
                           <span class="text-xs font-bold px-2 py-1 rounded-full <?= match ($item['status']) {
                                                                                    'Selesai' => 'bg-green-100 text-green-800',
                                                                                    'Ditolak' => 'bg-red-100 text-red-800',
                                                                                    default => 'bg-gray-100 text-gray-600'
                                                                                 } ?>">
                              <?= esc($item['status']) ?>
                           </span>

                           <?php if ($item['status'] == 'Diajukan'): ?>
                              <form action="<?= site_url('peminjam/peminjaman/delete-item/' . 'Prasarana' . '/' . $item['id_detail_prasarana']) ?>"
                                 method="post"
                                 onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                 <?= csrf_field() ?>
                                 <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-200 border border-red-300 rounded-lg text-xs font-medium transition-colors">
                                    Batal
                                 </button>
                              </form>
                           <?php endif; ?>

                           <?php if (in_array($item['status'], ['Disetujui', 'Dipinjam'])): ?>
                              <?php if (empty($item['foto_sebelum'])): ?>
                                 <button onclick="openUploadModal('sebelum', 'Prasarana', '<?= $item['id_detail_prasarana'] ?>', '<?= esc($item['nama_prasarana']) ?>')" class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded border border-yellow-300 hover:bg-yellow-200">Upload Foto SEBELUM</button>
                              <?php elseif (empty($item['foto_sesudah'])): ?>
                                 <button onclick="openUploadModal('sesudah', 'Prasarana', '<?= $item['id_detail_prasarana'] ?>', '<?= esc($item['nama_prasarana']) ?>')" class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded border border-green-300 hover:bg-green-200">Kembalikan</button>
                              <?php else: ?>
                                 <span class="text-xs text-gray-400 italic">Menunggu Verifikasi</span>
                              <?php endif; ?>
                           <?php endif; ?>
                        </div>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         <?php endif; ?>

         <div class="mt-4 pt-3 border-t border-gray-200 text-right">
            <a href="<?= site_url('peminjam/peminjaman/detail/' . $h['id_peminjaman']) ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">
               Lihat Detail Transaksi &rarr;
            </a>
         </div>
      </div>
   </details>
</div>