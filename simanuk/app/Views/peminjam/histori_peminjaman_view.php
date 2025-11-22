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
                            <?php if (empty($loans)) : ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                        Belum ada data peminjaman.
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($loans as $loan) : ?>
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
                                            $badgeClass = 'bg-gray-100 text-gray-800';
                                            switch ($loan['status']) {
                                                case 'Diajukan':
                                                    $badgeClass = 'font-semibold text-yellow-800 bg-yellow-100 rounded-full';
                                                    break;
                                                case 'Disetujui':
                                                    $badgeClass = 'font-semibold text-blue-800 bg-blue-100 rounded-full';
                                                    break;
                                                case 'Dipinjam':
                                                    $badgeClass = 'font-semibold text-indigo-800 bg-indigo-100 rounded-full';
                                                    break;
                                                case 'Selesai':
                                                    $badgeClass = 'font-semibold text-green-800 bg-green-100 rounded-full';
                                                    break;
                                                case 'Dibatalkan':
                                                    $badgeClass = 'font-semibold text-red-800 bg-red-100 rounded-full';
                                                    break;
                                            }
                                            ?>
                                            <span class="text-sm font-medium px-3 py-1 rounded-full <?= $badgeClass ?>">
                                                <?= esc($loan['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <?php if ($loan['aksi'] == 'Batal'): ?>
                                                <form action="<?= site_url('peminjam/peminjaman/delete-item/' . $loan['tipe'] . '/' . $loan['id_detail']) ?>"
                                                    method="post"
                                                    onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-200 border border-red-300 rounded-lg text-xs font-medium transition-colors">
                                                        Batal
                                                    </button>
                                                </form>
                                            <?php elseif ($loan['aksi'] == 'Upload Foto Sebelum'): ?>
                                                <button type="button"
                                                    onclick="openUploadModal('<?= $loan['tipe'] ?>', '<?= $loan['id_detail'] ?>', '<?= esc($loan['nama_item']) ?>')"
                                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-600 hover:bg-yellow-300 border border-yellow-600 rounded-lg text-xs font-medium transition-colors">
                                                    Upload Foto <br>SEBELUM
                                                </button>

                                            <?php elseif ($loan['aksi'] == 'Kembalikan'): ?>
                                                <a href="<?= site_url('peminjam/histori-peminjaman/detail/' . esc($loan['kode'])) ?>"
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-600 hover:bg-green-300 border border-green-600 rounded-lg text-xs font-medium transition-colors">
                                                    Kembalikan
                                                </a>
                                            <?php elseif ($loan['aksi'] == 'Kembalikan'): ?>
                                                <form action="<?= site_url('peminjam/peminjaman/delete-item/' . $loan['tipe'] . '/' . $loan['id_detail']) ?>"
                                                    method="post"
                                                    onsubmit="return confirm('Batalkan peminjaman untuk item ini saja?');">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-200 border border-red-300 rounded-lg text-xs font-medium transition-colors">
                                                        Batal
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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

                <div id="uploadModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeUploadModal()"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                            <form id="formUploadBukti" action="" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Upload Bukti Pengambilan</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500 mb-4">
                                            Silakan upload foto kondisi barang <b id="itemNameModal"></b> saat Anda mengambilnya. Ini sebagai bukti kondisi awal.
                                        </p>
                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700">Foto Bukti (Wajib)</label>
                                            <input type="file" name="foto_bukti" required accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-yellow-400 text-base font-medium text-white hover:bg-yellow-600 sm:ml-3 sm:w-auto sm:text-sm">
                                        Upload & Ambil Barang
                                    </button>
                                    <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Batal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<script>
    function openUploadModal(tipe, idDetail, namaItem) {
        // Set Action Form secara dinamis
        const form = document.getElementById('formUploadBukti');
        form.action = '<?= site_url("peminjam/peminjaman/upload-bukti/") ?>' + tipe + '/' + idDetail;

        // Set Nama Barang di Modal
        document.getElementById('itemNameModal').innerText = namaItem;

        // Tampilkan Modal
        document.getElementById('uploadModal').classList.remove('hidden');
    }

    function closeUploadModal() {
        document.getElementById('uploadModal').classList.add('hidden');
    }
</script>

<?= $this->endSection(); ?>