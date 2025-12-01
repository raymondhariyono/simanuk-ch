<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
   <div class="flex justify-between items-center mb-6">
      <h2 class="text-2xl font-semibold text-gray-700">Lapor Kerusakan (Internal)</h2>
      <?php if (isset($breadcrumbs)) : ?>
         <?= render_breadcrumb($breadcrumbs); ?>
      <?php endif; ?>
   </div>

   <?php if (session()->has('errors')) : ?>
      <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
         <ul><?php foreach (session('errors') as $error) : ?><li><?= esc($error) ?></li><?php endforeach ?></ul>
      </div>
   <?php endif ?>

   <div class="bg-white rounded-lg shadow-lg p-6">
      <form action="<?= site_url('admin/laporan-kerusakan/create') ?>" method="POST" enctype="multipart/form-data">
         <?= csrf_field() ?>

         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
               <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Aset</label>
               <select id="tipeAset" name="tipe_aset" onchange="toggleSelect()" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  <option value="Sarana">Sarana</option>
                  <option value="Prasarana">Prasarana</option>
               </select>
            </div>

            <div id="selectSarana">
               <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Sarana</label>
               <select name="id_sarana" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                  <option value="">-- Pilih Sarana --</option>
                  <?php foreach ($saranaList as $s) : ?>
                     <option value="<?= $s['id_sarana'] ?>"><?= esc($s['nama_sarana']) ?> (<?= esc($s['kode_sarana']) ?>)</option>
                  <?php endforeach; ?>
               </select>
            </div>

            <div id="selectPrasarana" class="hidden">
               <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Prasarana</label>
               <select name="id_prasarana" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline0">
                  <option value="">-- Pilih Prasarana --</option>
                  <?php foreach ($prasaranaList as $p) : ?>
                     <option value="<?= $p['id_prasarana'] ?>"><?= esc($p['nama_prasarana']) ?></option>
                  <?php endforeach; ?>
               </select>
            </div>

            <div id="inputJumlah">
               <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Rusak</label>
               <input type="number" name="jumlah" value="1" min="1" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>

            <div class="col-span-2">
               <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan</label>
               <input type="text" name="judul_laporan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: Temuan kursi patah di gudang" required>
            </div>

            <div class="col-span-2">
               <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Kerusakan</label>
               <textarea name="deskripsi" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
            </div>

            <div class="col-span-2">
               <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Foto (Temuan)</label>
               <input type="file" name="bukti_foto" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

         </div>

         <div class="mt-6 flex justify-end gap-3">
            <a href="<?= site_url('admin/laporan-kerusakan') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Batal</a>
            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 shadow">Simpan Laporan</button>
         </div>
      </form>
   </div>
</div>

<script>
   function toggleSelect() {
      const tipe = document.getElementById('tipeAset').value;
      const divSarana = document.getElementById('selectSarana');
      const divPrasarana = document.getElementById('selectPrasarana');
      const divJumlah = document.getElementById('inputJumlah');

      if (tipe === 'Sarana') {
         divSarana.classList.remove('hidden');
         divPrasarana.classList.add('hidden');
         divJumlah.classList.remove('hidden'); // Sarana butuh jumlah
      } else {
         divSarana.classList.add('hidden');
         divPrasarana.classList.remove('hidden');
         divJumlah.classList.add('hidden'); // Prasarana biasanya 1 unit (Prasarana)
      }
   }
</script>
<?= $this->endSection(); ?>