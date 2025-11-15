<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container-fluid bg-gray-100">

   <!-- Pesan Sukses atau Error -->
   <?php if (session()->getFlashdata('msg')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
         <?= session()->getFlashdata('msg') ?>
      </div>
   <?php endif; ?>
   <?php if (session()->getFlashdata('error')) : ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
         <?= session()->getFlashdata('error') ?>
      </div>
   <?php endif; ?>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <!-- <div class="mb-6">
      <a href="javascript:history.back()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
         <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
         </svg>
         Kembali
      </a>
   </div> -->

   <div class="flex min-h-screen">
      <div class="flex-1 flex flex-col overflow-hidden">
         <main class="flex-1 p-6 md:p-8 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 max-w-4xl mx-auto">

               <!-- Header Profil -->
               <div class="flex items-center space-x-4 mb-6 pb-6 border-b">
                  <div>
                     <h2 class="text-2xl font-bold text-gray-900"><?= esc($user->username); ?></h2>
                     <p class="text-gray-600"><?= esc($user->email); ?></p>
                     <p class="text-gray-500 text-sm">Mahasiswa</p>
                  </div>
               </div>

               <!-- Form Update Profil -->
               <h3 class="text-lg font-semibold text-gray-800 mb-4">Perbarui Profil</h3>
               <form action="<?= base_url('profile/update'); ?>" method="post" class="space-y-5 mb-8">
                  <?= csrf_field(); ?>

                  <div>
                     <label for="email" class="block text-sm font-medium text-gray-600 mb-2">Alamat Email</label>
                     <input type="email" id="email" name="email" value="<?= esc($user->email); ?>" readonly
                        class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                     <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                  </div>

                  <div>
                     <label for="username" class="block text-sm font-medium text-gray-600 mb-2">Username</label>
                     <input type="text" id="username" name="username" value="<?= esc($user->username); ?>"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                  </div>

                  <button type="submit"
                     class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-semibold">
                     Update Profil
                  </button>
               </form>

               <!-- Form Ganti Password -->
               <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Kata Sandi</h3>
               <form action="<?= base_url('profile/password'); ?>" method="post" class="space-y-5">
                  <?= csrf_field(); ?>

                  <div>
                     <label for="password_lama" class="block text-sm font-medium text-gray-600 mb-2">Kata Sandi Lama</label>
                     <input type="password" id="password_lama" name="password_lama" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                  </div>

                  <div>
                     <label for="password_baru" class="block text-sm font-medium text-gray-600 mb-2">Kata Sandi Baru</label>
                     <input type="password" id="password_baru" name="password_baru" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                  </div>

                  <div>
                     <label for="konfirmasi_password" class="block text-sm font-medium text-gray-600 mb-2">Konfirmasi Kata Sandi Baru</label>
                     <input type="password" id="konfirmasi_password" name="konfirmasi_password" required
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                  </div>

                  <button type="submit"
                     class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md font-semibold">
                     Ubah Kata Sandi
                  </button>
               </form>

            </div>
         </main>
      </div>
   </div>

</div>
<?= $this->endSection(); ?>