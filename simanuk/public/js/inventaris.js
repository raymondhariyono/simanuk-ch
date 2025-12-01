document.addEventListener('DOMContentLoaded', function () {
   // --- LOGIKA TABS INVENTARIS ---
   const tabButtons = document.querySelectorAll('.tab-btn');

   if (tabButtons.length > 0) {
      function switchTab(targetId) {
         // 1. Sembunyikan semua konten tab
         document.querySelectorAll('[role="tabpanel"]').forEach(panel => {
            panel.classList.add('hidden');
         });

         // 2. Tampilkan konten yang dipilih
         const targetPanel = document.getElementById(targetId);
         if (targetPanel) {
            targetPanel.classList.remove('hidden');
         }

         // 3. Update style tombol tab
         tabButtons.forEach(btn => {
            const isSelected = btn.getAttribute('data-target') === targetId;
            btn.setAttribute('aria-selected', isSelected);

            if (isSelected) {
               btn.classList.add('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
               btn.classList.remove('border-transparent');
            } else {
               btn.classList.remove('text-blue-600', 'border-blue-600', 'dark:text-blue-500', 'dark:border-blue-500');
               btn.classList.add('border-transparent'); // Agar tidak ada garis bawah
            }
         });
      }

      // Event Listener Click
      tabButtons.forEach(button => {
         button.addEventListener('click', function () {
            const target = this.getAttribute('data-target');
            switchTab(target);
         });
      });

      // Initial State (Cek mana yang aria-selected="true" dari PHP)
      const activeBtn = document.querySelector('.tab-btn[aria-selected="true"]');
      if (activeBtn) {
         switchTab(activeBtn.getAttribute('data-target'));
      } else {
         // Fallback ke tab pertama
         switchTab('sarana-content');
      }
   }


   // --- Script untuk Spesifikasi (Sarana) ---
   const tambahSpesifikasiBtn = document.getElementById('tambah-spesifikasi');
   if (tambahSpesifikasiBtn) {
      tambahSpesifikasiBtn.addEventListener('click', function () {
         const container = document.getElementById('spesifikasi-container');
         const newRow = document.createElement('div');
         newRow.className = 'flex items-center gap-4';
         newRow.innerHTML = `
            <input type="text" name="spesifikasi_key[]" placeholder="Nama Spesifikasi" class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700">
            <input type="text" name="spesifikasi_value[]" placeholder="Nilai Spesifikasi" class="shadow appearance-none border rounded w-2/3 py-2 px-3 text-gray-700">
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">Hapus</button>
         `;
         container.appendChild(newRow);
      });
   }

   // --- Script untuk Fasilitas (Prasarana) ---
   const tambahFasilitasBtn = document.getElementById('tambah-fasilitas');
   if (tambahFasilitasBtn) {
      tambahFasilitasBtn.addEventListener('click', function () {
         const container = document.getElementById('fasilitas-container');
         const newRow = document.createElement('div');
         newRow.className = 'flex items-center gap-4';
         newRow.innerHTML = `
            <input type="text" name="fasilitas[]" placeholder="Nama Fasilitas" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
            <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">Hapus</button>
         `;
         container.appendChild(newRow);
      });
   }

   // --- Script untuk Pratinjau Upload Foto ---
   const dropzoneInput = document.getElementById('dropzone-file');
   const previewContainer = document.getElementById('image-preview-container');

   // Hanya jalankan skrip jika elemen yang diperlukan ada
   if (dropzoneInput && previewContainer) {
      const dropzoneLabel = document.querySelector('label[for="dropzone-file"]');
      let fileStore = []; // Gunakan array untuk menyimpan file yang valid

      // Fungsi untuk me-render pratinjau
      function renderPreviews() {
         previewContainer.innerHTML = '';

         if (fileStore.length > 0) {
            dropzoneLabel.classList.add('hidden');
         } else {
            dropzoneLabel.classList.remove('hidden');
         }

         fileStore.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
               const previewWrapper = document.createElement('div');
               previewWrapper.className = 'relative border rounded-lg overflow-hidden';
               previewWrapper.innerHTML = `
                  <img src="${e.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                  <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">${file.name}</div>
                  <button type="button" data-index="${index}" class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold hover:bg-red-700">&times;</button>
               `;
               previewContainer.appendChild(previewWrapper);
            };
            reader.readAsDataURL(file);
         });
      }

      // Event listener untuk input file
      dropzoneInput.addEventListener('change', function (event) {
         // Ganti fileStore dengan file yang baru dipilih
         fileStore = Array.from(event.target.files);
         renderPreviews();
      });

      // Event listener untuk tombol hapus (menggunakan event delegation)
      previewContainer.addEventListener('click', function (event) {
         if (event.target.matches('button[data-index]')) {
            const indexToRemove = parseInt(event.target.getAttribute('data-index'), 10);

            // Hapus file dari fileStore
            fileStore.splice(indexToRemove, 1);

            // Buat objek DataTransfer baru untuk memperbarui input file
            const dataTransfer = new DataTransfer();
            fileStore.forEach(file => dataTransfer.items.add(file));
            dropzoneInput.files = dataTransfer.files;

            renderPreviews(); // Render ulang pratinjau
         }
      });
   }
});

// Fungsi global untuk menghapus baris, bisa dipakai keduanya
function removeRow(button) {
   button.parentElement.remove();
}

function formatRibuan(input) {
   // 1. Hapus karakter selain angka
   let value = input.value.replace(/[^0-9]/g, '');

   // 2. Format ke ribuan (Indonesia menggunakan titik)
   if (value) {
      value = parseInt(value).toLocaleString('id-ID');
   }

   // 3. Kembalikan ke input
   input.value = value;
}