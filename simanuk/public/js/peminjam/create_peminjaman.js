document.addEventListener("DOMContentLoaded", function () {
   // --- 1. LOGIKA TANGGAL & DURASI ---
   const inputStart = document.getElementById('tgl_mulai');
   const inputEnd = document.getElementById('tgl_selesai');
   const durasiBox = document.getElementById('durasi_info_box');
   const durasiText = document.getElementById('text_durasi');

   // Set Max Booking Window (2 Bulan)
   if (inputStart && inputEnd) {
      const maxBookingDate = new Date();
      maxBookingDate.setMonth(maxBookingDate.getMonth() + 2);
      const maxString = maxBookingDate.toISOString().split('T')[0];

      inputStart.setAttribute('max', maxString);
      inputEnd.setAttribute('max', maxString);
   }

   function updateDurationInfo() {
      if (inputStart.value && inputEnd.value) {
         const start = new Date(inputStart.value);
         const end = new Date(inputEnd.value);

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

         if (diffDays > 60) {
            pesan += "<br><span class='text-red-600'>⚠️ Melebihi batas maksimal Prasarana (60 hari)</span>";
            style = "text-red-700";
            bg = "bg-red-50";
            border = "border-red-100";
         } else if (diffDays > 30) {
            pesan += "<br><span class='text-yellow-700'>⚠️ Perhatian: Batas maksimal Sarana adalah 30 Hari</span>";
            style = "text-yellow-700";
            bg = "bg-yellow-50";
            border = "border-yellow-100";
         }

         durasiText.innerHTML = pesan;
         durasiText.className = "text-xs " + style;

         durasiBox.className = `mt-2 p-2 rounded border ${bg} ${border}`;
         durasiBox.classList.remove('hidden');

         triggerAllPrasaranaChecks();
      } else {
         durasiBox.classList.add('hidden');
      }
   }

   if (inputStart) {
      inputStart.addEventListener('change', function () {
         if (this.value) {
            if (inputEnd.value && inputEnd.value < this.value) {
               inputEnd.value = this.value;
            }
            inputEnd.setAttribute('min', this.value);
            updateDurationInfo();
         }
      });
   }

   if (inputEnd) {
      inputEnd.addEventListener('change', updateDurationInfo);
   }

   // --- 2. LOGIKA TAMBAH BARIS SARANA ---
   const addSaranaBtn = document.getElementById('addSaranaBtn');
   if (addSaranaBtn) {
      addSaranaBtn.addEventListener('click', function () {
         const tableBody = document.getElementById('saranaTableBody');
         const firstRow = tableBody.querySelector('tr');
         const newRow = firstRow.cloneNode(true);

         newRow.querySelectorAll('select').forEach(s => s.value = '');
         newRow.querySelectorAll('.stok-field').forEach(i => i.value = 0);
         newRow.querySelectorAll('input[type="number"]:not(.stok-field)').forEach(i => i.value = 1);

         const deleteBtn = newRow.querySelector('.delete-row-btn');
         deleteBtn.disabled = false;
         deleteBtn.classList.remove('text-gray-300');
         deleteBtn.classList.add('text-red-500', 'hover:text-red-700');

         deleteBtn.onclick = function () {
            newRow.remove();
         };

         tableBody.appendChild(newRow);
      });
   }

   const saranaTableBody = document.getElementById('saranaTableBody');
   if (saranaTableBody) {
      saranaTableBody.addEventListener('change', function (e) {
         if (e.target.classList.contains('sarana-select')) {
            const select = e.target;
            const selectedOption = select.options[select.selectedIndex];
            const stok = selectedOption.getAttribute('data-stok') || 0;
            const row = select.closest('tr');
            row.querySelector('.stok-field').value = stok;
         }
      });
   }

   // --- 3. LOGIKA TAMBAH BARIS PRASARANA ---
   const addPrasaranaBtn = document.getElementById('addPrasaranaBtn');
   if (addPrasaranaBtn) {
      addPrasaranaBtn.addEventListener('click', function () {
         const tableBody = document.getElementById('prasaranaTableBody');
         const newRow = tableBody.rows[0].cloneNode(true);

         const select = newRow.querySelector('select');
         select.value = '';
         select.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'border-green-500', 'bg-green-50');
         // Penting: Pasang ulang event listener onchange atau gunakan event delegation di HTML (onchange="checkAvailability(this)")
         // Karena di HTML sudah ada onchange="checkAvailability(this)", kita tidak perlu addEventListener manual di sini.

         const msg = newRow.querySelector('.availability-msg');
         msg.innerHTML = '';
         msg.classList.add('hidden');

         tableBody.appendChild(newRow);
      });
   }
});

// --- FUNGSI GLOBAL (Diakses oleh onclick/onchange di HTML) ---

function removePrasaranaRow(btn) {
   const row = btn.closest('tr');
   if (document.querySelectorAll('#prasaranaTableBody tr').length > 1) {
      row.remove();
   } else {
      const select = row.querySelector('select');
      select.value = '';
      select.classList.remove('border-red-500', 'bg-red-50', 'text-red-900', 'border-green-500', 'bg-green-50');
      row.querySelector('.availability-msg').classList.add('hidden');
   }
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
   const startDate = document.getElementById('tgl_mulai').value;
   const endDate = document.getElementById('tgl_selesai').value;

   const msgElement = selectElement.parentElement.querySelector('.availability-msg');

   // Reset UI
   selectElement.classList.remove('border-red-500', 'text-red-900', 'bg-red-50', 'border-green-500', 'bg-green-50');
   msgElement.classList.add('hidden');
   msgElement.innerHTML = '';

   if (!idPrasarana) return;

   if (!startDate || !endDate) {
      msgElement.innerHTML = '<span class="text-yellow-600">Pilih tanggal dulu.</span>';
      msgElement.classList.remove('hidden');
      return;
   }

   // Loading
   msgElement.classList.remove('hidden');
   msgElement.innerHTML = '<span class="text-gray-400 flex items-center"><svg class="animate-spin h-3 w-3 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Cek jadwal...</span>';

   try {
      // Menggunakan variabel global SITE_URL dari View
      const response = await fetch(`${SITE_URL}peminjam/api/check-prasarana/${idPrasarana}?start=${startDate}&end=${endDate}`, {
         headers: { "X-Requested-With": "XMLHttpRequest" }
      });

      if (!response.ok) throw new Error('Response not OK');
      const data = await response.json();

      if (data.status === 'booked') {
         selectElement.classList.add('border-red-500', 'text-red-900', 'bg-red-50');
         msgElement.innerHTML = `<span class="text-red-600 flex items-start gap-1"><svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> ${data.message}</span>`;
         selectElement.dataset.valid = "false";
      } else {
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