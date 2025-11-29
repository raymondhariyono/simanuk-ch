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
            <?php if (empty($dataLaporan)): ?>
               <tr>
                  <td colspan="5" class="px-4 py-3 text-center text-gray-500">Tidak ada laporan.</td>
               </tr>
            <?php else: ?>
               <?php foreach ($dataLaporan as $row) : ?>
                  <tr class="text-gray-700 hover:bg-gray-50">
                     <td class="px-4 py-3">
                        <p class="font-semibold text-sm"><?= esc($row['nama_lengkap']) ?></p>
                        <p class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></p>
                        <div class="mt-2 text-xs font-bold bg-gray-100 inline-block px-2 py-1 rounded">
                           <?= esc($row['nama_aset']) ?>
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
                        <button onclick="openProcessModal('<?= $row['id_laporan'] ?>', '<?= $row['status_laporan'] ?>')"
                           class="px-3 py-1 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">
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

<?php if (!defined('MODAL_PROSES_LOADED')): define('MODAL_PROSES_LOADED', true); ?>
   <div id="processModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 relative">
         <button onclick="document.getElementById('processModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">&times;</button>
         <h3 class="text-lg font-bold mb-4">Tindak Lanjut Laporan</h3>

         <form id="formProcess" action="" method="post">
            <?= csrf_field() ?>
            <div class="mb-4">
               <label class="block text-sm font-medium text-gray-700">Status Baru</label>
               <select name="status_laporan" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  <option value="Diproses">Diproses (Sedang Diperbaiki)</option>
                  <option value="Selesai">Selesai (Sudah Baik/Diganti)</option>
                  <option value="Ditolak">Ditolak (Laporan Palsu)</option>
               </select>
               <p class="text-xs text-gray-500 mt-1">
                  *Status 'Diproses' akan mengubah aset jadi 'Tidak Tersedia'.<br>
                  *Status 'Selesai' akan mengubah aset jadi 'Tersedia'.
               </p>
            </div>
            <div class="mb-4">
               <label class="block text-sm font-medium text-gray-700">Catatan Tindak Lanjut</label>
               <textarea name="tindak_lanjut" rows="3" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: Kabel diganti baru..."></textarea>
            </div>
            <div class="flex justify-end">
               <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
            </div>
         </form>
      </div>
   </div>
   <script>
      function openProcessModal(id, currentStatus) {
         const modal = document.getElementById('processModal');
         const form = document.getElementById('formProcess');
         form.action = '<?= site_url("admin/laporan-kerusakan/update/") ?>' + id;
         modal.classList.remove('hidden');
      }
   </script>
<?php endif; ?>