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
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg shadow-sm animate-pulse">
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

   <form action="<?= site_url('peminjam/peminjaman/create') ?>" method="post" id="loanForm">
      <?= csrf_field() ?>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

         <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
               <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                  <h3 class="text-lg font-semibold text-gray-900">Detail Kegiatan</h3>
                  <p class="text-xs text-gray-500 mt-1">Informasi umum mengenai acara.</p>
               </div>

               <div class="p-6 space-y-5">
                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kegiatan <span class="text-red-500">*</span></label>
                     <input type="text" name="kegiatan" value="<?= old('kegiatan') ?>" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="Contoh: Workshop Teknik Sipil" required>
                  </div>

                  <div class="space-y-4">
                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" id="tgl_mulai" name="tgl_pinjam_dimulai" min="<?= date('Y-m-d') ?>" value="<?= old('tgl_pinjam_dimulai') ?>" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
                     </div>

                     <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" id="tgl_selesai" name="tgl_pinjam_selesai" min="<?= date('Y-m-d') ?>" value="<?= old('tgl_pinjam_selesai') ?>" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>

                        <div id="durasi_info_box" class="hidden mt-2 p-2 bg-blue-50 rounded border border-blue-100">
                           <p class="text-xs text-blue-700 font-medium flex items-center">
                              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                              </svg>
                              <span id="text_durasi"></span>
                           </p>
                        </div>
                     </div>
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan</label>
                     <textarea name="keterangan" rows="4" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Opsional..."><?= old('keterangan') ?></textarea>
                  </div>

                  <div class="hidden lg:block pt-4">
                     <button type="submit" id="submitBtnDesktop" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all transform hover:scale-[1.02]">
                        Ajukan Peminjaman
                     </button>
                  </div>
               </div>
            </div>
         </div>

         <div class="lg:col-span-2 space-y-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
               <div class="px-6 py-4 border-b border-gray-100 bg-blue-50 flex justify-between items-center">
                  <div>
                     <h3 class="text-lg font-semibold text-blue-900">Daftar Sarana (Barang)</h3>
                     <p class="text-xs text-blue-600 mt-0.5">Pilih barang yang akan dipinjam.</p>
                  </div>
                  <button type="button" id="addSaranaBtn" class="inline-flex items-center px-3 py-1.5 border border-blue-200 text-xs font-medium rounded text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors">
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
                           <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-1/2">Nama Sarana</th>
                           <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Stok</th>
                           <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Jumlah</th>
                           <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16"></th>
                        </tr>
                     </thead>
                     <tbody id="saranaTableBody" class="bg-white divide-y divide-gray-200">
                        <tr class="sarana-row">
                           <td class="px-6 py-4">
                              <select name="items[sarana][]" class="sarana-select w-full border border-gray-300 px-2 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                 <option value="">-- Pilih Sarana --</option>
                                 <?php foreach ($sarana as $item) : ?>
                                    <option value="<?= $item['id_sarana'] ?>" data-stok="<?= $item['jumlah'] ?>">
                                       <?= esc($item['nama_sarana']) ?>
                                    </option>
                                 <?php endforeach; ?>
                              </select>
                           </td>
                           <td class="px-6 py-4 text-center">
                              <input type="number" class="stok-field w-20 text-center border-gray-200 rounded-md bg-gray-100 text-gray-500 text-sm" disabled value="0">
                           </td>
                           <td class="px-6 py-4 text-center">
                              <input type="number" name="items[jumlah][]" min="1" value="1" class="w-full border border-gray-300 px-2 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                           </td>
                           <td class="px-6 py-4 text-center">
                              <button type="button" class="delete-row-btn text-gray-300 hover:text-red-600 transition-colors" disabled>
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
                           <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase w-full">Nama Prasarana</th>
                           <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">Aksi</th>
                        </tr>
                     </thead>
                     <tbody id="prasaranaTableBody" class="bg-white divide-y divide-gray-200">
                        <tr class="prasarana-row">
                           <td class="px-6 py-4">
                              <div class="relative">
                                 <select name="items[prasarana][]" class="prasarana-select w-full border border-gray-300 px-2 py-2 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500 transition-colors" onchange="checkAvailability(this)">
                                    <option value="">-- Pilih Prasarana --</option>
                                    <?php foreach ($prasarana as $p) : ?>
                                       <option value="<?= $p['id_prasarana'] ?>"><?= esc($p['nama_prasarana']) ?> (Kap: <?= $p['kapasitas_orang'] ?>)</option>
                                    <?php endforeach; ?>
                                 </select>
                                 <p class="availability-msg text-xs mt-1 font-medium hidden animate-pulse"></p>
                              </div>
                           </td>
                           <td class="px-6 py-4 text-center">
                              <button type="button" onclick="removePrasaranaRow(this)" class="text-gray-400 hover:text-red-600 transition-colors">
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

            <div class="block lg:hidden mt-8 pb-8">
               <button type="submit" id="submitBtnMobile" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  Ajukan Peminjaman
               </button>
            </div>

         </div>
      </div>
   </form>
</div>

<script>
   // ==========================================
   // 1. LOGIKA TANGGAL & DURASI
   // ==========================================
   const inputStart = document.getElementById('tgl_mulai');
   const inputEnd = document.getElementById('tgl_selesai');
   const durasiBox = document.getElementById('durasi_info_box');
   const durasiText = document.getElementById('text_durasi');

   // Set Max Booking Window (2 Bulan)
   const maxBookingDate = new Date();
   maxBookingDate.setMonth(maxBookingDate.getMonth() + 2);
   const maxString = maxBookingDate.toISOString().split('T')[0];

   inputStart.setAttribute('max', maxString);
   inputEnd.setAttribute('max', maxString);

   function updateDurationInfo() {
      if (inputStart.value && inputEnd.value) {
         const start = new Date(inputStart.value);
         const end = new Date(inputEnd.value);

         // Validasi Frontend Sederhana
         if (start > end) {
            durasiBox.classList.remove('hidden', 'bg-blue-50', 'border-blue-100');
            durasiBox.classList.add('bg-red-50', 'border-red-100');
            durasiText.innerHTML = '<span class="text-red-600">Tanggal Selesai tidak boleh lebih awal!</span>';
            durasiText.className = "text-xs font-bold";
            return;
         }

         const diffTime = Math.abs(end - start);
         const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

         let pesan = `Durasi Peminjaman: <b>${diffDays} Hari</b>`;
         let style = "text-blue-700";
         let bg = "bg-blue-50";
         let border = "border-blue-100";

         // Warning Batas Waktu
         if (diffDays > 30) {
            pesan += "<br><span class='text-red-600'>⚠️ Melebihi batas maksimal Prasarana (30 Hari)</span>";
            style = "text-red-700";
            bg = "bg-red-50";
            border = "border-red-100";
         } else if (diffDays > 3) {
            pesan += "<br><span class='text-yellow-700'>⚠️ Perhatian: Batas maksimal Sarana adalah 3 Hari</span>";
            style = "text-yellow-700";
            bg = "bg-yellow-50";
            border = "border-yellow-100";
         }

         durasiText.innerHTML = pesan;
         durasiText.className = "text-xs " + style;

         durasiBox.className = `mt-2 p-2 rounded border ${bg} ${border}`;
         durasiBox.classList.remove('hidden');

         // Trigger cek ulang ketersediaan prasarana karena tanggal berubah
         triggerAllPrasaranaChecks();
      } else {
         durasiBox.classList.add('hidden');
      }
   }

   inputStart.addEventListener('change', function() {
      if (this.value) {
         // Auto-adjust tanggal selesai jika kurang dari tanggal mulai
         if (inputEnd.value && inputEnd.value < this.value) {
            inputEnd.value = this.value;
         }
         inputEnd.setAttribute('min', this.value);
         updateDurationInfo();
      }
   });

   inputEnd.addEventListener('change', updateDurationInfo);


   // ==========================================
   // 2. LOGIKA NAMBAH BARIS SARANA
   // ==========================================
   document.getElementById('addSaranaBtn').addEventListener('click', function() {
      const tableBody = document.getElementById('saranaTableBody');
      const firstRow = tableBody.querySelector('tr'); // Ambil row pertama sebagai template
      const newRow = firstRow.cloneNode(true);

      // Reset nilai input di baris baru
      newRow.querySelectorAll('select').forEach(s => s.value = '');
      newRow.querySelectorAll('.stok-field').forEach(i => i.value = 0);
      newRow.querySelectorAll('input[type="number"]:not(.stok-field)').forEach(i => i.value = 1);

      // Aktifkan tombol hapus
      const deleteBtn = newRow.querySelector('.delete-row-btn');
      deleteBtn.disabled = false;
      deleteBtn.classList.remove('text-gray-300');
      deleteBtn.classList.add('text-red-500', 'hover:text-red-700');

      deleteBtn.onclick = function() {
         newRow.remove();
      };

      tableBody.appendChild(newRow);
   });

   // Event Delegation untuk Change Select Sarana (Update Stok)
   document.getElementById('saranaTableBody').addEventListener('change', function(e) {
      if (e.target.classList.contains('sarana-select')) {
         const select = e.target;
         const selectedOption = select.options[select.selectedIndex];
         const stok = selectedOption.getAttribute('data-stok') || 0;

         const row = select.closest('tr');
         row.querySelector('.stok-field').value = stok;
      }
   });


   // ==========================================
   // 3. LOGIKA NAMBAH BARIS PRASARANA & AJAX CHECK
   // ==========================================

   document.getElementById('addPrasaranaBtn').addEventListener('click', function() {
      const tableBody = document.getElementById('prasaranaTableBody');
      const newRow = tableBody.rows[0].cloneNode(true);

      // Reset
      const select = newRow.querySelector('select');
      select.value = '';
      select.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'border-green-500', 'bg-green-50');

      const msg = newRow.querySelector('.availability-msg');
      msg.innerHTML = '';
      msg.classList.add('hidden');

      tableBody.appendChild(newRow);
   });

   function removePrasaranaRow(btn) {
      const row = btn.closest('tr');
      if (document.querySelectorAll('#prasaranaTableBody tr').length > 1) {
         row.remove();
      } else {
         // Jika sisa 1, reset saja jangan hapus
         const select = row.querySelector('select');
         select.value = '';
         select.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'border-green-500', 'bg-green-50');
         row.querySelector('.availability-msg').classList.add('hidden');
      }
      // Cek ulang status tombol submit
      validateSubmitButton();
   }

   function triggerAllPrasaranaChecks() {
      const selects = document.querySelectorAll('.prasarana-select');
      selects.forEach(select => {
         if (select.value) checkAvailability(select);
      });
   }

   async function checkAvailability(selectElement) {
      const idPrasarana = selectElement.value;
      const startDate = inputStart.value;
      const endDate = inputEnd.value;

      const msgElement = selectElement.parentElement.querySelector('.availability-msg');
      const submitBtns = document.querySelectorAll('button[type="submit"]'); // Ambil semua tombol submit (Desktop & Mobile)

      // Reset UI State
      selectElement.classList.remove('border-red-500', 'text-red-900', 'bg-red-50', 'border-green-500', 'bg-green-50');
      msgElement.classList.add('hidden');
      msgElement.innerHTML = '';

      if (!idPrasarana) return;

      if (!startDate || !endDate) {
         msgElement.innerHTML = '<span class="text-yellow-600">Pilih tanggal dulu.</span>';
         msgElement.classList.remove('hidden');
         return;
      }

      // Loading State
      msgElement.classList.remove('hidden');
      msgElement.innerHTML = '<span class="text-gray-400 flex items-center"><svg class="animate-spin h-3 w-3 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Cek jadwal...</span>';

      try {
         // FIX: Tambahkan header X-Requested-With agar terdeteksi sebagai AJAX di CI4
         const response = await fetch(`<?= site_url('peminjam/api/check-prasarana/') ?>${idPrasarana}?start=${startDate}&end=${endDate}`, {
            headers: {
               "X-Requested-With": "XMLHttpRequest"
            }
         });

         if (!response.ok) throw new Error('Response not OK');

         const data = await response.json();

         if (data.status === 'booked') {
            // BENTROK
            selectElement.classList.add('border-red-500', 'text-red-900', 'bg-red-50');
            msgElement.innerHTML = `<span class="text-red-600 flex items-start gap-1"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> ${data.message}</span>`;
            selectElement.dataset.valid = "false";
         } else {
            // AMAN
            selectElement.classList.add('border-green-500', 'bg-green-50');
            msgElement.innerHTML = `<span class="text-green-600 flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Ruangan tersedia.</span>`;
            selectElement.dataset.valid = "true";
         }
      } catch (error) {
         console.error('Error:', error);
         msgElement.innerHTML = '<span class="text-red-500">Gagal cek jadwal (Server Error).</span>';
      }

      validateSubmitButton();
   }

   function validateSubmitButton() {
      const submitBtns = [document.getElementById('submitBtnDesktop'), document.getElementById('submitBtnMobile')];
      const invalidSelects = document.querySelectorAll('.prasarana-select[data-valid="false"]');

      const isBlocked = invalidSelects.length > 0;

      submitBtns.forEach(btn => {
         if (btn) {
            btn.disabled = isBlocked;
            if (isBlocked) {
               btn.classList.add('opacity-50', 'cursor-not-allowed');
               btn.innerText = "Jadwal Bentrok - Perbaiki Dulu";
            } else {
               btn.classList.remove('opacity-50', 'cursor-not-allowed');
               btn.innerText = "Ajukan Peminjaman";
            }
         }
      });
   }
</script>

<?= $this->endSection(); ?>