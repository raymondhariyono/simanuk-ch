<?php $pager->setSurroundCount(1); ?>
<div class="p-4 flex justify-end">
   <!-- <span class="text-sm text-gray-700"> Menampilkan
      <span class="font-medium"><?= $pager->getFirst() ?>
      </span> sampai
      <span class="font-medium">
         // <?= $pager->getLast() ?>
      </span> dari
      <span class="font-medium">
         // <?= $pager->getTotal() ?>
      </span>
   </span> -->

   <nav class="flex space-x-1">
      <?php if ($pager->hasPrevious()) : ?>
         <a href="<?= $pager->getPrevious() ?>" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Sebelumnya</a>
      <?php endif ?>

      <?php foreach ($pager->links() as $link) : ?>
         <a href="<?= $link['uri'] ?>"
            class="py-2 px-3 rounded-lg <?= $link['active'] ? 'bg-blue-100 text-blue-600 font-medium' : 'text-gray-600 hover:bg-gray-100' ?>">
            <?= $link['title'] ?>
         </a>
      <?php endforeach ?>

      <?php if ($pager->hasNext()) : ?>
         <a href="<?= $pager->getNext() ?>" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Berikutnya</a>
      <?php endif ?>
   </nav>


</div>