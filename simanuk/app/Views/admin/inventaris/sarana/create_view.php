<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen">
   <div class="flex-1 flex flex-col overflow-hidden">
      <main class="flex-1 overflow-y-auto p-6 md:p-8">
         <h1 class="text-3xl font-bold text-gray-900 mt-6 mb-4">Tambah Sarana Baru</h1>
         <?php if (isset($breadcrumbs)) : ?>
            <?= render_breadcrumb($breadcrumbs); ?>
         <?php endif; ?>

         <div class="bg-white rounded-lg shadow-lg p-6">
            <?php if (session()->has('errors')) : ?>
               <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative" role="alert">
                  <ul>
                     <?php foreach (session('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                     <?php endforeach ?>
                  </ul>
               </div>
            <?php endif ?>

            <form action="<?= site_url('admin/inventaris/sarana/save') ?>" method="post">
               <?= csrf_field() ?>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_sarana">
                        Nama Item / Sarana 
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_sarana" name="nama_sarana" type="text" placeholder="Contoh: Proyektor Epson" value="<?= old('nama_sarana') ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kode_sarana">
                        Kode Sarana (Unik)
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kode_sarana" name="kode_sarana" type="text" placeholder="Contoh: FT-PRJ-001" value="<?= old('kode_sarana') ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_kategori">
                        Kategori
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_kategori" name="id_kategori">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori as $k) : ?>
                           <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori') == $k['id_kategori'] ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah">
                        Jumlah Unit
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jumlah" name="jumlah" type="number" min="1" value="<?= old('jumlah', 1) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_lokasi">
                        Lokasi Gedung
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_lokasi" name="id_lokasi">
                        <option value="">Pilih Lokasi</option>
                        <?php foreach ($lokasi as $l) : ?>
                           <option value="<?= $l['id_lokasi'] ?>" <?= old('id_lokasi') == $l['id_lokasi'] ? 'selected' : '' ?>><?= $l['nama_lokasi'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_prasarana">
                        Penempatan Ruangan (Opsional)
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_prasarana" name="id_prasarana">
                        <option value="">Tidak terikat ruangan</option>
                        <?php foreach ($prasarana as $p) : ?>
                           <option value="<?= $p['id_prasarana'] ?>" <?= old('id_prasarana') == $p['id_prasarana'] ? 'selected' : '' ?>><?= $p['nama_prasarana'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kondisi">
                        Kondisi
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kondisi" name="kondisi">
                        <option value="Baik" <?= old('kondisi') == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= old('kondisi') == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= old('kondisi') == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="status_ketersediaan">
                        Status Ketersediaan
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status_ketersediaan" name="status_ketersediaan">
                        <option value="Tersedia" <?= old('status_ketersediaan') == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Dipinjam" <?= old('status_ketersediaan') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Perawatan" <?= old('status_ketersediaan') == 'Perawatan' ? 'selected' : '' ?>>Perawatan</option>
                        <option value="Tidak Tersedia" <?= old('status_ketersediaan') == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                     </select>
                  </div>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Spesifikasi Tambahan</label>
                  <div id="spesifikasi-container" class="space-y-4">
                     <?php
                     $old_spec_keys = old('spesifikasi_key', ['']);
                     $old_spec_values = old('spesifikasi_value', ['']);
                     $spec_count = count($old_spec_keys);
                     ?>
                     <?php for ($i = 0; $i < $spec_count; $i++) : ?>
                        <div class="flex items-center gap-4">
                           <input type="text" name="spesifikasi_key[]" placeholder="Contoh: Merk" class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700" value="<?= esc($old_spec_keys[$i]) ?>">
                           <input type="text" name="spesifikasi_value[]" placeholder="Contoh: Epson" class="shadow appearance-none border rounded w-2/3 py-2 px-3 text-gray-700" value="<?= esc($old_spec_values[$i]) ?>">
                           <?php if ($i > 0) : ?>
                              <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">Hapus</button>
                           <?php else : ?>
                              <!-- Placeholder for alignment on the first row -->
                              <div style="width: 48px;"></div>
                           <?php endif; ?>
                        </div>
                     <?php endfor; ?>
                  </div>
                  <button type="button" id="tambah-spesifikasi" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                     + Tambah Spesifikasi
                  </button>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi Tambahan</label>
                  <textarea class="shadow border rounded w-full py-2 px-3 text-gray-700" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi') ?></textarea>
               </div>


               <div class="mt-8 flex justify-end gap-4">
                  <a href="<?= site_url('admin/inventaris') ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                     Batal
                  </a>
                  <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                     Simpan Data
                  </button>
               </div>
            </form>
         </div>
      </main>
   </div>
</div>

<script>
   document.getElementById('tambah-spesifikasi').addEventListener('click', function() {
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

   function removeRow(button) {
      const row = button.parentElement;
      row.remove();
   }
</script>

<?= $this->endSection(); ?>