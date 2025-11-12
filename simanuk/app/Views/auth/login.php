<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>

<div class="bg-white shadow-lg rounded-lg flex overflow-hidden w-[800px]">
   <!-- Kiri -->
   <div class="w-1/2 p-10 flex flex-col justify-center">
      <h2 class="text-lg font-semibold text-gray-700">Fakultas Teknik</h2>
      <h1 class="text-3xl font-bold mt-2 mb-4">Selamat Datang!</h1>
      <p class="text-gray-500 mb-6">Silakan masuk untuk mengakses sistem inventaris.</p>

      <form action="<?= url_to('login') ?>" method="post">
         <?= csrf_field() ?>

         <?php if (session('error')): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4">
               <?= session('error') ?>
            </div>
         <?php endif; ?>

         <?php if (session('errors.login') || session('errors.password')): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4">
               <?= session('errors.login') ?? '' ?><br>
               <?= session('errors.password') ?? '' ?>
            </div>
         <?php endif; ?>

         <label class="block mb-2 text-sm font-medium text-gray-600">Email / Username</label>
         <input type="text" name="login" placeholder="you@example.com"
            value="<?= old('login') ?>"
            class="w-full p-2 border rounded-md mb-4 focus:ring-2 focus:ring-blue-400">

         <label class="block mb-2 text-sm font-medium text-gray-600">Password</label>
         <input type="password" name="password" placeholder="••••••••"
            class="w-full p-2 border rounded-md mb-4 focus:ring-2 focus:ring-blue-400">

         <div class="text-right mb-4">
            <!-- url forgot-password -->
         </div>

         <button type="submit" <?= lang('Auth.login') ?>
            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md font-semibold">
            Masuk
         </button>
      </form>
   </div>

   <!-- Kanan -->
   <div class="w-1/2">
      <img src="<?= base_url('images/fakultas-teknik.jpg') ?>" alt="Fakultas Teknik"
         class="w-full h-full object-cover">
   </div>
</div>

<?= $this->endSection() ?>