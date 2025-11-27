<?php $pager->setSurroundCount(2) ?>

<nav aria-label="Page navigation">
   <ul class="inline-flex -space-x-px text-sm">
      <?php if ($pager->hasPrevious()) : ?>
         <li>
            <a href="<?= $pager->getFirst() ?>" class="flex items-center justify-center px-3 h-8 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700">
               <span aria-hidden="true">&laquo;</span>
            </a>
         </li>
         <li>
            <a href="<?= $pager->getPrevious() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
               <span aria-hidden="true">&lsaquo;</span>
            </a>
         </li>
      <?php endif ?>

      <?php foreach ($pager->links() as $link) : ?>
         <li>
            <a href="<?= $link['uri'] ?>" class="flex items-center justify-center px-3 h-8 leading-tight border border-gray-300 <?= $link['active'] ? 'text-blue-600 border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700' : 'text-gray-500 bg-white hover:bg-gray-100 hover:text-gray-700' ?>">
               <?= $link['title'] ?>
            </a>
         </li>
      <?php endforeach ?>

      <?php if ($pager->hasNext()) : ?>
         <li>
            <a href="<?= $pager->getNext() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
               <span aria-hidden="true">&rsaquo;</span>
            </a>
         </li>
         <li>
            <a href="<?= $pager->getLast() ?>" class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700">
               <span aria-hidden="true">&raquo;</span>
            </a>
         </li>
      <?php endif ?>
   </ul>
</nav>