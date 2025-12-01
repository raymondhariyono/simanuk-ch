<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-4 py-10 mx-auto max-w-5xl">

   <div class="flex justify-between items-center mb-8">
      <div>
         <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Histori Peminjaman</h2>
         <p class="mt-1 text-sm text-gray-500">Daftar riwayat kegiatan dan peminjaman aset Anda.</p>
         <?php if (isset($breadcrumbs)) : ?>
            <div class="mt-2">
               <?= render_breadcrumb($breadcrumbs); ?>
            </div>
         <?php endif; ?>
      </div>
      <a href="<?= site_url('peminjam/peminjaman/new') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
         </svg>Peminjaman Baru
      </a>
   </div>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
         <?= session()->getFlashdata('message') ?>
      </div>
   <?php endif; ?>

   <?php if (session()->has('error')) : ?>
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm relative" role="alert">
         <strong class="font-bold">Gagal Upload!</strong>
         <span class="block sm:inline"><?= session('error') ?></span>
         <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.remove();">
            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
               <title>Close</title>
               <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
            </svg>
         </span>
      </div>
   <?php endif; ?>

   <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-sm">
      <div class="flex">
         <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
               <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
         </div>
         <div class="ml-3">
            <p class="text-sm text-yellow-700">
               <span class="font-bold">Aturan Peminjaman:</span>
               Pengajuan yang tidak ditindaklanjuti (diverifikasi) oleh Admin/TU dalam waktu <strong>24 Jam</strong> akan otomatis <strong>DIBATALKAN</strong>.
            </p>
         </div>
      </div>
   </div>

   <div class="mb-6 border-b border-gray-200">
      <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
         <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-blue-600 border-blue-600"
               id="active-tab" type="button" role="tab">
               Peminjaman Aktif
               <?php if (count($activeLoans) > 0): ?>
                  <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= count($activeLoans) ?></span>
               <?php endif; ?>
            </button>
         </li>
         <li class="mr-2" role="presentation">
            <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-gray-500"
               id="history-tab" type="button" role="tab">
               Riwayat Selesai
            </button>
         </li>
      </ul>
   </div>

   <div id="myTabContent">

      <div id="active" role="tabpanel">
         <?php if (empty($activeLoans)) : ?>
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
               <p class="text-gray-500">Tidak ada peminjaman aktif.</p>
            </div>
         <?php else : ?>
            <div class="space-y-4">
               <?php foreach ($activeLoans as $h) : ?>
                  <?= $this->setData(['h' => $h, 'isHistory' => false])->render('peminjam/components/accordion_item') ?>
               <?php endforeach; ?>
            </div>

            <div class="mt-4 flex justify-end">
               <?= $pager->links('active', 'tailwind_pagination') ?>
            </div>
         <?php endif; ?>
      </div>

      <div id="history" class="hidden" role="tabpanel">
         <?php if (empty($historyLoans)) : ?>
            <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
               <p class="text-gray-500">Belum ada riwayat peminjaman selesai.</p>
            </div>
         <?php else : ?>
            <div class="space-y-4">
               <?php foreach ($historyLoans as $h) : ?>
                  <?= $this->setData(['h' => $h, 'isHistory' => true])->render('peminjam/components/accordion_item') ?>
               <?php endforeach; ?>

               <div class="mt-4 flex justify-end">
                  <?= $pager->links('history', 'tailwind_pagination') ?>
               </div>
            </div>
         <?php endif; ?>
      </div>

   </div>

</div>

<div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeUploadModal()"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
         <form id="formUploadBukti" action="" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
               <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Upload Bukti</h3>
               <div class="mt-2 space-y-4">
                  <p class="text-sm text-gray-500" id="modalDescription"></p>

                  <div>
                     <label class="block text-sm font-medium text-gray-700">
                        Foto Bukti (Wajib) <span class="text-red-500">*</span>
                     </label>

                     <input
                        type="file"
                        name="foto_bukti"
                        required
                        accept="image/png, image/jpeg, image/jpg"
                        onchange="validateFileUpload(this)"
                        class="px-2 py-2 mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">

                     <p class="text-xs text-gray-500 mt-1">Format: JPG/JPEG/PNG. Maksimal: 2MB.</p>
                     <p id="fileErrorMsg" class="text-xs text-red-600 mt-1 hidden font-bold"></p>
                  </div>

                  <div id="kondisiInputContainer" class="hidden">
                     <label class="block text-sm font-medium text-gray-700">Kondisi Sarana/Prasarana Saat Ini</label>
                     <select name="kondisi_akhir" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm sm:text-sm">
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
               <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
               <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
            </div>
         </form>
      </div>
   </div>
</div>

<div id="detailPenolakanModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
   <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
      <div class="flex items-center justify-between pb-3 border-b">
         <h3 class="text-lg font-semibold text-gray-900">Alasan Penolakan</h3>
         <button onclick="closeDetailPenolakanModal()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
         </button>
      </div>
      <div class="mt-4">
         <p id="alasanPenolakanText" class="text-sm text-gray-700"></p>
      </div>
      <div class="flex justify-end mt-6">
         <button onclick="closeDetailPenolakanModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Tutup</button>
      </div>
   </div>
</div>

<div id="rejectionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
   <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectionModal()"></div>
      <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border-l-4 border-red-500">
         <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
               <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                  </svg>
               </div>
               <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                  <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Foto Bukti Ditolak</h3>
                  <div class="mt-2">
                     <p class="text-sm text-gray-500">Admin telah menolak foto bukti yang Anda lampirkan:</p>
                     <div class="mt-3 p-3 bg-red-50 rounded-md text-red-800 text-sm font-medium" id="rejectionReasonText"></div>
                  </div>
               </div>
            </div>
         </div>
         <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button type="button" onclick="closeRejectionModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Saya Paham</button>
         </div>
      </div>
   </div>
</div>

<script>
   const SITE_URL = "<?= site_url() ?>";
</script>

<script src="<?= base_url('js/peminjam/histori_peminjaman.js') ?>"></script>

<?= $this->endSection(); ?>