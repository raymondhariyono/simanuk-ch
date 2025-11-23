<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen">
   <div class="flex-1 flex flex-col overflow-hidden">
      <main class="flex-1 overflow-y-auto p-6 md:p-8">
         <h1 class="text-3xl font-bold text-gray-900 mt-6 mb-4"><?= esc($title) ?></h1>
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

            <form action="<?= site_url('admin/inventaris/sarana/update/' . $sarana['id_sarana']) ?>" method="post" enctype="multipart/form-data">
               <?= csrf_field() ?>
               <input type="hidden" name="_method" value="POST">

               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_sarana">
                        Nama Item / Sarana
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_sarana" name="nama_sarana" type="text" placeholder="Contoh: Proyektor Epson" value="<?= old('nama_sarana', $sarana['nama_sarana']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kode_sarana">
                        Kode Sarana (Unik)
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kode_sarana" name="kode_sarana" type="text" placeholder="Contoh: FT-PRJ-001" value="<?= old('kode_sarana', $sarana['kode_sarana']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_kategori">
                        Kategori
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_kategori" name="id_kategori">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($kategori as $k) : ?>
                           <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori', $sarana['id_kategori']) == $k['id_kategori'] ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="jumlah">
                        Jumlah Unit
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jumlah" name="jumlah" type="number" min="1" value="<?= old('jumlah', $sarana['jumlah']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_lokasi">
                        Lokasi Gedung
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="id_lokasi" name="id_lokasi">
                        <option value="">Pilih Lokasi</option>
                        <?php foreach ($lokasi as $l) : ?>
                           <option value="<?= $l['id_lokasi'] ?>" <?= old('id_lokasi', $sarana['id_lokasi']) == $l['id_lokasi'] ? 'selected' : '' ?>><?= $l['nama_lokasi'] ?></option>
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
                           <option value="<?= $p['id_prasarana'] ?>" <?= old('id_prasarana', $sarana['id_prasarana']) == $p['id_prasarana'] ? 'selected' : '' ?>><?= $p['nama_prasarana'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kondisi">
                        Kondisi
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kondisi" name="kondisi">
                        <option value="Baik" <?= old('kondisi', $sarana['kondisi']) == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= old('kondisi', $sarana['kondisi']) == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= old('kondisi', $sarana['kondisi']) == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="status_ketersediaan">
                        Status Ketersediaan
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status_ketersediaan" name="status_ketersediaan">
                        <option value="Tersedia" <?= old('status_ketersediaan', $sarana['status_ketersediaan']) == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Dipinjam" <?= old('status_ketersediaan', $sarana['status_ketersediaan']) == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Perawatan" <?= old('status_ketersediaan', $sarana['status_ketersediaan']) == 'Perawatan' ? 'selected' : '' ?>>Perawatan</option>
                        <option value="Tidak Tersedia" <?= old('status_ketersediaan', $sarana['status_ketersediaan']) == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                     </select>
                  </div>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Spesifikasi Tambahan</label>
                  <div id="spesifikasi-container" class="space-y-4">
                     <?php
                     $old_spec_keys = old('spesifikasi_key');
                     $old_spec_values = old('spesifikasi_value');
                     $spesifikasi = json_decode($sarana['spesifikasi'], true) ?: [];

                     if ($old_spec_keys) { // Jika ada old input dari validasi
                        $spec_keys = $old_spec_keys;
                        $spec_values = $old_spec_values;
                     } else { // Jika tidak, gunakan data dari database
                        $spec_keys = array_keys($spesifikasi);
                        $spec_values = array_values($spesifikasi);
                     }
                     // Tambahkan satu baris kosong jika tidak ada spesifikasi
                     if (empty($spec_keys)) {
                        $spec_keys = [''];
                        $spec_values = [''];
                     }
                     ?>
                     <?php foreach ($spec_keys as $i => $key) : ?>
                        <div class="flex items-center gap-4">
                           <input type="text" name="spesifikasi_key[]" placeholder="Contoh: Merk" class="shadow appearance-none border rounded w-1/3 py-2 px-3 text-gray-700" value="<?= esc($key) ?>">
                           <input type="text" name="spesifikasi_value[]" placeholder="Contoh: Epson" class="shadow appearance-none border rounded w-2/3 py-2 px-3 text-gray-700" value="<?= esc($spec_values[$i] ?? '') ?>">
                           <?php if ($i > 0) : ?>
                              <button type="button" class="text-red-500 hover:text-red-700" onclick="removeRow(this)">Hapus</button>
                           <?php else : ?>
                              <div style="width: 48px;"></div>
                           <?php endif; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
                  <button type="button" id="tambah-spesifikasi" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                     + Tambah Spesifikasi
                  </button>
               </div>

               <div class="mt-8 border-t pt-6">
                  <h3 class="text-lg font-semibold text-gray-800 mb-4">Foto Aset</h3>

                  <?php if (!empty($fotoSarana)) : ?>
                     <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        <?php foreach ($fotoSarana as $foto) : ?>
                           <div class="relative group border rounded-lg overflow-hidden">
                              <img src="<?= base_url($foto['url_foto']) ?>" class="w-full h-32 object-cover">
                              <a href="<?= site_url('admin/inventaris/sarana/foto/delete/' . $foto['id_foto']) ?>"
                                 onclick="return confirm('Hapus foto ini?')"
                                 class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full opacity-75 hover:opacity-100">
                                 <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                 </svg>
                              </a>
                           </div>
                        <?php endforeach; ?>
                     </div>
                  <?php endif; ?>

                  <label class="block text-gray-700 text-sm font-bold mb-2" for="foto_aset">Tambah Foto Baru (Opsional)</label>
                  <div class="flex items-center justify-center w-full">
                     <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                           <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                           </svg>
                           <p class="text-sm text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag and drop</p>
                           <p class="text-xs text-gray-500">PNG, JPG (Maks. 2MB per file)</p>
                        </div>
                        <input id="dropzone-file" name="foto_aset[]" type="file" class="hidden" multiple accept="image/png, image/jpeg, image/jpg" />
                        <?php if (session('validation') && session('validation')->hasError('foto_aset')) : ?>
                           <p class="mt-1 text-sm text-red-500">
                              <?= session('validation')->getError('foto_aset'); ?>
                           </p>
                        <?php endif; ?>
                     </label>

                  </div>
                  <p class="text-center text-xs text-gray-500 mt-1">Tekan Ctrl (Windows) atau Command (Mac) untuk memilih banyak file sekaligus.</p>

                  <!-- Container untuk pratinjau gambar -->
                  <div id="image-preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                     <!-- Pratinjau gambar akan muncul di sini -->
                  </div>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi Tambahan</label>
                  <textarea class="shadow border rounded w-full py-2 px-3 text-gray-700" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi', $sarana['deskripsi']) ?></textarea>
               </div>

               <div class="mt-8 flex justify-end gap-4">
                  <a href="<?= site_url('admin/inventaris') ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                     Batal
                  </a>
                  <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                     Simpan Perubahan
                  </button>
               </div>
            </form>
         </div>
      </main>
   </div>
</div>

<!-- Panggil script inventaris -->
<script src="<?= base_url('js/inventaris.js') ?>"></script>

<?= $this->endSection(); ?>