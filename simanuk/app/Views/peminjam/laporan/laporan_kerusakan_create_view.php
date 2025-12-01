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

            <input type="hidden" name="id_peminjaman" value="<?= esc($prefill['id_peminjaman'] ?? '') ?>">

            <h3 class="text-lg font-bold text-gray-900 uppercase tracking-wider border-b border-gray-100 pb-2">
               Informasi Aset
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Aset</label>
                  <select id="tipeAset" name="tipe_aset" onchange="toggleSelect()" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                     <option value="Sarana" <?= ($prefill['tipe'] ?? '') == 'Sarana' ? 'selected' : '' ?>>Sarana (Barang)</option>
                     <option value="Prasarana" <?= ($prefill['tipe'] ?? '') == 'Prasarana' ? 'selected' : '' ?>>Prasarana (Ruangan)</option>
                  </select>
               </div>

               <div id="selectSarana" class="<?= ($prefill['tipe'] ?? 'Sarana') == 'Sarana' ? '' : 'hidden' ?>">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Barang</label>
                  <select name="id_sarana" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                     <option value="">-- Pilih Sarana --</option>
                     <?php foreach ($saranaList as $s) : ?>
                        <option value="<?= $s['id_sarana'] ?>"
                           <?= ($prefill['id_aset'] ?? '') == $s['id_sarana'] ? 'selected' : '' ?>>
                           <?= esc($s['nama_sarana']) ?> (<?= esc($s['kode_sarana']) ?>)
                        </option>
                     <?php endforeach; ?>
                  </select>
               </div>

               <div id="selectPrasarana" class="<?= ($prefill['tipe'] ?? '') == 'Prasarana' ? '' : 'hidden' ?>">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Ruangan</label>
                  <select name="id_prasarana" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
                     <option value="">-- Pilih Prasarana --</option>
                     <?php foreach ($prasaranaList as $p) : ?>
                        <option value="<?= $p['id_prasarana'] ?>"
                           <?= ($prefill['id_aset'] ?? '') == $p['id_prasarana'] ? 'selected' : '' ?>>
                           <?= esc($p['nama_prasarana']) ?>
                        </option>
                     <?php endforeach; ?>
                  </select>
               </div>

               <div id="inputJumlah" class="<?= ($prefill['tipe'] ?? 'Sarana') == 'Sarana' ? '' : 'hidden' ?>">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Rusak</label>
                  <input type="number" name="jumlah" value="1" min="1" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors">
               </div>

               <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan</label>
                  <input type="text" name="judul_laporan" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors" required>
               </div>

               <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kerusakan</label>
                  <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 px-3 py-2 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 transition-colors"></textarea>
               </div>

               <div class="col-span-2">
                  <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Foto</label>
                  <input type="file" name="bukti_foto" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
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