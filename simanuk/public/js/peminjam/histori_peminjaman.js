document.addEventListener('DOMContentLoaded', function () {
   // --- LOGIKA TAB SWITCHING ---
   const activeTab = document.getElementById('active-tab');
   const historyTab = document.getElementById('history-tab');
   const activeContent = document.getElementById('active');
   const historyContent = document.getElementById('history');

   if (activeTab && historyTab) {
      activeTab.addEventListener('click', () => {
         activeContent.classList.remove('hidden');
         historyContent.classList.add('hidden');

         activeTab.classList.add('text-blue-600', 'border-blue-600');
         activeTab.classList.remove('text-gray-500', 'border-transparent');

         historyTab.classList.add('text-gray-500', 'border-transparent');
         historyTab.classList.remove('text-blue-600', 'border-blue-600');
      });

      historyTab.addEventListener('click', () => {
         activeContent.classList.add('hidden');
         historyContent.classList.remove('hidden');

         historyTab.classList.add('text-blue-600', 'border-blue-600');
         historyTab.classList.remove('text-gray-500', 'border-transparent');

         activeTab.classList.add('text-gray-500', 'border-transparent');
         activeTab.classList.remove('text-blue-600', 'border-blue-600');
      });
   }

   // Close modal on Escape key
   window.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
         closeDetailPenolakanModal();
         closeUploadModal();
         closeRejectionModal();
      }
   });
});

// --- LOGIKA MODAL (Global Functions) ---

function openUploadModal(jenis, tipeItem, idDetail, namaItem, jumlah = 1) {
   const form = document.getElementById('formUploadBukti');
   const title = document.getElementById('modalTitle');
   const desc = document.getElementById('modalDescription');
   const kondisiDiv = document.getElementById('kondisiInputContainer');
   const kondisiInput = kondisiDiv.querySelector('select');

   document.getElementById('uploadModal').classList.remove('hidden');

   // Set nilai Max Qty untuk validasi
   document.getElementById('inputMaxQty').value = jumlah;

   // Reset Form State
   document.getElementById('selectKondisi').value = 'Baik';
   toggleJumlahRusakInput('Baik', jumlah); // Helper function baru

   // Menggunakan SITE_URL global
   if (jenis === 'sebelum') {
      form.action = `${SITE_URL}peminjam/peminjaman/upload-bukti-sebelum/${tipeItem}/${idDetail}`;
      title.innerText = 'Bukti Pengambilan';
      desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengambilnya.';
      kondisiDiv.classList.remove('hidden');
      kondisiInput.required = false;
   } else {
      form.action = `${SITE_URL}peminjam/peminjaman/upload-bukti-sesudah/${tipeItem}/${idDetail}`;
      title.innerText = 'Bukti Pengembalian';
      desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengembalikannya.';
      kondisiDiv.classList.remove('hidden');
      kondisiInput.required = true;
   }
}
// Event Listener untuk Dropdown Kondisi
const selectKondisi = document.getElementById('selectKondisi');
const inputJumlahRusak = document.getElementById('inputJumlahRusak');
const inputMaxQty = document.getElementById('inputMaxQty');

if (selectKondisi) {
   selectKondisi.addEventListener('change', function () {
      const maxQty = parseInt(inputMaxQty.value) || 1;
      toggleJumlahRusakInput(this.value, maxQty);
   });
}

// Logic Validasi Input Jumlah
if (inputJumlahRusak) {
   inputJumlahRusak.addEventListener('input', function () {
      const max = parseInt(inputMaxQty.value) || 1;
      let val = parseInt(this.value);

      // Validasi: Tidak boleh > jumlah pinjam
      if (val > max) {
         this.value = max;
         val = max;
      }
      // Validasi: Tidak boleh < 1
      if (val < 1) {
         this.value = 1;
         val = 1;
      }

      // Update Text Informasi
      const sisa = max - val;
      document.getElementById('spanSisaBaik').innerText = sisa;
   });
}

function toggleJumlahRusakInput(kondisi, maxQty) {
   const divJumlah = document.getElementById('divJumlahRusak');
   const inputJumlah = document.getElementById('inputJumlahRusak');

   if (kondisi !== 'Baik' && maxQty > 1) {
      // Tampilkan jika rusak DAN barang lebih dari 1
      divJumlah.classList.remove('hidden');
      inputJumlah.required = true;
      inputJumlah.max = maxQty;
      inputJumlah.value = maxQty; // Default set ke semua rusak (bisa diedit user)
      document.getElementById('spanSisaBaik').innerText = 0;
   } else {
      // Sembunyikan
      divJumlah.classList.add('hidden');
      inputJumlah.required = false;
      inputJumlah.value = '';
   }
}

function closeUploadModal() {
   document.getElementById('uploadModal').classList.add('hidden');
}

function openDetailPenolakanModal(buttonElement) {
   const alasan = buttonElement.getAttribute('data-alasan');
   let displayAlasan = alasan;
   const match = alasan.match(/\[DITOLAK:\s*(.*?)\]/);
   if (match && match[1]) displayAlasan = match[1];

   document.getElementById('alasanPenolakanText').textContent = displayAlasan.trim() ? displayAlasan : 'Tidak ada alasan spesifik.';
   const modal = document.getElementById('detailPenolakanModal');
   modal.classList.remove('hidden');
   modal.classList.add('flex');
}

function closeDetailPenolakanModal() {
   const modal = document.getElementById('detailPenolakanModal');
   modal.classList.add('hidden');
   modal.classList.remove('flex');
}

function openRejectionModal(button) {
   const reason = button.getAttribute('data-reason');
   document.getElementById('rejectionReasonText').innerText = reason;
   document.getElementById('rejectionModal').classList.remove('hidden');
}

function closeRejectionModal() {
   document.getElementById('rejectionModal').classList.add('hidden');
}

function validateFileUpload(input) {
   const file = input.files[0];
   const errorMsg = document.getElementById('fileErrorMsg');
   const submitBtn = document.getElementById('btnSubmitReturn'); // Pastikan tombol submit punya ID ini

   // Reset Error
   errorMsg.classList.add('hidden');
   errorMsg.innerText = '';
   input.classList.remove('border-red-500');

   if (file) {
      // 1. Validasi Tipe File (MIME Type)
      const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
      if (!validTypes.includes(file.type)) {
         showError(input, errorMsg, '❌ Hanya file JPG, JPEG, dan PNG yang diperbolehkan.');
         return;
      }

      // 2. Validasi Ukuran (2 MB = 2 * 1024 * 1024 bytes)
      const maxSize = 2 * 1024 * 1024;
      if (file.size > maxSize) {
         showError(input, errorMsg, '❌ Ukuran file terlalu besar! Maksimal 2 MB.');
         return;
      }
   }
}

function showError(input, element, message) {
   element.innerText = message;
   element.classList.remove('hidden');
   input.value = ''; // Reset input agar user harus pilih ulang
   input.classList.add('border-red-500'); // Merahkan input (opsional styling)
}