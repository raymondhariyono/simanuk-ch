<!-- Breadcrumb -->
<nav class="flex mb-4" aria-label="Breadcrumb">
   <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <?php foreach ($breadcrumbs as $index => $crumb) : ?>
         <li>
            <div class="flex items-center">
               <?php if ($index > 0) : ?>
                  <!-- Tampilkan separator untuk item setelah yang pertama -->
                  <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                  </svg>
               <?php endif; ?>

               <?php if (isset($crumb['url'])) : ?>
                  <!-- Jika ada URL, buat menjadi link -->
                  <a href="<?= $crumb['url'] ?>" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">
                     <?= esc($crumb['name']) ?>
                  </a>
               <?php else : ?>
                  <!-- Jika tidak ada URL (item terakhir/aktif), buat menjadi span -->
                  <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">
                     <?= esc($crumb['name']) ?>
                  </span>
               <?php endif; ?>
            </div>
         </li>
      <?php endforeach; ?>
   </ol>
</nav>