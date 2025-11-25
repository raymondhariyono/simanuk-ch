<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Manajemen Laporan Kerusakan (TU)</h2>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
   <?php endif; ?>
   <?php if (session()->getFlashdata('error')) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('error') ?></div>
   <?php endif; ?>

   <?php if (isset($breadcrumbs)) : ?>
      <div class="mt-2">
         <?= render_breadcrumb($breadcrumbs); ?>
      </div>
   <?php endif; ?>

   <div class="mb-4 border-b border-gray-200">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg hover:text-blue-600 hover:border-blue-600"
               id="sarana-tab" type="button" onclick="switchTab('sarana')">
               Laporan Sarana (Barang)
            </button>
         </li>
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
               id="prasarana-tab" type="button" onclick="switchTab('prasarana')">
               Laporan Prasarana (Ruangan)
            </button>
         </li>
      </ul>
   </div>

   <div id="damageTabContent">

      <div class="" id="sarana-panel">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white border border-gray-200">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Pelapor & Aset</th>
                        <th class="px-4 py-3">Detail Kerusakan</th>
                        <th class="px-4 py-3">Bukti Foto</th>
                        <th class="px-4 py-3">Status & Tindak Lanjut</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($laporanSarana)): ?>
                        <tr>
                           <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada laporan kerusakan untuk Sarana.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($laporanSarana as $row) : ?>
                           <tr class="text-gray-700 hover:bg-gray-50">
                              <td class="px-4 py-3">
                                 <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                 <div class="mt-2 text-xs font-bold bg-blue-100 text-blue-800 inline-block px-2 py-1 rounded border border-blue-200">
                                    <?= esc($row['nama_aset']) ?>
                                 </div>
                                 <div class="text-xs text-gray-400 mt-1">Kode: <?= esc($row['kode_aset']) ?></div>
                              </td>
                              <td class="px-4 py-3 text-sm w-1/3 align-top">
                                 <p class="font-bold text-gray-800"><?= esc($row['judul_laporan']) ?></p>
                                 <p class="text-xs mt-1 text-gray-600"><?= esc($row['deskripsi_kerusakan']) ?></p>
                                 <p class="text-xs text-gray-400 mt-2 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
                                 </p>
                              </td>
                              <td class="px-4 py-3 align-top">
                                 <?php if($row['bukti_foto']): ?>
                                    <a href="<?= base_url($row['bukti_foto']) ?>" target="_blank">
                                       <img src="<?= base_url($row['bukti_foto']) ?>" class="h-20 w-20 object-cover rounded-lg border hover:scale-105 transition shadow-sm">
                                    </a>
                                 <?php else: ?>
                                    <span class="text-xs text-gray-400">Tidak ada foto</span>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3 text-sm align-top">
                                 <?php
                                 $color = 'bg-gray-200 text-gray-700';
                                 if ($row['status_laporan'] == 'Diajukan') $color = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                 if ($row['status_laporan'] == 'Diproses') $color = 'bg-blue-100 text-blue-800 border border-blue-200';
                                 if ($row['status_laporan'] == 'Selesai') $color = 'bg-green-100 text-green-800 border border-green-200';
                                 if ($row['status_laporan'] == 'Ditolak') $color = 'bg-red-100 text-red-800 border border-red-200';
                                 ?>
                                 <span class="px-2.5 py-0.5 font-bold text-xs rounded-full <?= $color ?>">
                                    <?= esc($row['status_laporan']) ?>
                                 </span>
                                 <?php if ($row['tindak_lanjut']): ?>
                                    <div class="mt-2 p-2 bg-gray-50 text-xs border rounded text-gray-600">
                                       <strong>TL:</strong> <?= esc($row['tindak_lanjut']) ?>
                                    </div>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3 align-top">
                                 <button onclick="openProcessModal('<?= $row['id_laporan'] ?>')"
                                    class="flex items-center px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Tindak Lanjut
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

      <div class="hidden" id="prasarana-panel">
         <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white border border-gray-200">
            <div class="w-full overflow-x-auto">
               <table class="w-full whitespace-no-wrap">
                  <thead>
                     <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Pelapor & Ruangan</th>
                        <th class="px-4 py-3">Detail Kerusakan</th>
                        <th class="px-4 py-3">Bukti Foto</th>
                        <th class="px-4 py-3">Status & Tindak Lanjut</th>
                        <th class="px-4 py-3">Aksi</th>
                     </tr>
                  </thead>
                  <tbody class="divide-y">
                     <?php if (empty($laporanPrasarana)): ?>
                        <tr>
                           <td colspan="5" class="px-4 py-6 text-center text-gray-500">Tidak ada laporan kerusakan untuk Prasarana.</td>
                        </tr>
                     <?php else: ?>
                        <?php foreach ($laporanPrasarana as $row) : ?>
                           <tr class="text-gray-700 hover:bg-gray-50">
                              <td class="px-4 py-3">
                                 <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                                 <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                                 <div class="mt-2 text-xs font-bold bg-purple-100 text-purple-800 inline-block px-2 py-1 rounded border border-purple-200">
                                    <?= esc($row['nama_aset']) ?>
                                 </div>
                                 <div class="text-xs text-gray-400 mt-1">Kode: <?= esc($row['kode_aset']) ?></div>
                              </td>
                              <td class="px-4 py-3 text-sm w-1/3 align-top">
                                 <p class="font-bold text-gray-800"><?= esc($row['judul_laporan']) ?></p>
                                 <p class="text-xs mt-1 text-gray-600"><?= esc($row['deskripsi_kerusakan']) ?></p>
                                 <p class="text-xs text-gray-400 mt-2"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></p>
                              </td>
                              <td class="px-4 py-3 align-top">
                                 <?php if($row['bukti_foto']): ?>
                                    <a href="<?= base_url($row['bukti_foto']) ?>" target="_blank">
                                       <img src="<?= base_url($row['bukti_foto']) ?>" class="h-20 w-20 object-cover rounded-lg border hover:scale-105 transition shadow-sm">
                                    </a>
                                 <?php else: ?>
                                    <span class="text-xs text-gray-400">Tidak ada foto</span>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3 text-sm align-top">
                                 <?php
                                 $color = 'bg-gray-200 text-gray-700';
                                 if ($row['status_laporan'] == 'Diajukan') $color = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                 if ($row['status_laporan'] == 'Diproses') $color = 'bg-blue-100 text-blue-800 border border-blue-200';
                                 if ($row['status_laporan'] == 'Selesai') $color = 'bg-green-100 text-green-800 border border-green-200';
                                 if ($row['status_laporan'] == 'Ditolak') $color = 'bg-red-100 text-red-800 border border-red-200';
                                 ?>
                                 <span class="px-2.5 py-0.5 font-bold text-xs rounded-full <?= $color ?>">
                                    <?= esc($row['status_laporan']) ?>
                                 </span>
                                 <?php if ($row['tindak_lanjut']): ?>
                                    <div class="mt-2 p-2 bg-gray-50 text-xs border rounded text-gray-600">
                                       <strong>TL:</strong> <?= esc($row['tindak_lanjut']) ?>
                                    </div>
                                 <?php endif; ?>
                              </td>
                              <td class="px-4 py-3 align-top">
                                 <button onclick="openProcessModal('<?= $row['id_laporan'] ?>')"
                                    class="flex items-center px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Tindak Lanjut
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
   <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 relative m-4">
      <button onclick="document.getElementById('processModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
      </button>
      
      <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Update Status & Tindak Lanjut</h3>

      <form id="formProcess" action="" method="post">
         <?= csrf_field() ?>
         <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Status Baru</label>
            <select name="status_laporan" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5">
               <option value="Diproses">Diproses (Sedang diperbaiki/Dicek)</option>
               <option value="Selesai">Selesai (Sudah diperbaiki/Diganti)</option>
               <option value="Ditolak">Ditolak (Laporan tidak valid)</option>
            </select>
            <div class="mt-2 p-3 bg-blue-50 rounded-md text-xs text-blue-700">
               <ul class="list-disc list-inside">
                  <li><strong>Diproses:</strong> Status aset otomatis menjadi 'Perawatan'.</li>
                  <li><strong>Selesai:</strong> Status aset otomatis menjadi 'Tersedia'.</li>
               </ul>
            </div>
         </div>
         
         <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tindak Lanjut</label>
            <textarea name="tindak_lanjut" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2" placeholder="Contoh: Barang telah dibawa ke tempat service, atau Suku cadang diganti baru."></textarea>
         </div>
         
         <div class="flex justify-end gap-3">
            <button type="button" onclick="document.getElementById('processModal').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Batal</button>
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow-sm">Simpan Perubahan</button>
         </div>
      </form>
   </div>
</div>

<script>
   function switchTab(tabName) {
      const saranaPanel = document.getElementById('sarana-panel');
      const prasaranaPanel = document.getElementById('prasarana-panel');
      const saranaTab = document.getElementById('sarana-tab');
      const prasaranaTab = document.getElementById('prasarana-tab');

      if (tabName === 'sarana') {
         saranaPanel.classList.remove('hidden');
         prasaranaPanel.classList.add('hidden');

         saranaTab.classList.add('text-blue-600', 'border-blue-600');
         saranaTab.classList.remove('border-transparent', 'text-gray-600');

         prasaranaTab.classList.remove('text-blue-600', 'border-blue-600');
         prasaranaTab.classList.add('border-transparent', 'text-gray-600');
      } else {
         saranaPanel.classList.add('hidden');
         prasaranaPanel.classList.remove('hidden');

         prasaranaTab.classList.add('text-blue-600', 'border-blue-600');
         prasaranaTab.classList.remove('border-transparent', 'text-gray-600');

         saranaTab.classList.remove('text-blue-600', 'border-blue-600');
         saranaTab.classList.add('border-transparent', 'text-gray-600');
      }
   }

   function openProcessModal(id) {
      const modal = document.getElementById('processModal');
      const form = document.getElementById('formProcess');
      // Set action URL secara dinamis
      form.action = '<?= site_url("tu/laporan-kerusakan/update/") ?>' + id;
      modal.classList.remove('hidden');
   }
   
   // Default tab
   document.addEventListener('DOMContentLoaded', () => switchTab('sarana'));
</script>

<?= $this->endSection(); ?>