<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Manajemen Laporan Kerusakan</h2>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
   <?php endif; ?>

   <?php if (isset($breadcrumbs)) : ?>
      <div class="mt-2">
         <?= render_breadcrumb($breadcrumbs); ?>
      </div>
   <?php endif; ?>

   <div class="mb-4 border-b border-gray-200">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="damageTab" data-tabs-toggle="#damageTabContent" role="tablist">
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg hover:text-blue-600 hover:border-blue-600"
               id="sarana-tab" type="button" role="tab" aria-controls="sarana" aria-selected="true"
               onclick="switchTab('sarana')">
               Laporan Sarana
            </button>
         </li>
         <li class="mr-2">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300"
               id="prasarana-tab" type="button" role="tab" aria-controls="prasarana" aria-selected="false"
               onclick="switchTab('prasarana')">
               Laporan Prasarana
            </button>
         </li>
      </ul>
   </div>

   <div id="damageTabContent">

      <div class="" id="sarana-panel" role="tabpanel">
         <?= $this->include('admin/components/table_laporan', ['dataLaporan' => $laporanSarana, 'tipe' => 'Sarana']) ?>
      </div>

      <div class="hidden" id="prasarana-panel" role="tabpanel">
         <?= $this->include('admin/components/table_laporan', ['dataLaporan' => $laporanPrasarana, 'tipe' => 'Prasarana']) ?>
      </div>

   </div>
</div>

<script>
   function switchTab(tabName) {
      // Logic sederhana switch class hidden
      document.getElementById('sarana-panel').classList.add('hidden');
      document.getElementById('prasarana-panel').classList.add('hidden');

      // Reset style tombol
      document.getElementById('sarana-tab').className = "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300";
      document.getElementById('prasarana-tab').className = "inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300";

      // Aktifkan yang dipilih
      document.getElementById(tabName + '-panel').classList.remove('hidden');
      document.getElementById(tabName + '-tab').className = "inline-block p-4 border-b-2 text-blue-600 border-blue-600 rounded-t-lg active";
   }
</script>

<?= $this->endSection(); ?>