<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4 md:p-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Peminjaman Saya</h1>
                    <?php if (isset($breadcrumbs)) : ?>
                        <div class="mt-2 overflow-x-auto">
                            <?= render_breadcrumb($breadcrumbs); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <a href="<?= site_url('peminjam/peminjaman/new') ?>" 
                   class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex justify-center items-center space-x-2 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Ajukan Baru</span>
                </a>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <div class="mb-8 grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" placeholder="Cari nama barang / kode..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm">
                </div>
                
                <div class="md:col-span-3">
                    <select class="w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:border-blue-500 text-sm bg-white">
                        <option value="">Semua Kategori</option>
                        <option value="Sarana">Sarana</option>
                        <option value="Prasarana">Prasarana</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <select class="w-full border border-gray-300 rounded-lg shadow-sm py-2.5 px-3 focus:border-blue-500 text-sm bg-white">
                        <option value="">Semua Lokasi</option>
                        <option value="Gedung A">Gedung A</option>
                        <option value="Gedung B">Gedung B</option>
                    </select>
                </div>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <span class="font-bold block mb-1">Aturan Peminjaman:</span>
                            Pengajuan yang tidak diverifikasi dalam waktu <strong>24 Jam</strong> otomatis <strong>DIBATALKAN</strong>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-6 border-b border-gray-200 overflow-x-auto no-scrollbar">
                <ul class="flex flex-nowrap -mb-px text-sm font-medium text-center min-w-max" id="myTab" data-tabs-toggle="#myTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-blue-600 border-blue-600 whitespace-nowrap"
                            id="active-tab" data-tabs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">
                            Peminjaman Aktif
                            <?php if (count($activeLoans) > 0): ?>
                                <span class="ml-2 bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded-full"><?= count($activeLoans) ?></span>
                            <?php endif; ?>
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 text-gray-500 whitespace-nowrap"
                            id="history-tab" data-tabs-target="#history" type="button" role="tab" aria-controls="history" aria-selected="false">
                            Riwayat Selesai
                        </button>
                    </li>
                </ul>
            </div>

            <div id="myTabContent">

                <div class="" id="active" role="tabpanel" aria-labelledby="active-tab">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan & Tanggal</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (empty($activeLoans)) : ?>
                                        <tr>
                                            <td colspan="5" class="py-8 px-6 text-center text-gray-500">
                                                Belum ada data peminjaman aktif.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($activeLoans as $loan) : ?>
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-gray-900 text-sm"><?= esc($loan['nama_item']); ?></span>
                                                        <span class="text-xs text-gray-500 uppercase"><?= esc($loan['tipe']); ?></span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-gray-600 whitespace-nowrap text-sm font-mono">
                                                    <?= esc($loan['kode']); ?>
                                                </td>
                                                <td class="py-4 px-6 text-gray-700">
                                                    <div class="flex flex-col">
                                                        <span class="text-sm font-medium truncate max-w-xs" title="<?= esc($loan['kegiatan']); ?>">
                                                            <?= esc($loan['kegiatan']); ?>
                                                        </span>
                                                        <span class="text-xs text-gray-400 mt-1">
                                                            Mulai: <?= date('d M Y', strtotime($loan['tgl_pinjam'])) ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <?php
                                                    $statusColor = 'bg-gray-100 text-gray-800';
                                                    switch ($loan['status']) {
                                                        case 'Diajukan': $statusColor = 'bg-yellow-100 text-yellow-800'; break;
                                                        case 'Disetujui': $statusColor = 'bg-blue-100 text-blue-800'; break;
                                                        case 'Dipinjam': $statusColor = 'bg-indigo-100 text-indigo-800'; break;
                                                        case 'Ditolak': $statusColor = 'bg-red-100 text-red-800'; break;
                                                    }
                                                    ?>
                                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $statusColor ?>">
                                                        <?= esc($loan['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex flex-col space-y-2">
                                                        <?php if ($loan['status'] == 'Diajukan'): ?>
                                                            <form action="<?= site_url('peminjam/peminjaman/delete-item/' . $loan['tipe'] . '/' . $loan['id_detail']) ?>"
                                                                method="post"
                                                                onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                                                <?= csrf_field() ?>
                                                                <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-white border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-xs font-medium transition-colors">
                                                                    Batal
                                                                </button>
                                                            </form>
                                                        <?php elseif ($loan['status'] == 'Disetujui' || $loan['status'] == 'Dipinjam'): ?>
                                                            
                                                            <?php if (!empty($loan['catatan_penolakan']) && empty($loan['foto_sebelum'])): ?>
                                                                <button type="button"
                                                                    data-reason="<?= esc($loan['catatan_penolakan']) ?>"
                                                                    onclick="openRejectionModal(this)"
                                                                    class="text-xs text-red-600 hover:text-red-800 underline font-medium flex items-center gap-1 mb-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                                    Info Penolakan Foto
                                                                </button>
                                                            <?php endif; ?>

                                                            <?php if (empty($loan['foto_sebelum'])): ?>
                                                                <button type="button"
                                                                    onclick="openUploadModal('sebelum', '<?= $loan['tipe'] ?>', '<?= $loan['id_detail'] ?>', '<?= esc($loan['nama_item']) ?>')"
                                                                    class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-yellow-500 text-white hover:bg-yellow-600 rounded-lg text-xs font-medium transition-colors shadow-sm">
                                                                    Upload Bukti Ambil
                                                                </button>
                                                            <?php else: ?>
                                                                <?php if (empty($loan['foto_sesudah'])): ?>
                                                                    <button type="button"
                                                                        onclick="openUploadModal('sesudah', '<?= $loan['tipe'] ?>', '<?= $loan['id_detail'] ?>', '<?= esc($loan['nama_item']) ?>')"
                                                                        class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-green-600 text-white hover:bg-green-700 rounded-lg text-xs font-medium transition-colors shadow-sm">
                                                                        Kembalikan
                                                                    </button>
                                                                <?php else: ?>
                                                                    <span class="text-gray-500 text-xs italic bg-gray-100 px-2 py-1 rounded text-center">Menunggu Verifikasi</span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>

                                                        <?php elseif ($loan['status'] == 'Ditolak'): ?>
                                                            <?php foreach ($peminjaman as $p): ?>
                                                                <button type="button"
                                                                    onclick="openDetailPenolakanModal(this)"
                                                                    data-alasan="<?= esc($p['keterangan']) ?>"
                                                                    class="w-full inline-flex justify-center items-center px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300 rounded-lg text-xs font-medium transition-colors">
                                                                    Lihat Alasan
                                                                </button>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="p-4 border-t border-gray-200">
                             </div>
                    </div>
                </div>

                <div class="hidden" id="history" role="tabpanel" aria-labelledby="history-tab">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border border-gray-200">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Selesai</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (empty($historyLoans)) : ?>
                                        <tr>
                                            <td colspan="5" class="py-8 px-6 text-center text-gray-500">Belum ada riwayat peminjaman.</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($historyLoans as $loan) : ?>
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-4 px-6">
                                                    <div class="flex flex-col">
                                                        <span class="font-medium text-gray-900 text-sm"><?= esc($loan['nama_item']); ?></span>
                                                        <span class="text-xs text-gray-500"><?= esc($loan['tipe']); ?> (<?= esc($loan['kode']); ?>)</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-700 truncate max-w-xs" title="<?= esc($loan['kegiatan']); ?>">
                                                    <?= esc($loan['kegiatan']); ?>
                                                </td>
                                                <td class="py-4 px-6 text-sm text-gray-500 whitespace-nowrap">
                                                    <?= date('d M Y', strtotime($loan['tgl_selesai'])) ?>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <?php
                                                    $color = 'bg-gray-100 text-gray-800';
                                                    if ($loan['status'] == 'Selesai') $color = 'bg-green-100 text-green-800';
                                                    if ($loan['status'] == 'Ditolak') $color = 'bg-red-100 text-red-800';
                                                    if ($loan['status'] == 'Dibatalkan') $color = 'bg-gray-200 text-gray-600';
                                                    ?>
                                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $color ?>">
                                                        <?= esc($loan['status']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <?php if ($loan['status'] == 'Selesai'): ?>
                                                        <a href="<?= site_url('peminjam/histori-peminjaman/detail/' . esc($loan['id_peminjaman'])) ?>"
                                                            class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-medium transition-colors">
                                                            Lihat Detail
                                                        </a>
                                                    <?php else: ?>
                                                        <button type="button"
                                                            onclick="openDetailPenolakanModal(this)"
                                                            data-alasan="<?= esc($loan['keterangan']) ?>"
                                                            class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 rounded-lg text-xs font-medium transition-colors">
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
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeUploadModal()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                        <form id="formUploadBukti" action="" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2" id="modalTitle">Upload Bukti</h3>
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-500" id="modalDescription"></p>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Bukti (Wajib)</label>
                                        <input type="file" name="foto_bukti" required accept="image/*" class="block w-full text-sm text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-sm file:font-semibold
                                            file:bg-blue-50 file:text-blue-700
                                            hover:file:bg-blue-100 cursor-pointer border border-gray-300 rounded-lg">
                                    </div>

                                    <div id="kondisiInputContainer" class="hidden">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Saat Ini</label>
                                        <select name="kondisi_akhir" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                <button type="submit" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:w-auto sm:text-sm">
                                    Simpan
                                </button>
                                <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="detailPenolakanModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeDetailPenolakanModal()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                            <div class="sm:flex sm:items-start">
                                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Alasan Penolakan</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded-md border border-gray-200" id="alasanPenolakanText">
                                            </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="closeDetailPenolakanModal()" class="w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:ml-3 sm:w-auto sm:text-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="rejectionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeRejectionModal()"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                    <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border-t-4 border-red-500">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="ml-3 w-full">
                                    <h3 class="text-lg font-medium text-gray-900">Foto Bukti Ditolak</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-2">Admin menolak foto bukti dengan alasan:</p>
                                        <div class="bg-red-50 text-red-700 p-3 rounded-md text-sm font-medium border border-red-100" id="rejectionReasonText"></div>
                                        <p class="text-xs text-gray-400 mt-3">Silakan upload ulang foto yang lebih jelas/sesuai.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" onclick="closeRejectionModal()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                                Mengerti
                            </button>
                        </div>
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

        if(activeTab && historyTab){
            activeTab.addEventListener('click', () => switchTab(true));
            historyTab.addEventListener('click', () => switchTab(false));
        }
    });

    // --- LOGIKA MODAL ---

    // 1. Modal Upload (Sebelum/Sesudah)
    function openUploadModal(jenis, tipeItem, idDetail, namaItem) {
        const form = document.getElementById('formUploadBukti');
        const title = document.getElementById('modalTitle');
        const desc = document.getElementById('modalDescription');
        const kondisiDiv = document.getElementById('kondisiInputContainer');
        const kondisiInput = kondisiDiv.querySelector('select');

        document.getElementById('uploadModal').classList.remove('hidden');

        if (jenis === 'sebelum') {
            form.action = '<?= site_url("peminjam/peminjaman/upload-bukti-sebelum/") ?>' + tipeItem + '/' + idDetail;
            title.innerText = 'Bukti Pengambilan';
            desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengambilnya.';
            kondisiDiv.classList.add('hidden');
            kondisiInput.required = false;
        } else {
            form.action = '<?= site_url("peminjam/peminjaman/upload-bukti-sesudah/") ?>' + tipeItem + '/' + idDetail;
            title.innerText = 'Bukti Pengembalian';
            desc.innerText = 'Upload foto kondisi ' + namaItem + ' saat Anda mengembalikannya.';
            kondisiDiv.classList.remove('hidden');
            kondisiInput.required = true;
        }
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }

    // 2. Modal Detail Penolakan (Umum)
    function openDetailPenolakanModal(buttonElement) {
        const alasan = buttonElement.getAttribute('data-alasan');
        let displayAlasan = alasan;
        
        // Bersihkan format [DITOLAK: ...] jika ada
        const match = alasan.match(/\[DITOLAK:\s*(.*?)\]/);
        if (match && match[1]) {
            displayAlasan = match[1];
        }

        const modalTextElement = document.getElementById('alasanPenolakanText');
        modalTextElement.textContent = displayAlasan.trim() ? displayAlasan : 'Tidak ada alasan spesifik.';

        document.getElementById('detailPenolakanModal').classList.remove('hidden');
    }

    function closeDetailPenolakanModal() {
        document.getElementById('detailPenolakanModal').classList.add('hidden');
    }

    // 3. Modal Penolakan Foto
    function openRejectionModal(button) {
        const reason = button.getAttribute('data-reason');
        document.getElementById('rejectionReasonText').innerText = reason;
        document.getElementById('rejectionModal').classList.remove('hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejectionModal').classList.add('hidden');
    }

    // Tutup modal dengan tombol Escape
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeUploadModal();
            closeDetailPenolakanModal();
            closeRejectionModal();
        }
    });
</script>

<?= $this->endSection(); ?>