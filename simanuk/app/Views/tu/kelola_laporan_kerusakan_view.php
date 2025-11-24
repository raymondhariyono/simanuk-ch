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
                    <h1 class="text-3xl font-bold text-gray-900">Daftar Laporan Kerusakan Aset (TU)</h1>
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
                    <span>Tambah Laporan Baru</span>
                </a>
            </div>

            <div class="mb-8 flex flex-wrap gap-4 items-center">
                <div class="relative flex-grow" style="min-width: 300px;">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Cari berdasarkan nama item"
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                </div>
                <div class="grid items-center">
                    <label class="text-center text-gray-600 font-medium">Semua Status</label>
                    <select class="border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:border-blue-500">
                        <option>Selesai</option>
                        <option>Sedang Ditinjau</option>
                        <option>Ditolak</option>
                        <option>Sedang Diperbaiki</option>
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
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($sarana)) : ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                        Belum ada data inventaris.
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($sarana as $barang) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="font-medium text-gray-900"><?= esc($barang['nama_sarana']); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($barang['kode_sarana']); ?>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($barang['nama_kategori']); ?>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="text-sm font-medium px-3 py-1 rounded-full
                                                <?php
                                                switch ($barang['status_ketersediaan']) {
                                                    case 'Tersedia':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Dipinjam':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'Perawatan':
                                                        echo 'bg-indigo-100 text-indigo-800';
                                                        break;
                                                    case 'Selesai':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'Tidak Tersedia':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= esc($barang['status_ketersediaan']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <a href="#" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100" title="Lihat Detail">
                                                <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                </svg>
                                            </a>
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
                        <button class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Sebelumnya</button>
                        <button class="py-2 px-3 rounded-lg bg-blue-100 text-blue-600 font-medium">1</button>
                        <button class="py-2 px-3 rounded-lg text-gray-600 hover:bg-gray-100">Berikutnya</button>
                    </nav>
                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>