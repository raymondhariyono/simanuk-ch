<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

   <div class="mb-8">
      <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Form Pengajuan Peminjaman</h1>
      <p class="mt-2 text-sm text-gray-500">
         Silakan lengkapi detail kegiatan dan pilih aset yang dibutuhkan.
      </p>
   </div>

   <?php if (isset($breadcrumbs)) : ?>
      <div class="mb-6">
         <?= render_breadcrumb($breadcrumbs); ?>
      </div>
   <?php endif; ?>

   <?php if (session()->has('error') || session()->has('errors')) : ?>
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm">
         <div class="flex">
            <div class="flex-shrink-0">
               <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
               </svg>
            </div>
            <div class="ml-3">
               <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada isian Anda</h3>
               <?php if (session()->has('errors')) : ?>
                  <div class="mt-2 text-sm text-red-700">
                     <ul class="list-disc pl-5 space-y-1">
                        <?php foreach (session('errors') as $error) : ?>
                           <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                     </ul>
                  </div>
               <?php else: ?>
                  <p class="mt-1 text-sm text-red-700"><?= session('error') ?></p>
               <?php endif; ?>
            </div>
         </div>
      </div>
   <?php endif ?>

   <form action="<?= site_url('peminjam/peminjaman/create') ?>" method="post">
      <?= csrf_field() ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

         <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
               <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                  <h3 class="text-lg font-semibold text-gray-900">Detail Kegiatan</h3>
                  <p class="text-xs text-gray-500 mt-1">Informasi umum mengenai acara.</p>
               </div>

               <div class="p-6 space-y-5">
                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                     <input type="text" name="kegiatan" value="<?= old('kegiatan') ?>" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Workshop Teknik Sipil" required>
                  </div>

                  <div class="space-y-4">
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" id="tgl_mulai" name="tgl_pinjam_dimulai" min="<?= date('Y-m-d') ?>" value="<?= old('tgl_pinjam_dimulai') ?>" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500" required>
                     </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" id="tgl_selesai" name="tgl_pinjam_selesai" min="<?= date('Y-m-d') ?>" value="<?= old('tgl_pinjam_selesai') ?>" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500" required>
                        <p id="durasi_info" class="text-xs text-gray-500 mt-2 font-medium flex items-center">
                           <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                           </svg>
                           <span id="text_durasi">Pilih tanggal untuk estimasi durasi</span>
                        </p>
                     </div>
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                     <textarea name="keterangan" rows="4" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional..."><?= old('keterangan') ?></textarea>
                  </div>
               </div>
            </div>

            <div class="hidden lg:block">
               <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                  Ajukan Peminjaman
               </button>
            </div>
         </div>

         <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
               <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex justify-between items-center">
                  <div>
                     <h3 class="text-lg font-semibold text-blue-900">Daftar Sarana (Barang)</h3>
                     <p class="text-xs text-blue-600 mt-0.5">Pilih barang yang akan dipinjam.</p>
                  </div>
                  <button type="button" id="addItemBtn" class="inline-flex items-center px-3 py-1.5 border border-blue-200 text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
                     <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                     </svg>
                     Tambah Baris
                  </button>
               </div>

               <div class="p-0">
                  <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                        <tr>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Nama Sarana</th>
                           <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Stok</th>
                           <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Jumlah</th>
                           <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16"></th>
                        </tr>
                     </thead>
                     <tbody id="itemTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                           <td class="px-6 py-4 whitespace-nowrap">
                              <select name="items[sarana][]" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500">
                                 <option value="">-- Pilih Sarana --</option>
                                 <?php foreach ($sarana as $item) : ?>
                                    <option value="<?= $item['id_sarana'] ?>" data-stok="<?= $item['jumlah'] ?>">
                                       <?= esc($item['nama_sarana']) ?>
                                    </option>
                                 <?php endforeach; ?>
                              </select>
                           </td>
                           <td class="px-6 py-4 whitespace-nowrap text-center">
                              <input type="number" class="stok-field w-20 text-center border-gray-200 rounded-md bg-gray-50 text-gray-500 text-sm" disabled value="0">
                           </td>
                           <td class="px-6 py-4 whitespace-nowrap text-center">
                              <input type="number" name="items[jumlah][]" min="1" value="1" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500">
                           </td>
                           <td class="px-6 py-4 whitespace-nowrap text-center">
                              <button type="button" class="text-gray-300 hover:text-red-600 transition-colors" disabled>
                                 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                 </svg>
                              </button>
                           </td>
                        </tr>
                     </tbody>
                  </table>
                  <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 text-xs text-gray-500 text-center italic">
                     Pastikan jumlah tidak melebihi stok tersedia.
                  </div>
               </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
               <div class="px-6 py-4 border-b border-gray-100 bg-purple-50 flex justify-between items-center">
                  <div>
                     <h3 class="text-lg font-semibold text-purple-900">Daftar Prasarana (Ruangan)</h3>
                     <p class="text-xs text-purple-600 mt-0.5">Pilih ruangan/gedung yang diperlukan.</p>
                  </div>
                  <button type="button" id="addPrasaranaBtn" class="inline-flex items-center px-3 py-1.5 border border-purple-200 text-xs font-medium rounded text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm transition-colors">
                     <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                     </svg>
                     Tambah Baris
                  </button>
               </div>

               <div class="p-0">
                  <table class="min-w-full divide-y divide-gray-200">
                     <thead class="bg-gray-50">
                        <tr>
                           <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-full">Nama Prasarana</th>
                           <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-16"></th>
                        </tr>
                     </thead>
                     <tbody id="prasaranaTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                           <td class="px-6 py-4 whitespace-nowrap">
                              <select name="items[prasarana][]" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500">
                                 <option value="">-- Pilih Prasarana --</option>
                                 <?php foreach ($prasarana as $p) : ?>
                                    <option value="<?= $p['id_prasarana'] ?>"><?= esc($p['nama_prasarana']) ?> (Kapasitas: <?= $p['kapasitas_orang'] ?> orang)</option>
                                 <?php endforeach; ?>
                              </select>
                           </td>
                           <td class="px-6 py-4 whitespace-nowrap text-center">
                              <button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-600 transition-colors">
                                 <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                 </svg>
                              </button>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>

            <div class="block lg:hidden mt-8">
               <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Ajukan Peminjaman
               </button>
            </div>

         </div>
      </div>
   </form>
</div>

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
                  <input type="date"
                     id="tgl_mulai"
                     name="tgl_pinjam_dimulai"
                     min="<?= date('Y-m-d') ?>"
                     value="<?= old('tgl_pinjam_dimulai') ?>"
                     class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500"
                     required>
               </div>

               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                  <input type="date"
                     id="tgl_selesai"
                     name="tgl_pinjam_selesai"
                     min="<?= date('Y-m-d') ?>"
                     value="<?= old('tgl_pinjam_selesai') ?>"
                     class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-md focus:ring-blue-500 focus:border-blue-500"
                     required>
                  <p id="durasi_info" class="text-xs text-gray-500 mt-1"></p>
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

   document.addEventListener("DOMContentLoaded", function() {
      const tglMulai = document.getElementById('tgl_mulai');
      const tglSelesai = document.getElementById('tgl_selesai');
      const durasiInfo = document.getElementById('durasi_info');

      const durasiTextSpan = document.getElementById('text_durasi');

      // Batas Booking Window (2 Bulan dari hari ini, sesuai logika PHP)
      // PHP: $maxAdvanceDate = date('Y-m-d', strtotime('+2 months'));
      const maxBookingDate = new Date();
      maxBookingDate.setMonth(maxBookingDate.getMonth() + 2);
      const maxString = maxBookingDate.toISOString().split('T')[0];

      // Set atribut max awal untuk kedua input
      tglMulai.setAttribute('max', maxString);
      tglSelesai.setAttribute('max', maxString);

      // Event Listener saat Tanggal Mulai berubah
      tglMulai.addEventListener('change', function() {
         const startDateVal = this.value;

         if (startDateVal) {
            // 1. Reset Tanggal Selesai jika lebih kecil dari Tanggal Mulai baru
            if (tglSelesai.value && tglSelesai.value < startDateVal) {
               tglSelesai.value = startDateVal;
            }

            // 2. Set atribut MIN pada Tanggal Selesai agar tidak bisa pilih tanggal sebelum Mulai
            tglSelesai.setAttribute('min', startDateVal);

            // 3. (Opsional) Hitung estimasi durasi realtime
            checkDuration();
         }
      });

      tglSelesai.addEventListener('change', checkDuration);

      function checkDuration() {
         if (tglMulai.value && tglSelesai.value) {
            const start = new Date(tglMulai.value);
            const end = new Date(tglSelesai.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

            let pesan = "Durasi: " + diffDays + " Hari.";
            let warna = "text-gray-500";

            // Peringatan Visual Sederhana (Validasi keras tetap di server)
            // Kita tidak tahu user pinjam Sarana atau Prasarana saat ini (karena dinamis),
            // jadi kita beri info umum saja atau warning jika > 31 hari.
            if (diffDays > 60) {
               pesan += " (Melebihi batas maksimal Prasarana 2 bulan)";
               warna = "text-red-500 font-bold";
            } else if (diffDays > 3) {
               pesan += " (Perhatikan: Batas maksimal Sarana adalah 1 bulan dan Prasarana 2 bulan)";
               warna = "text-yellow-600";
            }

            durasiInfo.innerText = pesan;
            durasiInfo.className = "text-xs mt-1 " + warna;
         }
      }
   });
</script>

<?= $this->endSection(); ?>