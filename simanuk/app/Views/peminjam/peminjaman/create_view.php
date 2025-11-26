<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-4xl mx-auto p-6">

   <div class="mb-4">
      <h1 class="text-2xl font-bold text-gray-800">Form Pengajuan Peminjaman</h1>
   </div>

   <p class="text-md font-sm text-gray-700 mb-2">
      Isi formulir di bawah ini untuk mengajukan peminjaman <span class="font-bold">sarana / prasarana</span> di <span class="font-bold">Fakultas Teknik</span>
   </p>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <?php if (session()->has('error')) : ?>
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
         <p><?= session('error') ?></p>
      </div>
   <?php endif ?>

   <?php if (session()->has('errors')) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
         <ul class="list-disc list-inside">
            <?php foreach (session('errors') as $error) : ?>
               <li><?= esc($error) ?></li>
            <?php endforeach ?>
         </ul>
      </div>
   <?php endif ?>

   <div class="bg-white rounded-lg shadow p-6">
      <form action="<?= site_url('peminjam/peminjaman/create') ?>" method="post">
         <?= csrf_field() ?>

         <!-- Informasi Kegiatan -->
         <div class="space-y-5 mb-6">

            <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan</label>
               <input type="text" name="kegiatan" value="<?= old('kegiatan') ?>" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Workshop Teknik Sipil">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                  <input type="date" name="tgl_pinjam_dimulai" value="<?= old('tgl_pinjam_dimulai') ?>" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500">
               </div>

               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                  <input type="date" name="tgl_pinjam_selesai" value="<?= old('tgl_pinjam_selesai') ?>" class="w-full border border-gray-300 px-2 py-2  rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500">
               </div>
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
               <textarea name="keterangan" rows="2" class="w-full border border-gray-300 px-2 py-2  rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500"><?= old('keterangan') ?></textarea>
            </div>
         </div>

         <hr class="my-6">

         <!-- Daftar Sarana -->
         <h3 class="text-lg font-semibold text-gray-800 mb-3">Daftar Sarana yang Dipinjam</h3>

         <div class="overflow-x-auto mb-4 rounded-lg border border-gray-200">
            <table class="w-full text-left border-collapse">
               <thead class="bg-gray-50">
                  <tr class="border-b">
                     <th class="p-3 text-sm font-medium text-gray-700">Nama Sarana</th>
                     <th class="p-3 text-sm font-medium text-gray-700 w-32">Jumlah Tersedia</th>
                     <th class="p-3 text-sm font-medium text-gray-700 w-32">Jumlah Dipinjam</th>
                     <th class="p-3 text-sm font-medium text-gray-700 w-20">Aksi</th>
                  </tr>
               </thead>

               <tbody id="itemTableBody">
                  <tr class="border-b">
                     <td class="p-3">
                        <select name="items[sarana][]" class="w-full border-gray-300 rounded-md shadow-md">
                           <option value="">-- Pilih Sarana --</option>
                           <?php foreach ($sarana as $item) : ?>
                              <option value="<?= $item['id_sarana'] ?>" data-stok="<?= $item['jumlah'] ?>">
                                 <?= esc($item['nama_sarana']) ?>
                              </option>
                           <?php endforeach; ?>
                        </select>
                     </td>

                     <td class="p-3">
                        <input type="number" class="stok-field w-full border-gray-300 rounded-md bg-gray-100 text-gray-700" disabled value="0">
                     </td>

                     <td class="p-3">
                        <input type="number" name="items[jumlah][]" min="1" value="1" class="w-full border-gray-300 rounded-md shadow-md">
                     </td>

                     <td class="p-3 text-center">
                        <button type="button" class="text-red-500 hover:text-red-700" disabled>
                           <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                           </svg>
                        </button>
                     </td>
                  </tr>

               </tbody>
            </table>

            <button type="button" id="addItemBtn" class="px-2 py-2 mb-6 text-sm text-blue-600 font-medium hover:underline flex items-center">
               + Tambah Sarana
            </button>
         </div>

         <!-- TABLE PRASARANA -->
         <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Prasarana</h3>
         <div class="overflow-x-auto mb-4  rounded-lg border border-gray-200">
            <div class="overflow-x-auto mb-4">
               <table class="w-full text-left border-collapse">
                  <thead>
                     <tr class="bg-gray-50 border-b">
                        <th class="p-3 text-sm font-medium text-gray-600">Nama Prasarana</th>
                        <th class="p-3 text-sm font-medium text-gray-600 w-20">Aksi</th>
                     </tr>
                  </thead>
                  <tbody id="prasaranaTableBody">
                     <tr>
                        <td class="p-2">
                           <select name="items[prasarana][]" class="w-full border-gray-300 rounded-md shadow-md">
                              <option value="">-- Pilih Prasarana --</option>
                              <?php foreach ($prasarana as $p) : ?>
                                 <option value="<?= $p['id_prasarana'] ?>"><?= esc($p['nama_prasarana']) ?> (Kapasitas: <?= $p['kapasitas_orang'] ?>)</option>
                              <?php endforeach; ?>
                           </select>
                        </td>
                        <td class="p-2 text-center">
                           <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700">
                              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                              </svg>
                           </button>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <button type="button" id="addPrasaranaBtn" class="text-sm text-blue-600 font-medium hover:underline">+ Tambah Prasarana</button>
         </div>

         <div class="flex justify-end gap-3">
            <a href="<?= site_url('peminjam/histori-peminjaman') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-medium">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium shadow">Ajukan</button>
         </div>

      </form>
   </div>
</div>

<script>
   document.getElementById('addItemBtn').addEventListener('click', function() {
      const tableBody = document.getElementById('itemTableBody');
      const firstRow = tableBody.rows[0];
      const newRow = firstRow.cloneNode(true);

      newRow.querySelectorAll('input').forEach(i => i.value = 1);
      newRow.querySelectorAll('select').forEach(s => s.value = '');

      const deleteBtn = newRow.querySelector('button');
      deleteBtn.disabled = false;

      deleteBtn.addEventListener('click', function() {
         newRow.remove();
      });

      tableBody.appendChild(newRow);
   });

   document.addEventListener('change', function(e) {
      if (e.target.matches('select[name="items[sarana][]"]')) {
         const select = e.target;
         const stok = select.selectedOptions[0].dataset.stok || 0;

         const row = select.closest('tr');
         const stokField = row.querySelector('.stok-field');

         stokField.value = stok;
      }
   });

   document.getElementById('addPrasaranaBtn').addEventListener('click', function() {
      const table = document.getElementById('prasaranaTableBody');
      const row = table.rows[0].cloneNode(true);
      row.querySelector('select').value = '';
      table.appendChild(row);
   });
</script>

<?= $this->endSection(); ?>