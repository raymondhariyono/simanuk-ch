<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
   <div class="mb-6">
      <h2 class="text-2xl font-semibold text-gray-700">Detail Verifikasi TU</h2>
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
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Data Kegiatan</h3>
            <div class="grid grid-cols-1 gap-4">
               <div>
                  <label class="text-xs text-gray-500 uppercase font-bold">Nama Kegiatan</label>
                  <p class="text-gray-800 font-medium"><?= esc($peminjaman['kegiatan']) ?></p>
               </div>
               <div>
                  <label class="text-xs text-gray-500 uppercase font-bold">Waktu Pelaksanaan</label>
                  <p class="text-gray-800">
                     <?= date('d M Y H:i', strtotime($peminjaman['tgl_pinjam_dimulai'])) ?> s/d 
                     <?= date('d M Y H:i', strtotime($peminjaman['tgl_pinjam_selesai'])) ?>
                  </p>
               </div>
               <div>
                  <label class="text-xs text-gray-500 uppercase font-bold">Keterangan</label>
                  <p class="text-gray-800"><?= esc($peminjaman['keterangan'] ?: '-') ?></p>
               </div>
            </div>
         </div>

         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Item yang Dipinjam</h3>
            <table class="w-full text-left border-collapse">
               <thead>
                  <tr class="bg-gray-50 border-b">
                     <th class="p-3 text-sm font-medium text-gray-600">Nama Item</th>
                     <th class="p-3 text-sm font-medium text-gray-600">Kategori</th>
                     <th class="p-3 text-sm font-medium text-gray-600 text-center">Jumlah</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($itemsSarana as $item) : ?>
                     <tr class="border-b">
                        <td class="p-3"><?= esc($item['nama_sarana']) ?> <br><span class="text-xs text-gray-500"><?= esc($item['kode_sarana']) ?></span></td>
                        <td class="p-3 text-sm">Sarana</td>
                        <td class="p-3 text-center font-bold"><?= esc($item['jumlah']) ?></td>
                     </tr>
                  <?php endforeach; ?>
                  <?php foreach ($itemsPrasarana as $item) : ?>
                     <tr class="border-b">
                        <td class="p-3"><?= esc($item['nama_prasarana']) ?> <br><span class="text-xs text-gray-500"><?= esc($item['kode_prasarana']) ?></span></td>
                        <td class="p-3 text-sm">Prasarana</td>
                        <td class="p-3 text-center font-bold">1</td>
                     </tr>
                  <?php endforeach; ?>
               </tbody>
            </table>
         </div>
      </div>

      <div class="space-y-6">
         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Pemohon</h3>
            <p class="font-bold text-gray-800"><?= esc($peminjaman['nama_lengkap']) ?></p>
            <p class="text-sm text-gray-500"><?= esc($peminjaman['organisasi']) ?></p>
            <div class="mt-2 text-sm">
               <span class="text-gray-500">Kontak:</span> <?= esc($peminjaman['kontak']) ?>
            </div>
         </div>

         <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Aksi Verifikasi</h3>
            
            <?php if ($peminjaman['status_peminjaman_global'] == 'Diajukan') : ?>
               <form action="<?= site_url('tu/verifikasi-peminjaman/approve/' . $peminjaman['id_peminjaman']) ?>" method="post" class="mb-3">
                  <?= csrf_field() ?>
                  <button type="submit" class="w-full py-2 px-4 bg-green-600 text-white rounded hover:bg-green-700 font-bold shadow" onclick="return confirm('Yakin setujui? Stok akan berkurang.')">
                     Setujui
                  </button>
               </form>

               <button onclick="document.getElementById('rejectForm').classList.toggle('hidden')" class="w-full py-2 px-4 bg-red-100 text-red-700 rounded hover:bg-red-200 font-bold border border-red-300">
                  Tolak
               </button>

               <div id="rejectForm" class="hidden mt-3 bg-gray-50 p-3 rounded border">
                  <form action="<?= site_url('tu/verifikasi-peminjaman/reject/' . $peminjaman['id_peminjaman']) ?>" method="post">
                     <?= csrf_field() ?>
                     <textarea name="alasan_tolak" required class="w-full border rounded p-2 text-sm mb-2" placeholder="Alasan penolakan..."></textarea>
                     <button type="submit" class="w-full py-1 bg-red-600 text-white text-xs rounded">Konfirmasi Tolak</button>
                  </form>
               </div>
            <?php else: ?>
               <div class="text-center p-2 bg-gray-100 rounded">
                  Status: <b><?= esc($peminjaman['status_peminjaman_global']) ?></b>
               </div>
            <?php endif; ?>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>