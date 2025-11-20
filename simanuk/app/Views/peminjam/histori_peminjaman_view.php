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
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    <span>Tambah Item Baru</span>
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
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="font-medium text-gray-900"><?= esc($loan['nama_item']); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($loan['kode']); ?>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($loan['kegiatan']); ?>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="text-sm font-medium px-3 py-1 rounded-full
                                                <?php
                                                switch ($loan['status']) {
                                                    case 'Menunggu Verifikasi':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Menunggu Persetujuan':
                                                        echo 'bg-blue-100 text-blue-800';
                                                        break;
                                                    case 'Disetujui':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'Berlangsung':
                                                        echo 'bg-indigo-100 text-indigo-800';
                                                        break;
                                                    case 'Selesai':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'Ditolak':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= esc($loan['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <?php if ($loan['aksi'] == 'Batal'): ?>
                                                <a href="#" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded-full">Batal</a>
                                            <?php elseif ($loan['aksi'] == 'Upload Foto SEBELUM *'): ?>
                                                <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded-full">Upload Foto SEBELUM *</a>
                                            <?php elseif ($loan['aksi'] == 'Kembalikan'): ?>
                                                <a href="#" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded-full">Kembalikan</a>
                                                <a href="#" class="bg-gray-600 hover:bg-gray-700 text-white py-1 px-3 rounded-full ml-2">Perpanjang</a>
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
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>