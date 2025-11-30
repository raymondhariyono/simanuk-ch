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
   
   <div class="bg-white p-4 flex justify-between items-center shadow-sm md:hidden z-20 sticky top-0">
      <span class="font-bold text-lg text-gray-800">Menu</span>
      
      <button id="sidebarToggle" class="text-gray-600 focus:outline-none hover:text-gray-900 p-2 rounded-md hover:bg-gray-100">
         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
         </svg>
      </button>
   </div>

   <div class="flex flex-col md:flex-row h-screen bg-gray-200">
      
      <?php
      // PERBAIKAN LOGIKA:
      // Tampilkan sidebar jika variabel $showSidebar TIDAK diset (default), 
      // ATAU jika diset ke true.
      // Hanya sembunyikan jika secara eksplisit diset ke false (misal di halaman login).
      if (!isset($showSidebar) || $showSidebar === true) : ?>
         <?= $this->include('layout/sidebar') ?>
      <?php endif; ?>

      <div class="flex-1 flex flex-col overflow-hidden"> 
         
         <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-800"> Fakultas Teknik - SarPras </h1>
            <div class="text-sm text-gray-800"> 
               <?= auth()->user()->organisasi ?? auth()->user()->username ?> (<?= auth()->user()->nama_lengkap ?>)
            </div>
         </header> 
         
         <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-4 md:p-6 no-scrollbar"> 
            <?= $this->renderSection('content') ?> 
         </main>
      </div>

   </div>

   <script>
      document.addEventListener('DOMContentLoaded', () => {
         const sidebarToggle = document.getElementById('sidebarToggle');
         const sidebar = document.getElementById('sidebar');

         if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', (e) => {
               e.preventDefault();
               // Toggle visibilitas sidebar
               sidebar.classList.toggle('hidden');
               sidebar.classList.toggle('flex');
            });
         }
      });
   </script>
</body>

</html>