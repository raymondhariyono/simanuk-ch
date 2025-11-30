<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-4 py-10 mx-auto max-w-3xl">

   <div class="mb-10 text-center">
      <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Laporan Kerusakan</h2>
      <p class="mt-2 text-sm text-gray-500">Silakan lengkapi formulir di bawah ini dengan detail yang akurat.</p>
   </div>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <div class="bg-white rounded-xl shadow-sm border border-gray-200">
      <div class="p-8">

         <form action="<?= site_url('peminjam/laporan-kerusakan/create') ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <?= csrf_field() ?>

            <div class="space-y-6">
               <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
                  Informasi Aset
               </h3>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Aset</label>
                     <select id="tipeAset" name="tipe_aset" onchange="toggleSelect()" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5">
                        <option value="Sarana">Sarana</option>
                        <option value="Prasarana">Prasarana</option>
                     </select>
                  </div>

                  <div id="selectSarana">
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Barang</label>
                     <select name="id_sarana" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5">
                        <option value="">-- Pilih Barang --</option>
                        <?php foreach ($saranaList as $s) : ?>
                           <option value="<?= $s['id_sarana'] ?>"><?= esc($s['nama_sarana']) ?> (<?= esc($s['kode_sarana']) ?>)</option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div id="selectPrasarana" class="hidden">
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Pilih Prasarana</label>
                     <select name="id_prasarana" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5">
                        <option value="">-- Pilih Prasarana --</option>
                        <?php foreach ($prasaranaList as $p) : ?>
                           <option value="<?= $p['id_prasarana'] ?>"><?= esc($p['nama_prasarana']) ?> (<?= esc($p['kode_prasarana']) ?>)</option>
                        <?php endforeach; ?>
                     </select>
                  </div>
               </div>
            </div>

            <div class="space-y-6 pt-4">
               <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
                  Detail Masalah
               </h3>

               <div class="space-y-5">
                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Laporan</label>
                     <input type="text" name="judul_laporan" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-2 py-2.5 placeholder-gray-400" placeholder="Contoh: Kursi Patah di R.101">
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                     <textarea name="deskripsi" rows="4" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-2 py-2.5 placeholder-gray-400" placeholder="Jelaskan kondisi kerusakan..."></textarea>
                  </div>

                  <div>
                     <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto</label>
                     <div class="flex items-center justify-center w-full">
                        <label for="fileInput" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                           <div class="flex flex-col items-center justify-center pt-5 pb-6">
                              <svg class="w-8 h-8 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                              </svg>
                              <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                              <p class="text-xs text-gray-500">PNG, JPG (Max. 5MB)</p>
                           </div>
                           <input id="fileInput" name="bukti_foto" type="file" class="hidden" required accept="image/*" onchange="previewFile()" />
                        </label>
                     </div>
                     <p id="fileName" class="text-xs text-gray-600 mt-2 text-center font-medium hidden"></p>
                  </div>
               </div>
            </div>

            <div class="pt-6 flex items-center justify-end space-x-3">
               <a href="<?= site_url('peminjam/laporan-kerusakan') ?>" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 transition-colors">
                  Batal
               </a>
               <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors shadow-sm">
                  Kirim Laporan
               </button>
            </div>

         </form>
      </div>
   </div>
</div>

<script src="<?= base_url('js/peminjam/laporan_kerusakan.js') ?>"></script>

<?= $this->endSection(); ?>