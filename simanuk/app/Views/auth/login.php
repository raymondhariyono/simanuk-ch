<?= $this->extend('auth/layout') ?>

<?= $this->section('content') ?>

<?php
if (auth()->loggedIn()) {
   echo '<div class="mb-4 p-4 bg-yellow-100 border border-yellow-400 text-yellow-700 rounded">';
   echo 'PERINGATAN: Anda sudah login! <a href="/logout" class="underline">Logout di sini</a>';
   echo '</div>';
}
?>

<!-- Wrapper utama -->
<div class="flex justify-center items-center min-h-screen bg-gray-100 px-4">

   <!-- Card responsif -->
   <div class="bg-white shadow-xl rounded-lg overflow-hidden flex flex-col lg:flex-row w-full max-w-4xl">

      <!-- Bagian form -->
      <div class="w-full lg:w-1/2 p-8 flex flex-col justify-center">
         <h2 class="text-lg font-semibold text-gray-700">Fakultas Teknik</h2>
         <h1 class="text-3xl font-bold mt-2 mb-4 text-gray-800">Selamat Datang!</h1>
         <p class="text-gray-500 mb-6">Silakan masuk untuk mengakses sistem inventaris.</p>

         <form action="<?= url_to('login') ?>" method="post">
            <?= csrf_field() ?>

            <?php if (session('error')): ?>
               <div class="bg-red-100 text-red-600 p-2 rounded mb-4 text-sm">
                  <?= session('error') ?>
               </div>
            <?php elseif (session('errors') !== null): ?>
               <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm">
                  <?php if (is_array(session('errors'))) : ?>
                     <?php foreach (session('errors') as $error) : ?>
                        <p><?= esc($error) ?></p>
                     <?php endforeach ?>
                  <?php else : ?>
                     <p><?= esc(session('errors')) ?></p>
                  <?php endif ?>
               </div>
            <?php endif; ?>

            <label for="email" class="block mb-2 text-sm font-medium text-gray-600">Email</label>
            <div class="relative mb-4">
               <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A1.875 1.875 0 0118 22.5H6a1.875 1.875 0 01-1.501-2.382z" />
                  </svg>
               </div>
               <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="you@example.com"
                  value="<?= old('email') ?>"
                  class="w-full p-2 pl-10 border rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <label for="password" class="block mb-2 text-sm font-medium text-gray-600">Password</label>
            <div class="relative mb-2">
               <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 00-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                  </svg>
               </div>
               <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="••••••••••"
                  class="w-full p-2 pl-10 pr-10 border rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
            </div>

            <div class="mt-2 mb-6 text-left">
               <a href="#" class="text-sm text-blue-600 hover:underline">Lupa Password?</a>
            </div>

            <button type="submit"
               class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-md font-semibold transition">
               Masuk
            </button>
         </form>
      </div>

      <!-- Bagian gambar, hanya tampil di layar besar -->
      <div class="hidden lg:block lg:w-1/2">
         <img src="<?= base_url('images/login/fakultas-teknik.jpg') ?>"
            alt="Fakultas Teknik"
            class="w-full h-full object-cover">
      </div>
   </div>
</div>

<?= $this->endSection() ?>