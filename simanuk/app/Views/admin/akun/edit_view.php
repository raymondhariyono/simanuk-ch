<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Edit Akun Pengguna: <?= esc($user->username) ?></h2>

   <?php if (session()->has('errors')) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
         <ul><?php foreach (session('errors') as $error) : ?>
               <li><?= esc($error) ?></li><?php endforeach ?>
         </ul>
      </div>
   <?php endif ?>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <div class="bg-gray-50 shadow-sm rounded-lg p-6 border border-gray-200">
      <form action="<?= site_url('admin/manajemen-akun/update/' . $user->id) ?>" method="post">
         <?= csrf_field() ?>

         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
               <label class="block text-sm font-medium text-gray-700">Username</label>
               <input type="text" name="username"
                  value="<?= old('username', $user->username) ?>"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700">Email <span class="text-gray-400">(Sebagai ID untuk login)</span> </label>
               <input type="email" name="email"
                  value="<?= old('email', $user->email) ?>"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700">Password Baru <span class="text-gray-400"">(Opsional)</span></label>
               <input type="password" name="password"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
               <input type="text" name="nama_lengkap"
                  value="<?= old('nama_lengkap', $user->nama_lengkap) ?>"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700">Organisasi</label>
               <input type="text" name="organisasi"
                  value="<?= old('organisasi', $user->organisasi) ?>"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
               <label class="block text-sm font-medium text-gray-700">Level Akses</label>
               <select name="id_role"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
                  <?php foreach ($roles as $role) : ?>
                     <option value="<?= $role['id_role'] ?>"
                        <?= old('id_role', $user->id_role) == $role['id_role'] ? 'selected' : '' ?>>
                        <?= esc($role['nama_role']) ?>
                     </option>
                  <?php endforeach ?>
               </select>
            </div>

            <div class="md:col-span-2">
               <label class="block text-sm font-medium text-gray-700">Kontak</label>
               <input type="text" name="kontak"
                  value="<?= old('kontak', $user->kontak) ?>"
                  class="mt-1 w-full rounded-md bg-gray-100 border border-gray-300 px-3 py-2
               focus:border-blue-500 focus:ring-blue-500">
            </div>

         </div>

         <div class="mt-6 flex justify-end space-x-3">
            <a href="<?= site_url('admin/manajemen-akun') ?>"
               class="px-4 py-2 rounded bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
               Batal
            </a>

            <button type="submit"
               class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700 transition">
               Simpan Perubahan
            </button>
         </div>
      </form>
   </div>

</div>
<?= $this->endSection(); ?>