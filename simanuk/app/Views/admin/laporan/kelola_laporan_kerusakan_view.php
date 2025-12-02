<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Manajemen Laporan Kerusakan</h2>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
   <?php endif; ?>

   <?php if (isset($breadcrumbs)) : ?>
      <div class="mt-2">
         <?= render_breadcrumb($breadcrumbs); ?>
      </div>
   <?php endif; ?>

   <div class="mb-4 border-b border-gray-200">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="damageTab" role="tablist">
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg hover:text-blue-600 hover:border-blue-600"
               id="sarana-tab" type="button" role="tab" aria-controls="sarana" aria-selected="true"
               onclick="switchTab('sarana')">
               Laporan Sarana
            </button>
         </li>
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
               id="prasarana-tab" type="button" role="tab" aria-controls="prasarana" aria-selected="false"
               onclick="switchTab('prasarana')">
               Laporan Prasarana
            </button>
         </li>
      </ul>
   </div>

   <div class="flex justify-end items-center mb-4">
      <a href="<?= site_url('admin/laporan-kerusakan/new') ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded flex items-center gap-2 shadow-sm">
         <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
         </svg>
         Buat Laporan Internal
      </a>
   </div>

   <div id="damageTabContent">

      <div class="" id="sarana-panel" role="tabpanel">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Pelapor & Aset</th>
                        <th class="px-4 py-3">Kerusakan</th>
                        <th class="px-4 py-3">Bukti</th>
                        <th class="px-4 py-3">Status & Tindak Lanjut</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($laporanSarana)): ?>
                        <tr>
                           <td colspan="5" class="px-4 py-3 text-center text-gray-500">Tidak ada laporan Sarana.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($laporanSarana as $row) : ?>
                           <tr class="text-gray-700 hover:bg-gray-50">
                              <td class="px-4 py-3">
                                 <?php if (!empty($row['id_peminjaman'])): ?>

                                    <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                    <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100 mt-1 inline-block">
                                       Transaksi #<?= $row['id_peminjaman'] ?>
                                    </span>

                                 <?php else: ?>

                                    <?php if (strtolower($row['nama_role']) === 'admin'): ?>
                                       <p class="text-xs text-gray-500 mt-1">Oleh: <?= esc($row['nama_lengkap']) ?></p>
                                       <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded border border-purple-200">
                                          INTERNAL (Cek Rutin)
                                       </span>

                                    <?php else: ?>

                                       <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                       <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                       <span class="text-[10px] bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded border border-orange-100 mt-1 inline-block">
                                          Laporan Umum (Non-Transaksi)
                                       </span>

                                    <?php endif; ?>

                                 <?php endif; ?>

                                 <div class="mt-2 text-xs font-bold bg-gray-100 inline-block px-2 py-1 rounded">
                                    <?= esc($row['nama_aset']) ?>
                                    <?php if (isset($row['jumlah']) && $row['jumlah'] > 1): ?>
                                       (x<?= $row['jumlah'] ?>)
                                    <?php endif; ?>
                                 </div>
                              </td>

                              <td class="px-4 py-3 text-sm w-1/3">
                                 <p class="font-bold"><?= esc($row['judul_laporan']) ?></p>
                                 <p class="text-xs mt-1"><?= esc($row['deskripsi_kerusakan']) ?></p>
                                 <p class="text-xs text-gray-400 mt-2"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></p>
                              </td>
                              <td class="px-4 py-3">
                                 <a href="<?= base_url($row['bukti_foto']) ?>" target="_blank">
                                    <img src="<?= base_url($row['bukti_foto']) ?>" class="h-16 w-16 object-cover rounded border hover:scale-150 transition">
                                 </a>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                 <?php
                                 $color = 'bg-gray-200 text-gray-700';
                                 if ($row['status_laporan'] == 'Diajukan') $color = 'bg-yellow-100 text-yellow-800';
                                 if ($row['status_laporan'] == 'Diproses') $color = 'bg-blue-100 text-blue-800';
                                 if ($row['status_laporan'] == 'Selesai') $color = 'bg-green-100 text-green-800';
                                 if ($row['status_laporan'] == 'Ditolak') $color = 'bg-red-100 text-red-800';
                                 ?>
                                 <span class="px-2 py-1 font-semibold text-xs rounded-full <?= $color ?>">
                                    <?= esc($row['status_laporan']) ?>
                                 </span>
                                 <?php if ($row['tindak_lanjut']): ?>
                                    <div class="mt-2 p-2 bg-gray-50 text-xs border rounded">
                                       <b>TL:</b> <?= esc($row['tindak_lanjut']) ?>
                                    </div>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3">
                                 <button onclick="openProcessModal('<?= $row['id_laporan'] ?>')"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Proses
                                 </button>
                              </td>
                           </tr>
                        <?php endforeach; ?>
                     <?php endif; ?>
                  </tbody>
               </table>
            </div>
         </div>
      </div>

      <div class="hidden" id="prasarana-panel" role="tabpanel">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Pelapor & Aset</th>
                        <th class="px-4 py-3">Kerusakan</th>
                        <th class="px-4 py-3">Bukti</th>
                        <th class="px-4 py-3">Status & Tindak Lanjut</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($laporanPrasarana)): ?>
                        <tr>
                           <td colspan="5" class="px-4 py-3 text-center text-gray-500">Tidak ada laporan Prasarana.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($laporanPrasarana as $row) : ?>
                           <tr class="text-gray-700 hover:bg-gray-50">
                              <td class="px-4 py-3">
                                 <?php if (!empty($row['id_peminjaman'])): ?>

                                    <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                    <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                    <span class="text-[10px] bg-blue-50 text-blue-600 px-1.5 py-0.5 rounded border border-blue-100 mt-1 inline-block">
                                       Transaksi #<?= $row['id_peminjaman'] ?>
                                    </span>

                                 <?php else: ?>

                                    <?php if (strtolower($row['nama_role']) === 'admin'): ?>

                                       <p class="text-xs text-gray-500 mt-1">Oleh: <?= esc($row['nama_lengkap']) ?></p>
                                       <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded border border-purple-200">
                                          INTERNAL (Cek Rutin)
                                       </span>

                                    <?php else: ?>

                                       <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                       <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                       <span class="text-[10px] bg-orange-50 text-orange-600 px-1.5 py-0.5 rounded border border-orange-100 mt-1 inline-block">
                                          Laporan Umum (Non-Transaksi)
                                       </span>

                                    <?php endif; ?>

                                 <?php endif; ?>

                                 <div class="mt-2 text-xs font-bold bg-gray-100 inline-block px-2 py-1 rounded">
                                    <?= esc($row['nama_aset']) ?>
                                    <?php if (isset($row['jumlah']) && $row['jumlah'] > 1): ?>
                                       (x<?= $row['jumlah'] ?>)
                                    <?php endif; ?>
                                 </div>
                              </td>

                              <td class="px-4 py-3 text-sm w-1/3">
                                 <p class="font-bold"><?= esc($row['judul_laporan']) ?></p>
                                 <p class="text-xs mt-1"><?= esc($row['deskripsi_kerusakan']) ?></p>
                                 <p class="text-xs text-gray-400 mt-2"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></p>
                              </td>
                              <td class="px-4 py-3">
                                 <a href="<?= base_url($row['bukti_foto']) ?>" target="_blank">
                                    <img src="<?= base_url($row['bukti_foto']) ?>" class="h-16 w-16 object-cover rounded border hover:scale-150 transition">
                                 </a>
                              </td>
                              <td class="px-4 py-3 text-sm">
                                 <?php
                                 $color = 'bg-gray-200 text-gray-700';
                                 if ($row['status_laporan'] == 'Diajukan') $color = 'bg-yellow-100 text-yellow-800';
                                 if ($row['status_laporan'] == 'Diproses') $color = 'bg-blue-100 text-blue-800';
                                 if ($row['status_laporan'] == 'Selesai') $color = 'bg-green-100 text-green-800';
                                 if ($row['status_laporan'] == 'Ditolak') $color = 'bg-red-100 text-red-800';
                                 ?>
                                 <span class="px-2 py-1 font-semibold text-xs rounded-full <?= $color ?>">
                                    <?= esc($row['status_laporan']) ?>
                                 </span>
                                 <?php if ($row['tindak_lanjut']): ?>
                                    <div class="mt-2 p-2 bg-gray-50 text-xs border rounded">
                                       <b>TL:</b> <?= esc($row['tindak_lanjut']) ?>
                                    </div>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3">
                                 <button onclick="openProcessModal('<?= $row['id_laporan'] ?>')"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    Proses
                                 </button>
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

<div id="processModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center">
   <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
      <button onclick="document.getElementById('processModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
      <h3 class="text-lg font-bold mb-4">Tindak Lanjut Laporan</h3>

      <form id="formProcess" action="" method="post">
         <?= csrf_field() ?>
         <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Status Baru</label>
            <select name="status_laporan" class="mt-1 block w-full shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
               <option value="Diproses">Diproses (Sedang Diperbaiki)</option>
               <option value="Selesai">Selesai (Sudah Baik/Diganti)</option>
               <option value="Ditolak">Ditolak (Laporan Palsu)</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">
               * <b>Diproses:</b> Stok sarana akan <b>berkurang</b> sementara atau prasarana akan <b>tidak tersedia</b>.<br>
               * <b>Selesai:</b> Stok sarana akan <b>bertambah</b> kembali atau prasarana akan <b>tersedia</b>.<br>
               * Prasarana akan berubah status menjadi Perawatan/Tersedia.
            </p>
         </div>
         <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Catatan Tindak Lanjut</label>
            <textarea name="tindak_lanjut" rows="3" class="mt-1 block w-full shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: Kabel diganti baru..."></textarea>
         </div>
         <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
         </div>
      </form>
   </div>
</div>

<script>
   // Logic Tab
   document.addEventListener('DOMContentLoaded', function() {
      // Set Sarana sebagai default aktif
      switchTab('sarana');
   });

   function switchTab(tabName) {
      // Logika untuk panel (konten)
      const saranaPanel = document.getElementById('sarana-panel');
      const prasaranaPanel = document.getElementById('prasarana-panel');

      // Logika untuk tab (tombol)
      const saranaTab = document.getElementById('sarana-tab');
      const prasaranaTab = document.getElementById('prasarana-tab');

      if (tabName === 'sarana') {
         saranaPanel.classList.remove('hidden');
         prasaranaPanel.classList.add('hidden');

         saranaTab.classList.add('text-blue-600', 'border-blue-600');
         saranaTab.classList.remove('border-transparent');

         prasaranaTab.classList.remove('text-blue-600', 'border-blue-600');
         prasaranaTab.classList.add('border-transparent');
      } else {
         saranaPanel.classList.add('hidden');
         prasaranaPanel.classList.remove('hidden');

         prasaranaTab.classList.add('text-blue-600', 'border-blue-600');
         prasaranaTab.classList.remove('border-transparent');

         saranaTab.classList.remove('text-blue-600', 'border-blue-600');
         saranaTab.classList.add('border-transparent');
      }
   }

   // Logic Modal
   function openProcessModal(id) {
      const modal = document.getElementById('processModal');
      const form = document.getElementById('formProcess');
      // Set action URL untuk form proses
      form.action = '<?= site_url("admin/laporan-kerusakan/update/") ?>' + id;
      modal.classList.remove('hidden');
   }

   // Logic penutup modal sudah ada di dalam tag modal itu sendiri
</script>

<?= $this->endSection(); ?>