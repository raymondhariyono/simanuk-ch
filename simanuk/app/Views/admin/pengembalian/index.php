<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
   <h2 class="text-2xl font-semibold text-gray-700 mb-6">Verifikasi Pengembalian</h2>

   <?php if (isset($breadcrumbs)) : ?>
      <?= render_breadcrumb($breadcrumbs); ?>
   <?php endif; ?>

   <?php if (session()->getFlashdata('message')) : ?>
      <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
   <?php endif; ?>

   <div class="w-full overflow-hidden rounded-lg shadow-xs">
      <div class="w-full overflow-x-auto">
         <table class="w-full whitespace-no-wrap">
            <thead>
               <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                  <th class="px-4 py-3">Peminjam</th>
                  <th class="px-4 py-3">Kegiatan</th>
                  <th class="px-4 py-3">Tenggat Waktu</th>
                  <th class="px-4 py-3">Status</th>
                  <th class="px-4 py-3">Aksi</th>
               </tr>
            </thead>
            <tbody class="bg-white divide-y">
               <?php if (empty($peminjaman)) : ?>
                  <tr>
                     <td colspan="5" class="px-4 py-3 text-center text-gray-500">Tidak ada barang yang sedang dipinjam.</td>
                  </tr>
               <?php else : ?>
                  <?php foreach ($peminjaman as $row) : ?>
                     <tr class="text-gray-700 hover:bg-gray-50">
                        <td class="px-4 py-3">
                           <div class="text-sm font-semibold"><?= esc($row['nama_lengkap']) ?></div>
                           <div class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></div>
                        </td>
                        <td class="px-4 py-3 text-sm"><?= esc($row['kegiatan']) ?></td>
                        <td class="px-4 py-3 text-sm">
                           <?= date('d M Y', strtotime($row['tgl_pinjam_selesai'])) . ' - ' . date('d M Y', strtotime($row['tgl_pinjam_selesai'] . ' + 3 days')) ?>

                           <?php if (date('Y-m-d', strtotime($row['tgl_pinjam_selesai'])) > date('Y-m-d', strtotime($row['tgl_pinjam_selesai'] . ' + 3 days'))) : ?>
                              <span class="ml-2 text-xs text-red-600 font-bold">(Terlambat)</span>
                           <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-xs">
                           <span class="px-2 py-1 font-semibold leading-tight text-indigo-700 bg-indigo-100 rounded-full">
                              Dipinjam
                           </span>
                        </td>
                        <td class="px-4 py-3">
                           <a href="<?= site_url('admin/pengembalian/detail/' . $row['id_peminjaman']) ?>"
                              class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                              Verifikasi
                           </a>
                        </td>
                     </tr>
                  <?php endforeach; ?>
               <?php endif; ?>
            </tbody>
         </table>
      </div>
   </div>
</div>
<?= $this->endSection(); ?>