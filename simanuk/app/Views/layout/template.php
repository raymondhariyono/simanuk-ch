<!DOCTYPE html>
<html lang="id">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= $this->renderSection('title') ?> | Sistem Manajemen Sarpras </title> 
   <script src="https://cdn.tailwindcss.com"></script>
   <style>
      /* Sembunyikan scrollbar untuk layout utama */
      .no-scrollbar::-webkit-scrollbar {
         display: none;
      }

      .no-scrollbar {
         -ms-overflow-style: none;
         scrollbar-width: none;
      }
   </style>
</head>

<body class="bg-gray-100 font-inter">
   <div class="flex h-screen bg-gray-200">
      <?php
      // Tampilkan sidebar hanya jika showSidebar bernilai true atau tidak didefinisikan
      if (isset($showSidebar) && $showSidebar === true) : ?>
         <?= $this->include('layout/sidebar') ?>
      <?php endif; ?>

      <!-- Content Area -->
      <div class="flex-1 flex flex-col overflow-hidden"> <!-- Header (Opsional, bisa ditambahkan di sini) -->
         <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800"> Fakultas Teknik - SarPras </h1>
            <div class="text-sm text-gray-800"> <?= auth()->user()->organisasi ?? auth()->user()->username ?> (</span><?= auth()->user()->nama_lengkap ?> )</div>
         </header> <!-- Main Content -->
         <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 no-scrollbar"> <!-- Di sinilah konten view spesifik akan dimuat --> <?= $this->renderSection('content') ?> </main>
      </div>

   </div>
</body>

</html>