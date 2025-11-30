<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">HALAMAN PEMINJAMAN SAYA</h1>
                    <?php if (isset($breadcrumbs)) : ?>
                        <div class="mt-2">
                            <?= render_breadcrumb($breadcrumbs); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <a href="<?= site_url('peminjam/peminjaman/new') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Tambah Pinjaman Baru</span>
                </a>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <div class="mb-8 flex flex-wrap gap-4 items-center">
                <div class="relative flex-grow" style="min-width: 300px;">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Cari berdasarkan nama atau kode"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-gray-600 font-medium">Kategori:</label>
                    <select class="border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:border-blue-500">
                        <option>Semua</option>
                        <option>Sarana</option>
                        <option>Prasarana</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-gray-600 font-medium">Lokasi:</label>
                    <select class="border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:border-blue-500">
                        <option>Semua</option>
                        <option>Gedung A</option>
                        <option>Gedung B</option>
                    </select>
                </div>
            </div>

            <!-- flash data untuk menampilkan bahwa pengajuan peminjaman lebih dari 24 jam dibatalkan -->
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
                            Pengajuan yang tidak ditindaklanjuti (diverifikasi) oleh Admin/TU dalam waktu <strong>24 Jam</strong> akan otomatis <strong>DIBATALKAN</strong> oleh sistem.
                            <br>
                            Jika status berubah menjadi "Dibatalkan", silakan ajukan peminjaman ulang.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-blue-600 border-blue-600"
                            id="active-tab" data-tabs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                            Peminjaman Aktif
                            <?php if (count($activeLoans) > 0): ?>
                                <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= count($activeLoans) ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-gray-500"
                            id="history-tab" data-tabs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                            Riwayat Selesai
                        </button>
                    </li>
                </ul>
            </div>

            <div id="myTabContent">

                <div class="" id="active" role="tabpanel" aria-labelledby="active-tab">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full min-w-max">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Item</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php if (empty($activeLoans)) : ?>
                                            <tr>
                                                <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                                    Belum ada data peminjaman.
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                            <?php foreach ($activeLoans as $loan) : ?>
                                                <tr class="hover:bg-gray-50 transition">
                                                    <td class="py-4 px-6 whitespace-nowrap">
                                                        <div class="flex flex-col">
                                                            <span class="font-medium text-gray-900"><?= esc($loan['nama_item']); ?></span>
                                                            <span class="text-xs text-gray-500"><?= esc($loan['tipe']); ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                        <?= esc($loan['kode']); ?>
                                                    </td>
                                                    <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                        <div class="flex flex-col">
                                                            <span><?= esc($loan['kegiatan']); ?></span>
                                                            <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($loan['tgl_pinjam'])) ?></span>
                                                        </div>
                                                    </td>
                                                    <td class="py-4 px-6 whitespace-nowrap">
                                                        <?php
                                                        $badgeClass = 'font-bold';
                                                        switch ($loan['status']) {
                                                            case 'Diajukan':
                                                                break;
                                                            case 'Disetujui':
                                                                break;
                                                            case 'Dipinjam':
                                                                break;
                                                            case 'Selesai':
                                                                break;
                                                            case 'Dibatalkan':
                                                                break;
                                                        }
                                                        ?>
                                                        <span class="text-sm font-bold px-3 py-1 rounded-full <?= $badgeClass ?>">
                                                            <?= esc($loan['status']); ?>
                                                        </span>
                                                    </td>
                                                    <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                                        <?php if ($loan['status'] == 'Diajukan'): ?>
                                                            <form action="<?= site_url('peminjam/peminjaman/delete-item/' . $loan['tipe'] . '/' . $loan['id_detail']) ?>"
                                                                method="post"
                                                                onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-200 border border-red-300 rounded-lg text-xs font-medium transition-colors">
                                                                    Batal
                                                                </button>
                                                            </form>
                                                        <?php elseif ($loan['status'] == 'Disetujui' || $loan['status'] == 'Dipinjam'): ?>
                                                            <?php if (!empty($loan['catatan_penolakan']) && empty($loan['foto_sebelum'])): ?>
                                                                <div class="my-4">
                                                                    <button type="button"
                                                                        data-reason="<?= esc($loan['catatan_penolakan']) ?>"
                                                                        onclick="openRejectionModal(this)"
                                                                        class="text-xs text-red-600 hover:text-red-800 underline font-medium flex items-center gap-1">
                                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                        </svg>
                                                                        Lihat Alasan Penolakan
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>

                                                            <?php if (empty($loan['foto_sebelum'])): ?>
                                                                <button type="button"
                                                                    onclick="openUploadModal('sebelum', '<?= $loan['tipe'] ?>', '<?= $loan['id_detail'] ?>', '<?= esc($loan['nama_item']) ?>')"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-600 hover:bg-yellow-300 border border-yellow-600 rounded-lg text-xs font-medium transition-colors">
                                                                    Upload Foto <br>SEBELUM<span class="text-red-500 text-xl">*</span>
                                                                </button>

                                                            <?php else: ?>
                                                                <?php if (empty($loan['foto_sesudah'])): ?>
                                                                    <button type="button"
                                                                        onclick="openUploadModal('sesudah', '<?= $loan['tipe'] ?>', '<?= $loan['id_detail'] ?>', '<?= esc($loan['nama_item']) ?>')"
                                                                        class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-600 hover:bg-green-300 border border-green-600 rounded-lg text-xs font-medium transition-colors">
                                                                        Kembalikan
                                                                    </button>
                                                                <?php else: ?>
                                                                    <span class="text-gray-500 text-xs italic">Menunggu Verifikasi Admin</span>
                                                                <?php endif; ?>

                                                            <?php endif; ?>

                                                        <?php elseif ($loan['status'] == 'Selesai'): ?>
                                                            <a href="<?= site_url('peminjam/histori-peminjaman/detail/' . esc($loan['id_peminjaman'])) ?>"
                                                                class="inline-flex items-center px-3 py-1.5 bg-neutral-100 text-neutral-600 hover:bg-neutral-300 border border-neutral-600 rounded-lg text-xs font-medium transition-colors">
                                                                Lihat Riwayat
                                                            </a>
                                                        <?php else: ?>
                                                            <?php foreach ($peminjaman as $p): ?>
                                                                <button type="button"
                                                                    onclick="openDetailPenolakanModal(this)"
                                                                    data-alasan="<?= esc($p['keterangan']) ?>"
                                                                    class="inline-flex items-center px-3 py-1.5 bg-neutral-100 text-neutral-600 hover:bg-neutral-300 border border-neutral-600 rounded-lg text-xs font-medium transition-colors">
                                                                    Lihat Alasan..
                                                                </button>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <div id="rejectionModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeRejectionModal()"></div>
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border-l-4 border-red-500">
                                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                            <div class="sm:flex sm:items-start">
                                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                        Foto Bukti Ditolak
                                                    </h3>
                                                    <div class="mt-2">
                                                        <p class="text-sm text-gray-500">
                                                            Admin telah menolak foto bukti yang Anda lampirkan dengan alasan berikut:
                                                        </p>
                                                        <div class="mt-3 p-3 bg-red-50 rounded-md text-red-800 text-sm font-medium" id="rejectionReasonText">
                                                        </div>
                                                        <p class="text-xs text-gray-400 mt-3">
                                                            Silakan upload ulang foto yang sesuai pada tombol "Upload Foto" di tabel.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                            <button type="button" onclick="closeRejectionModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                                Saya Paham
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 flex justify-between items-center">
                                <span class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">1-5</span> dari <span class="font-medium">100</span>
                                </span>
                                <nav class="flex space-x-1">
                                    <a href="#" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Sebelumnya</a>
                                    <a href="#" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">1</a>
                                    <a href="#" class="py-2 px-3 rounded-lg bg-blue-100 text-blue-600 font-medium">2</a>
                                    <a href="#" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">3</a>
                                    <a href="#" class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Berikutnya</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="hidden" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-100 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Nama Item</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (empty($historyLoans)) : ?>
                                        <tr>
                                            <td colspan="5" class="py-6 px-6 text-center text-gray-500">Belum ada riwayat peminjaman.</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($historyLoans as $loan) : ?>
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-4 px-6">
                                                    <div class="flex flex-col">
                                                        <span class="font-medium text-gray-900"><?= esc($loan['nama_item']); ?></span>
                                                        <span class="text-xs text-gray-500"><?= esc($loan['tipe']); ?> (<?= esc($loan['kode']); ?>)</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-700"><?= esc($loan['kegiatan']); ?></td>
                                                <td class="py-4 px-6 text-sm text-gray-500">
                                                    <?= date('d M Y', strtotime($loan['tgl_selesai'])) ?>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <?php
                                                    $color = 'bg-gray-100 text-gray-800';
                                                    if ($loan['status'] == 'Selesai') $color = 'bg-green-100 text-green-800';
                                                    if ($loan['status'] == 'Ditolak') $color = 'bg-red-100 text-red-800';
                                                    if ($loan['status'] == 'Dibatalkan') $color = 'bg-gray-200 text-gray-600';
                                                    ?>
                                                    <span class="text-xs font-bold px-3 py-1 rounded-full <?= $color ?>">
                                                        <?= esc($loan['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <?php if ($loan['status'] == 'Selesai'): ?>
                                                        <a href="<?= site_url('peminjam/histori-peminjaman/detail/' . esc($loan['id_peminjaman'])) ?>"
                                                            class="inline-flex items-center px-3 py-1.5 bg-neutral-100 text-neutral-600 hover:bg-neutral-300 border border-neutral-600 rounded-lg text-xs font-medium transition-colors">
                                                            Lihat Riwayat
                                                        </a>
                                                    <?php else: ?>
                                                        <button type="button"
                                                            onclick="openDetailPenolakanModal(this)"
                                                            data-alasan="<?= esc($loan['keterangan']) ?>"
                                                            class="inline-flex items-center px-3 py-1.5 bg-neutral-100 text-neutral-600 hover:bg-neutral-300 border border-neutral-600 rounded-lg text-xs font-medium transition-colors">
                                                            Lihat Alasan
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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
                                        <label class="block text-sm font-medium text-gray-700">Foto Bukti (Wajib)</label>
                                        <input type="file" name="foto_bukti" required accept="image/*" class="px-2 py-2 mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                    </div>

                                    <div id="kondisiInputContainer" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700">Kondisi Sarana/Prasarana Saat Ini</label>
                                        <select name="kondisi_akhir" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                                    Simpan
                                </button>
                                <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="returnModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeReturnModal()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <form id="formReturn" action="" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Form Pengembalian Barang</h3>
                                <p class="text-sm text-gray-500 mt-1">Item: <b id="returnItemName"></b></p>

                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Foto Bukti Pengembalian <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file" name="foto_sesudah" required accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                        <p class="text-xs text-gray-500 mt-1">Upload foto kondisi barang saat dikembalikan.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Kondisi Barang <span class="text-red-500">*</span></label>
                                        <select name="kondisi_akhir" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                                    Kirim & Kembalikan
                                </button>
                                <button type="button" onclick="closeReturnModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal Detail Alasan Penolakan -->
            <div id="detailPenolakanModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-black bg-opacity-50">
                <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                    <div class="flex items-center justify-between pb-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Alasan Penolakan/Pembatalan</h3>
                        <button onclick="closeDetailPenolakanModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-4">
                        <p id="alasanPenolakanText" class="text-sm text-gray-700">
                            <!-- Alasan akan dimasukkan di sini oleh JavaScript -->
                        </p>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button onclick="closeDetailPenolakanModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const activeTab = document.getElementById('active-tab');
        const historyTab = document.getElementById('history-tab');
        const activeContent = document.getElementById('active');
        const historyContent = document.getElementById('history');

        function switchTab(showActive) {
            if (showActive) {
                activeContent.classList.remove('hidden');
                historyContent.classList.add('hidden');

                activeTab.classList.add('text-blue-600', 'border-blue-600');
                activeTab.classList.remove('text-gray-500', 'border-transparent');

                historyTab.classList.add('text-gray-500', 'border-transparent');
                historyTab.classList.remove('text-blue-600', 'border-blue-600');
            } else {
                activeContent.classList.add('hidden');
                historyContent.classList.remove('hidden');

                historyTab.classList.add('text-blue-600', 'border-blue-600');
                historyTab.classList.remove('text-gray-500', 'border-transparent');

                activeTab.classList.add('text-gray-500', 'border-transparent');
                activeTab.classList.remove('text-blue-600', 'border-blue-600');
            }
        }

        activeTab.addEventListener('click', () => switchTab(true));
        historyTab.addEventListener('click', () => switchTab(false));
    });

    // upload-foto-sebelum
    function openUploadModal(jenis, tipeItem, idDetail, namaItem) {
        const form = document.getElementById('formUploadBukti');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');
        const kondisiDiv = document.getElementById('kondisiInputContainer');
        const kondisiInput = kondisiDiv.querySelector('select');

        document.getElementById('uploadModal').classList.remove('hidden');

        if (jenis === 'sebelum') {
            // Mode Ambil Barang
            form.action = '<?= site_url("peminjam/peminjaman/upload-bukti-sebelum/") ?>' + tipeItem + '/' + idDetail;
            title.innerText = 'Bukti Pengambilan Barang';
            desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengambilnya.';
            kondisiDiv.classList.add('hidden'); // Sembunyikan input kondisi
            kondisiInput.required = false;
        } else {
            // Mode Kembalikan Barang
            form.action = '<?= site_url("peminjam/peminjaman/upload-bukti-sesudah/") ?>' + tipeItem + '/' + idDetail;
            title.innerText = 'Bukti Pengembalian';
            desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengembalikannya.';
            kondisiDiv.classList.remove('hidden'); // Munculkan input kondisi
            kondisiInput.required = true;
        }
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    // pengembalian + upload foto sesudah
    function openReturnModal(tipe, idDetail, namaItem) {
        const form = document.getElementById('formReturn');

        // Set action URL ke method uploadBuktiSesudah di PeminjamanController
        // Pastikan rute ini sudah ada di Routes.php!
        form.action = '<?= site_url("peminjam/peminjaman/upload-bukti-sesudah/") ?>' + tipe + '/' + idDetail;

        document.getElementById('returnItemName').innerText = namaItem;
        document.getElementById('returnModal').classList.remove('hidden');
    }

    function closeReturnModal() {
        document.getElementById('returnModal').classList.add('hidden');
    }

    // penolakan
    function openDetailPenolakanModal(buttonElement) {
        // 1. Ambil alasan dari atribut data-alasan
        const alasan = buttonElement.getAttribute('data-alasan');

        // 2. Ekstrak pesan penolakan yang sebenarnya
        // Method reject() di controller Anda menambahkan prefix "[DITOLAK: ...]"
        // Kita akan coba cari dan bersihkan itu untuk tampilan yang lebih baik.
        let displayAlasan = alasan;
        const match = alasan.match(/\[DITOLAK:\s*(.*?)\]/);
        if (match && match[1]) {
            displayAlasan = match[1];
        }

        // 3. Tampilkan alasan di dalam modal
        const modalTextElement = document.getElementById('alasanPenolakanText');
        modalTextElement.textContent = displayAlasan.trim() ? displayAlasan : 'Tidak ada alasan spesifik yang diberikan.';

        // 4. Tampilkan modal
        const modal = document.getElementById('detailPenolakanModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDetailPenolakanModal() {
        const modal = document.getElementById('detailPenolakanModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Opsional: Tutup modal jika user menekan tombol Escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailPenolakanModal();
        }
    });

    function openRejectionModal(button) {
        // Ambil data alasan dari atribut data-reason pada tombol yang diklik
        const reason = button.getAttribute('data-reason');

        // Isi teks ke dalam modal
        document.getElementById('rejectionReasonText').innerText = reason;

        // Tampilkan modal
        document.getElementById('rejectionModal').classList.remove('hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejectionModal').classList.add('hidden');
    }
</script>

<?= $this->endSection(); ?>