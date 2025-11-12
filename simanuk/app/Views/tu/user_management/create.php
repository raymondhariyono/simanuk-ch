<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Tambah Akun' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-lg shadow-md">
   <h1 class="text-2xl font-bold mb-6">Tambah Akun Pengguna Baru</h1>

   <!-- Tampilkan Error Validasi -->
   <?php if (session('errors')): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
         <strong class="font-bold">Error!</strong>
         <ul>
            <?php foreach (session('errors') as $error): ?>
               <li><?= $error ?></li>
            <?php endforeach; ?>
         </ul>
      </div>
   <?php endif; ?>

   <form action="<?= site_url('tu/kelola/akun/save') ?>" method="post" class="space-y-4">
      <?= csrf_field() ?>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
         <!-- Kolom Kiri: Informasi Pribadi -->
         <div class="space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2 mb-4">Data Pribadi</h2>
            <div>
               <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
               <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?= old('nama_lengkap') ?>" required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="organisasi" class="block text-sm font-medium text-gray-700">Organisasi (UKM/Bagian)</label>
               <input type="text" name="organisasi" id="organisasi" value="<?= old('organisasi') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="kontak" class="block text-sm font-medium text-gray-700">Nomor Kontak (HP) </label>
               <input type="text" name="kontak" id="kontak" value="<?= old('kontak') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="id_role" class="block text-sm font-medium text-gray-700">Role Pengguna <span class="text-red-500">*</span></label>
               <select name="id_role" id="id_role" required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
                  <option value="">-- Pilih Role --</option>
                  <?php foreach ($roles as $role): ?>
                     <option value="<?= $role['id_role'] ?>" <?= (string)old('id_role') === (string)$role['id_role'] ? 'selected' : '' ?>>
                        <?= $role['nama_role'] ?>
                     </option>
                  <?php endforeach; ?>
               </select>
            </div>
         </div>

         <!-- Kolom Kanan: Kredensial Akun -->
         <div class="space-y-4">
            <h2 class="text-lg font-semibold border-b pb-2 mb-4">Kredensial Akun</h2>
            <div>
               <label for="email" class="block text-sm font-medium text-gray-700">Email (Digunakan untuk Login) <span class="text-red-500">*</span></label>
               <input type="email" name="email" id="email" value="<?= old('email') ?>" required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="username" class="block text-sm font-medium text-gray-700">Username (Opsional) </label>
               <input type="text" name="username" id="username" value="<?= old('username') ?>"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="password" class="block text-sm font-medium text-gray-700">Password Default <span class="text-red-500">*</span></label>
               <input type="password" name="password" id="password" required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
               <label for="pass_confirm" class="block text-sm font-medium text-gray-700">Konfirmasi Password <span class="text-red-500">*</span></label>
               <input type="password" name="pass_confirm" id="pass_confirm" required
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 border">
               <p class="mt-2 text-xs text-gray-500">Pengguna wajib mengganti password ini saat login pertama kali (UC04).</p>
            </div>
         </div>
      </div>

      <div class="pt-6 border-t flex justify-end space-x-3">
         <a href="<?= site_url('tu/kelola/akun') ?>" class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            Batal
         </a>
         <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
            Buat Akun
         </button>
      </div>
   </form>
</div>
<?= $this->endSection() ?>