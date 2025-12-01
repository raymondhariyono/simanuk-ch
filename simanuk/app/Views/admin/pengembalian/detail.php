<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">

   <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-700">Verifikasi Fisik Sarana / Prasarana</h2>
   </div>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="md:col-span-2 space-y-6">

         <?php foreach ($itemsSarana as $item) : ?>
            <div class="bg-white shadow rounded-lg p-6">
               <div class="flex justify-between mb-4">
                  <div>
                     <h4 class="text-lg font-bold text-gray-800"><?= esc($item['nama_sarana']) ?></h4>
                     <p class="text-sm text-gray-500">Jumlah Kembali: <b><?= esc($item['jumlah']) ?> Unit</b></p>
                  </div>
                  <div class="text-right">
                     <span class="block text-xs text-gray-500">Kondisi Dilaporkan User:</span>
                     <span class="font-bold <?= $item['kondisi_akhir'] == 'Baik' ? 'text-green-600' : 'text-red-600' ?>">
                        <?= esc($item['kondisi_akhir'] ?? 'Belum Lapor') ?>
                     </span>
                  </div>
               </div>

               <div class="grid grid-cols-2 gap-4 mt-4 bg-gray-50 p-4 rounded-lg">
                  <div class="text-center">
                     <p class="text-xs font-bold text-gray-500 mb-2">FOTO SEBELUM (SAAT AMBIL)</p>
                     <?php if ($item['foto_sebelum']): ?>
                        <a href="<?= base_url($item['foto_sebelum']) ?>" target="_blank">
                           <img src="<?= base_url($item['foto_sebelum']) ?>" class="h-32 mx-auto object-cover rounded border border-gray-300 hover:opacity-75 transition">
                        </a>

                        <button onclick="openRejectModal('<?= $item['id_detail_sarana'] ?>', 'sarana', 'sebelum')"
                           class="mt-2 text-xs text-red-600 hover:text-red-800 underline">
                           ❌ Tolak Foto Ini
                        </button>
                     <?php else: ?>
                        <div class="h-32 flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">Tidak ada foto</div>
                     <?php endif; ?>
                  </div>

                  <div id="rejectPhotoModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                     <div class="bg-white p-6 rounded-lg w-96">
                        <h3 class="text-lg font-bold mb-2">Tolak Foto Bukti</h3>
                        <form id="formRejectPhoto" method="post">
                           <?= csrf_field() ?>
                           <textarea name="alasan" class="w-full border p-2 text-sm rounded" placeholder="Alasan penolakan (contoh: Foto buram)" required></textarea>
                           <div class="flex justify-end gap-2 mt-4">
                              <button type="button" onclick="document.getElementById('rejectPhotoModal').classList.add('hidden')" class="px-3 py-1 bg-gray-200 rounded text-sm">Batal</button>
                              <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded text-sm">Tolak Foto</button>
                           </div>
                        </form>
                     </div>
                  </div>

                  <div class="text-center">
                     <p class="text-xs font-bold text-gray-500 mb-2">FOTO SESUDAH (SAAT KEMBALI)</p>
                     <?php if ($item['foto_sesudah']): ?>
                        <a href="<?= base_url($item['foto_sesudah']) ?>" target="_blank">
                           <img src="<?= base_url($item['foto_sesudah']) ?>" class="h-32 mx-auto object-cover rounded border border-gray-300 hover:opacity-75 transition">
                        </a>

                        <button onclick="openRejectModal('<?= $item['id_detail_sarana'] ?>', 'sarana', 'sesudah')"
                           class="mt-2 text-xs text-red-600 hover:text-red-800 underline">
                           ❌ Tolak Foto Ini
                        </button>
                     <?php else: ?>
                        <div class="h-32 flex items-center justify-center text-red-400 text-xs border border-dashed border-red-300 bg-red-50">
                           User <span class="font-bold">"<?= $peminjaman['nama_lengkap'] ?>"</span> Belum Upload
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>

         <?php foreach ($itemsPrasarana as $item) : ?>
            <div class="bg-white shadow rounded-lg p-6">
               <div class="flex justify-between mb-4">
                  <div>
                     <h4 class="text-lg font-bold text-gray-800"><?= esc($item['nama_prasarana']) ?></h4>
                  </div>
                  <div class="text-right">
                     <span class="block text-xs text-gray-500">Kondisi Dilaporkan User:</span>
                     <span class="font-bold <?= $item['kondisi_akhir'] == 'Baik' ? 'text-green-600' : 'text-red-600' ?>">
                        <?= esc($item['kondisi_akhir'] ?? 'Belum Lapor') ?>
                     </span>
                  </div>
               </div>

               <div class="grid grid-cols-2 gap-4 mt-4 bg-gray-50 p-4 rounded-lg">
                  <div class="text-center">
                     <p class="text-xs font-bold text-gray-500 mb-2">FOTO SEBELUM (SAAT AMBIL)</p>
                     <?php if ($item['foto_sebelum']): ?>
                        <a href="<?= base_url($item['foto_sebelum']) ?>" target="_blank">
                           <img src="<?= base_url($item['foto_sebelum']) ?>" class="h-32 mx-auto object-cover rounded border border-gray-300 hover:opacity-75 transition">
                        </a>

                        <button onclick="openRejectModal('<?= $item['id_detail_prasarana'] ?>', 'prasarana', 'sebelum')"
                           class="mt-2 text-xs text-red-600 hover:text-red-800 underline">
                           ❌ Tolak Foto Ini
                        </button>
                     <?php else: ?>
                        <div class="h-32 flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">Tidak ada foto</div>
                     <?php endif; ?>
                  </div>

                  <div class="text-center">
                     <p class="text-xs font-bold text-gray-500 mb-2">FOTO SESUDAH (SAAT KEMBALI)</p>
                     <?php if ($item['foto_sesudah']): ?>
                        <a href="<?= base_url($item['foto_sesudah']) ?>" target="_blank">
                           <img src="<?= base_url($item['foto_sesudah']) ?>" class="h-32 mx-auto object-cover rounded border border-gray-300 hover:opacity-75 transition">
                        </a>

                        <button onclick="openRejectModal('<?= $item['id_detail_prasarana'] ?>', 'prasarana', 'sebelum')"
                           class="mt-2 text-xs text-red-600 hover:text-red-800 underline">
                           ❌ Tolak Foto Ini
                        </button>
                     <?php else: ?>
                        <div class="h-32 flex items-center justify-center text-red-400 text-xs border border-dashed border-red-300 bg-red-50">
                           User <span class="font-bold">"<?= $peminjaman['nama_lengkap'] ?>"</span> Belum Upload
                        </div>
                     <?php endif; ?>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>

      </div>

      <div class="space-y-6">
         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Aksi Admin</h3>

            <p class="text-sm text-gray-600 mb-4">
               Pastikan Anda telah memeriksa kondisi sarana/prasarana sesuai dengan foto bukti yang dilampirkan.
            </p>

            <form action="<?= site_url('admin/pengembalian/selesai/' . $peminjaman['id_peminjaman']) ?>" method="post" onsubmit="return confirm('Pastikan semua sarana/prasrana sudah diterima kembali. Stok akan dikembalikan ke sistem.')">
               <?= csrf_field() ?>

               <?php
               $allReturned = true;
               foreach ($itemsSarana as $i) {
                  if (empty($i['foto_sesudah'])) $allReturned = false;
               }
               ?>

               <?php if (!$allReturned): ?>
                  <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-4">
                     <p class="text-xs text-yellow-700">⚠️ Peringatan: Ada item yang belum memiliki bukti foto pengembalian dari user.</p>
                  </div>
               <?php endif; ?>

               <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                  ✅ Terima & Selesai
               </button>
            </form>

            <div class="mt-4 pt-4 border-t">
               <a href="<?= site_url('admin/pengembalian') ?>" class="block text-center text-sm text-gray-500 hover:text-gray-700">Kembali ke Daftar</a>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
   function openRejectModal(idDetail, tipe, jenisFoto) {
      const form = document.getElementById('formRejectPhoto');
      form.action = '<?= site_url("admin/peminjaman/tolak-foto/") ?>' + tipe + '/' + jenisFoto + '/' + idDetail;
      document.getElementById('rejectPhotoModal').classList.remove('hidden');
   }
</script>

<?= $this->endSection(); ?>