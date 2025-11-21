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

            <form action="<?= site_url('admin/inventaris/prasarana/update/' . $prasarana['id_prasarana']) ?>" method="post">
               <?= csrf_field() ?>

               <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_prasarana">
                        Nama Item / Prasarana
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_prasarana" name="nama_prasarana" type="text" placeholder="Contoh: Ruang Rapat" value="<?= old('nama_prasarana', $prasarana['nama_prasarana']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kode_prasarana">
                        Kode Prasarana (Unik)
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kode_prasarana" name="kode_prasarana" type="text" placeholder="Contoh: FT-RRU-01" value="<?= old('kode_prasarana', $prasarana['kode_prasarana']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_kategori">
                        Kategori
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700" id="id_kategori" name="id_kategori">
                        <?php foreach ($kategori as $k) : ?>
                           <option value="<?= $k['id_kategori'] ?>" <?= old('id_kategori', $prasarana['id_kategori']) == $k['id_kategori'] ? 'selected' : '' ?>><?= $k['nama_kategori'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="lantai">
                        Lantai Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="lantai" name="lantai" type="number" min="1" value="<?= old('lantai', $prasarana['lantai']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="luas_ruangan">
                        Luas Ruangan <span class="text-gray-400">(m²)</span>
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="luas_ruangan" name="luas_ruangan" type="number" min="1" value="<?= old('luas_ruangan', $prasarana['luas_ruangan']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="kapasitas_orang">
                        Kapasitas Orang
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="kapasitas_orang" name="kapasitas_orang" type="number" min="1" value="<?= old('kapasitas_orang', $prasarana['kapasitas_orang']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="jenis_ruangan">
                        Jenis Ruangan
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="jenis_ruangan" name="jenis_ruangan" type="text" placeholder="Contoh: Kelas" value="<?= old('jenis_ruangan', $prasarana['jenis_ruangan']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="tata_letak">
                        Tata Letak
                     </label>
                     <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" id="tata_letak" name="tata_letak" type="text" placeholder="Contoh: Teater" value="<?= old('tata_letak', $prasarana['tata_letak']) ?>">
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="id_lokasi">
                        Lokasi Gedung
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700" id="id_lokasi" name="id_lokasi">
                        <?php foreach ($lokasi as $l) : ?>
                           <option value="<?= $l['id_lokasi'] ?>" <?= old('id_lokasi', $prasarana['id_lokasi']) == $l['id_lokasi'] ? 'selected' : '' ?>><?= $l['nama_lokasi'] ?></option>
                        <?php endforeach; ?>
                     </select>
                  </div>

                  <div>
                     <label class="block text-gray-700 text-sm font-bold mb-2" for="status_ketersediaan">
                        Status Ketersediaan
                     </label>
                     <select class="shadow border rounded w-full py-2 px-3 text-gray-700" id="status_ketersediaan" name="status_ketersediaan">
                        <option value="Tersedia" <?= old('status_ketersediaan', $prasarana['status_ketersediaan']) == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                        <option value="Dipinjam" <?= old('status_ketersediaan', $prasarana['status_ketersediaan']) == 'Dipinjam' ? 'selected' : '' ?>>Dipinjam</option>
                        <option value="Renovasi" <?= old('status_ketersediaan', $prasarana['status_ketersediaan']) == 'Renovasi' ? 'selected' : '' ?>>Renovasi</option>
                        <option value="Tidak Tersedia" <?= old('status_ketersediaan', $prasarana['status_ketersediaan']) == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                     </select>
                  </div>
               </div>

               <div class="mt-6">
                  <label class="block text-gray-700 text-sm font-bold mb-2">Fasilitas Ruangan</label>
                  <div id="fasilitas-container" class="space-y-4">
                     <?php
                     $old_fasilitas = old('fasilitas', json_decode($prasarana['fasilitas'], true) ?: ['']);
                     ?>
                     <?php foreach ($old_fasilitas as $i => $fasilitas_item) : ?>
                        <div class="flex items-center gap-4">
                           <input type="text" name="fasilitas[]" placeholder="Contoh: AC" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="<?= esc($fasilitas_item) ?>">
                           <?php if ($i > 0) : ?>
                              <button type="button" class="text-red-500 hover:text-red-700" onclick="removeFasilitasRow(this)">Hapus</button>
                           <?php else : ?>
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
                  <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">Deskripsi Tambahan</label>
                  <textarea class="shadow border rounded w-full py-2 px-3 text-gray-700" id="deskripsi" name="deskripsi" rows="3"><?= old('deskripsi', $prasarana['deskripsi']) ?></textarea>
               </div>

               <div class="mt-8 flex justify-end gap-4">
                  <a href="<?= site_url('admin/inventaris') ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                     Batal
                  </a>
                  <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
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