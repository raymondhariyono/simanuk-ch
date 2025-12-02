<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Manajemen Transaksi Peminjaman</h2>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
   <?php endif; ?>

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

   <div class="mb-4 border-b border-gray-200">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="adminTab" role="tablist">
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 text-red-600 border-red-600 rounded-t-lg active"
               id="pending-tab" type="button" onclick="switchAdminTab('pending')">
               Verifikasi Baru
               <?php if (count($pendingLoans) > 0): ?>
                  <span class="ml-2 bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= count($pendingLoans) ?></span>
               <?php endif; ?>
            </button>
         </li>
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600"
               id="active-tab" type="button" onclick="switchAdminTab('active')">
               Sedang Berjalan (Aktif)
               <?php if (count($activeLoans) > 0): ?>
                  <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= count($activeLoans) ?></span>
               <?php endif; ?>
            </button>
         </li>
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent hover:text-gray-600"
               id="history-tab" type="button" onclick="switchAdminTab('history')">
               Riwayat Selesai
            </button>
         </li>
      </ul>
   </div>

   <div id="adminTabContent">

      <div id="pending-panel">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Peminjam</th>
                        <th class="px-4 py-3">Kegiatan</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($pendingLoans)): ?>
                        <tr>
                           <td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada permintaan baru.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($pendingLoans as $row): ?>
                           <tr class="text-gray-700">
                              <td class="px-4 py-3">
                                 <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                              </td>
                              <td class="px-4 py-3 text-sm"><?= esc($row['kegiatan']) ?></td>
                              <td class="px-4 py-3 text-xs">
                                 <?= date('d M', strtotime($row['tgl_pinjam_dimulai'])) ?> - <?= date('d M', strtotime($row['tgl_pinjam_selesai'])) ?>
                              </td>
                              <td class="px-4 py-3">
                                 <a href="<?= site_url('tu/verifikasi-peminjaman/detail/' . $row['id_peminjaman']) ?>" class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                    Verifikasi
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

      <div id="active-panel" class="hidden">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Peminjam</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tenggat</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($activeLoans)): ?>
                        <tr>
                           <td colspan="4" class="px-4 py-3 text-center text-gray-500">Tidak ada peminjaman aktif.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($activeLoans as $row): ?>
                           <tr class="text-gray-700">
                              <td class="px-4 py-3">
                                 <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-500"><?= esc($row['kegiatan']) ?></p>
                              </td>
                              <td class="px-4 py-3 text-xs">
                                 <?php if ($row['status_peminjaman_global'] == 'Disetujui'): ?>
                                    <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">Disetujui (Belum Diambil)</span>
                                 <?php else: ?>
                                    <span class="px-2 py-1 font-semibold leading-tight text-indigo-700 bg-indigo-100 rounded-full">Dipinjam</span>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                 <?= date('d M Y', strtotime($row['tgl_pinjam_selesai'])) . ' - ' . date('d M Y', strtotime($row['tgl_pinjam_selesai'] . ' + 3 days')) ?>
                                 
                                 <?php if (date('Y-m-d', strtotime($row['tgl_pinjam_selesai'])) > date('Y-m-d', strtotime($row['tgl_pinjam_selesai'] . ' + 3 days'))) : ?>
                                    <span class="ml-2 text-xs text-red-600 font-bold">(Terlambat)</span>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3">
                                 <?php if ($row['status_peminjaman_global'] == 'Dipinjam'): ?>
                                    <a href="<?= site_url('tu/pengembalian/detail/' . $row['id_peminjaman']) ?>" class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                                       Proses Kembali
                                    </a>
                                 <?php else: ?>
                                    <span class="text-gray-400 text-xs">Menunggu Pengambilan</span>
                                 <?php endif; ?>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

      <div id="history-panel" class="hidden">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Peminjam & Kegiatan</th>
                        <th class="px-4 py-3">Tgl Selesai</th>
                        <th class="px-4 py-3">Status Akhir</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($historyLoans)): ?>
                        <tr>
                           <td colspan="3" class="px-4 py-3 text-center text-gray-500">Belum ada riwayat.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($historyLoans as $row): ?>
                           <tr class="text-gray-700">
                              <td class="px-4 py-3">
                                 <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-500"><?= esc($row['kegiatan']) ?></p>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                 <?= date('d M Y', strtotime($row['tgl_pinjam_selesai'])) ?>
                              </td>
                              <td class="px-4 py-3 text-xs">
                                 <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                          <?= $row['status_peminjaman_global'] == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' ?>">
                                    <?= esc($row['status_peminjaman_global']) ?>
                                 </span>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

   </div>
</div>

<script>
   function switchAdminTab(tabName) {
      // Hide all panels
      ['pending', 'active', 'history'].forEach(t => {
         document.getElementById(t + '-panel').classList.add('hidden');
         // Reset Button Style
         const btn = document.getElementById(t + '-tab');
         btn.className = "inline-block p-4 border-b-2 border-transparent hover:text-gray-600 text-gray-500";
      });

      // Show selected
      document.getElementById(tabName + '-panel').classList.remove('hidden');

      // Active Button Style
      const activeBtn = document.getElementById(tabName + '-tab');
      // Warna beda untuk Pending (Merah) dan lainnya (Biru) opsional, di sini pakai biru standard
      activeBtn.className = "inline-block p-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg active";
      if (tabName === 'pending') {
         activeBtn.className = "inline-block p-4 border-b-2 text-red-600 border-red-600 rounded-t-lg active";
      }
   }

   // Default open pending
   document.addEventListener('DOMContentLoaded', function() {
      switchAdminTab('pending');
   });
</script>

<?= $this->endSection(); ?>