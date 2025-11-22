<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen">
   <div class="flex-1 flex flex-col overflow-hidden">
      <main class="flex-1 overflow-y-auto p-6 md:p-8">
         <h1 class="text-3xl font-bold text-gray-900 mt-6 mb-4">Tambah Prasarana Baru</h1>
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

            <form action="<?= site_url('admin/inventaris/prasarana/save') ?>" method="post" enctype="multipart/form-data">
               <?= csrf_field() ?>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_prasarana">
                        Nama Item / Prasarana
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_prasarana" name="nama_prasarana" type="text" placeholder="Contoh: Ruang Rapat atau Lab Komputer dll" value="<?= old('nama_prasarana') ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kode_prasarana">
                        Kode Prasarana (Unik)
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kode_prasarana" name="kode_prasarana" type="text" placeholder="Contoh: FT-RRU-01 atau FT-LAB-KMD" value="<?= old('kode_prasarana') ?>">
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
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="lantai">
                        Lantai Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="lantai" name="lantai" type="number" min="1" value="<?= old('lantai', 1) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="luas_ruangan">
                        Luas Ruangan <span class="text-gray-400">(hanya angka dan dalam satuan cm2)</span>
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="luas_ruangan" name="luas_ruangan" type="number" min="1" value="<?= old('luas_ruangan', 1) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kapasitas_orang">
                        Kapasitas Orang dalam Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kapasitas_orang" name="kapasitas_orang" type="number" min="1" value="<?= old('kapasitas_orang', 1) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_ruangan">
                        Jenis Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="jenis_ruangan" name="jenis_ruangan" type="text" placeholder="Contoh: Kelas" value="<?= old('jenis_ruangan') ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="tata_letak">
                        Tata Letak Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="tata_letak" name="tata_letak" type="text" placeholder="Contoh: Ruangan Kelas" value="<?= old('tata_letak') ?>">
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
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="status_ketersediaan">
                        Status Ketersediaan
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="status_ketersediaan" name="status_ketersediaan">
                        <option value="Tersedia" <?= old('status_ketersediaan') == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Dipinjam" <?= old('status_ketersediaan') == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Renovasi" <?= old('status_ketersediaan') == 'Renovasi' ? 'selected' : '' ?>>Renovasi</option>
                        <option value="Tidak Tersedia" <?= old('status_ketersediaan') == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                     </select>
                  </div>

               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitas Ruangan</label>
                  <div id="fasilitas-container" class="space-y-4">
                     <?php
                     // Menangani data lama jika terjadi error validasi
                     $old_fasilitas = old('fasilitas', ['']);
                     ?>
                     <?php foreach ($old_fasilitas as $i => $fasilitas_item) : ?>
                        <div class="flex items-center gap-4">
                           <input type="text" name="fasilitas[]" placeholder="Contoh: AC" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?= esc($fasilitas_item) ?>">
                           <?php if ($i > 0) : ?>
                              <button type="button" class="text-red-500 hover:text-red-700" onclick="removeFasilitasRow(this)">Hapus</button>
                           <?php else : ?>
                              <!-- Placeholder untuk alignment -->
                              <div style="width: 48px;"></div>
                           <?php endif; ?>
                        </div>
                     <?php endforeach; ?>
                  </div>
                  <button type="button" id="tambah-fasilitas" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                     + Tambah Fasilitas
                  </button>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="foto_aset">
                     Foto Dokumentasi Prasarana
                  </label>
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
               </div>
               <!-- Container untuk pratinjau gambar -->
               <div id="image-preview-container" class="mt-4 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                  <!-- Pratinjau gambar akan muncul di sini -->
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

<!-- Panggil script inventaris -->
<script src="<?= base_url('js/inventaris.js') ?>"></script>

<?= $this->endSection(); ?>