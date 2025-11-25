<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
   <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-700">Verifikasi Peminjaman (TU)</h2>
   </div>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <!-- flash data untuk peminjaman yang gagal, lebih dari 24 jam -->
   <?php if (session()->getFlashdata('info')) : ?>
      <div class="flex items-center bg-blue-100 text-blue-800 text-sm font-medium px-4 py-3 rounded-lg shadow-sm mb-4 border border-blue-200" role="alert">
         <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
         </svg>
         <div>
            <span class="font-bold">Sistem Otomatis:</span> <?= session()->getFlashdata('info') ?>
         </div>
      </div>
   <?php endif; ?>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
         <?= session()->getFlashdata('message') ?>
      </div>
   <?php endif; ?>

   <div class="w-full overflow-hidden rounded-lg shadow-xs">
      <div class="w-full overflow-x-auto">
         <table class="w-full whitespace-no-wrap">
            <thead>
               <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                  <th class="px-4 py-3">Pemohon</th>
                  <th class="px-4 py-3">Kegiatan</th>
                  <th class="px-4 py-3">Tanggal</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Aksi</th>
               </tr>
            </thead>
            <tbody class="bg-white divide-y">
               <?php if (empty($peminjaman)) : ?>
                  <tr>
                     <td colspan="5" class="px-4 py-3 text-center text-gray-500">Tidak ada pengajuan baru.</td>
                  </tr>
               <?php else : ?>
                  <?php foreach ($peminjaman as $row) : ?>
                     <tr class="text-gray-700 hover:bg-gray-50">
                        <td class="px-4 py-3">
                           <div class="flex items-center text-sm">
                              <div>
                                 <p class="font-semibold"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-600"><?= esc($row['organisasi']) ?></p>
                              </div>
                           </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                           <?= esc($row['kegiatan']) ?>
                        </td>
                        <td class="px-4 py-3 text-sm">
                           <?= date('d M', strtotime($row['tgl_pinjam_dimulai'])) ?> - <?= date('d M Y', strtotime($row['tgl_pinjam_selesai'])) ?>
                        </td>
                        <td class="px-4 py-3 text-xs">
                           <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                              <?= esc($row['status_peminjaman_global']) ?>
                           </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                           <a href="<?= site_url('tu/verifikasi-peminjaman/detail/' . $row['id_peminjaman']) ?>"
                              class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                              Proses
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               <?php endif; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>