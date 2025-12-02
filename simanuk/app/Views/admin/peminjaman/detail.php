<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
   <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-700">Detail Pengajuan Peminjaman</h2>
   </div>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <?php if (session()->has('error')) : ?>
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
         <p><?= session('error') ?></p>
      </div>
   <?php endif ?>

   <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

      <div class="md:col-span-2 space-y-6">

         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Kegiatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
               <div>
                  <label class="text-xs text-gray-500 uppercase font-bold">Nama Kegiatan</label>
                  <p class="text-gray-800 font-medium"><?= esc($peminjaman['kegiatan']) ?></p>
               </div>
               <div>
                  <label class="text-xs text-gray-500 uppercase font-bold">Tanggal Pelaksanaan</label>
                  <p class="text-gray-800 font-medium">
                     <?= date('d M Y', strtotime($peminjaman['tgl_pinjam_dimulai'])) ?> s/d
                     <?= date('d M Y', strtotime($peminjaman['tgl_pinjam_selesai'])) ?>
                  </p>
                  <span class="text-xs text-gray-500">(<?= esc($peminjaman['durasi']) ?> Hari)</span>
               </div>
               <div class="md:col-span-2">
                  <label class="text-xs text-gray-500 uppercase font-bold">Keterangan / Keperluan</label>
                  <p class="text-gray-800"><?= esc($peminjaman['keterangan'] ?: '-') ?></p>
               </div>
            </div>
         </div>

         <?php if (!empty($itemsSarana)) : ?>
            <div class="bg-white shadow rounded-lg p-6">
               <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Daftar Sarana</h3>
               <div class="overflow-x-auto">
                  <table class="w-full text-left border-collapse">
                     <thead>
                        <tr class="bg-gray-50 border-b">
                           <th class="p-3 text-sm font-medium text-gray-600">Nama Sarana</th>
                           <th class="p-3 text-sm font-medium text-gray-600">Kode</th>
                           <th class="p-3 text-sm font-medium text-gray-600 text-center">Jumlah</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($itemsSarana as $item) : ?>
                           <tr class="border-b hover:bg-gray-50">
                              <td class="p-3"><?= esc($item['nama_sarana']) ?></td>
                              <td class="p-3 font-mono text-sm text-gray-500"><?= esc($item['kode_sarana']) ?></td>
                              <td class="p-3 text-center font-bold"><?= esc($item['jumlah']) ?></td>
                           </tr>
                           <?php if ($peminjaman['status_peminjaman_global'] == 'Diajukan' && !empty($item['foto_sebelum'])) : ?>
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

                           <?php endif; ?>
                        <?php endforeach; ?>
                     </tbody>
                  </table>
               </div>
            </div>
         <?php endif; ?>

         <?php if (!empty($itemsPrasarana)) : ?>
            <div class="bg-white shadow rounded-lg p-6">
               <h4 class="font-bold text-gray-700 mt-6 mb-3">Daftar Prasarana</h4>
               <?php foreach ($itemsPrasarana as $item) : ?>
                  <div class="bg-white rounded-lg shadow p-6">
                     <h4 class="text-lg font-bold"><?= esc($item['nama_prasarana']) ?></h4>

                     <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>
                        <form action="<?= site_url('peminjam/peminjaman/kembalikan-item/prasarana/' . $item['id_detail_prasarana']) ?>" ...>
                        </form>
                     <?php endif; ?>
                  </div>
               <?php endforeach; ?>
            </div>
         <?php endif; ?>

      </div>

      <div class="space-y-6">

         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Pemohon</h3>
            <div class="flex items-center mb-4">
               <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xl mr-4">
                  <?= substr($peminjaman['nama_lengkap'], 0, 1) ?>
               </div>
               <div>
                  <p class="font-bold text-gray-800"><?= esc($peminjaman['nama_lengkap']) ?></p>
                  <p class="text-sm text-gray-500"><?= esc($peminjaman['organisasi']) ?></p>
               </div>
            </div>
            <div class="space-y-2 text-sm">
               <div class="flex justify-between">
                  <span class="text-gray-500">Kontak:</span>
                  <span class="font-medium"><?= esc($peminjaman['kontak']) ?></span>
               </div>
               <div class="flex justify-between">
                  <span class="text-gray-500">Tanggal Pengajuan:</span>
                  <span class="font-medium"><?= date('d M Y', strtotime($peminjaman['tgl_pengajuan'])) ?></span>
               </div>
            </div>
         </div>

         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Status Verifikasi</h3>

            <div class="mb-6 text-center">
               <span class="px-4 py-2 rounded-full text-sm font-bold 
                        <?= $peminjaman['status_peminjaman_global'] == 'Diajukan' ? 'bg-yellow-100 text-yellow-800' : ($peminjaman['status_peminjaman_global'] == 'Disetujui' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') ?>">
                  <?= esc($peminjaman['status_peminjaman_global']) ?>
               </span>
            </div>

            <?php if ($peminjaman['status_peminjaman_global'] == 'Diajukan') : ?>
               <div class="space-y-3">
                  <form action="<?= site_url('admin/peminjaman/approve/' . $peminjaman['id_peminjaman']) ?>" method="post" onsubmit="return confirm('Setujui peminjaman ini? Stok sarana akan berkurang otomatis.')">
                     <?= csrf_field() ?>
                     <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        ✅ Setujui Peminjaman
                     </button>
                  </form>

                  <button onclick="toggleRejectForm()" class="w-full flex justify-center py-2 px-4 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-200 focus:outline-none">
                     ❌ Tolak Peminjaman
                  </button>

                  <div id="rejectForm" class="hidden mt-4 bg-gray-50 p-3 rounded border">
                     <form action="<?= site_url('admin/peminjaman/reject/' . $peminjaman['id_peminjaman']) ?>" method="post">
                        <?= csrf_field() ?>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Alasan Penolakan</label>
                        <textarea name="alasan_tolak" required rows="2" class="w-full px-2 border-gray-300 rounded-md text-sm mb-2" placeholder="Contoh: Jadwal bentrok..."></textarea>
                        <button type="submit" class="w-full bg-red-600 text-white text-xs rounded hover:bg-red-700">Konfirmasi Tolak</button>
                     </form>
                  </div>
               </div>
            <?php else: ?>
               <div class="text-center text-sm text-gray-500 mt-4">
                  <p>Peminjaman ini sudah diproses oleh Admin.</p>
               </div>
            <?php endif; ?>
         </div>

      </div>
   </div>
</div>

<script>
   function toggleRejectForm() {
      const form = document.getElementById('rejectForm');
      form.classList.toggle('hidden');
   }

   function openRejectModal(idDetail, tipe, jenisFoto) {
      const form = document.getElementById('formRejectPhoto');
      form.action = '<?= site_url("admin/peminjaman/tolak-foto/") ?>' + tipe + '/' + jenisFoto + '/' + idDetail;
      document.getElementById('rejectPhotoModal').classList.remove('hidden');
   }
</script>
<?= $this->endSection(); ?>