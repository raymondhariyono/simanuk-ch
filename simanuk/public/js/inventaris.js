document.addEventListener('DOMContentLoaded', function () {
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
});

// Fungsi global untuk menghapus baris, bisa dipakai keduanya
function removeRow(button) {
   button.parentElement.remove();
}