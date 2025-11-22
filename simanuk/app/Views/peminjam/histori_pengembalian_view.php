<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="mb-8 text-center">
                <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-wide">HISTORI PENGEMBALIAN SAYA</h1>
                <?php if (isset($breadcrumbs)) : ?>
                    <div class="mt-2">
                        <?= render_breadcrumb($breadcrumbs); ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">

                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
                    <div class="relative w-full md:w-1/2">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Cari berdasarkan nama atau kode">
                    </div>

                    <div class="flex gap-4 w-full md:w-auto">
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600 whitespace-nowrap">Kategori:</label>
                            <select class="bg-gray-100 border-none text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2">
                                <option>Semua</option>
                                <option>Sarana</option>
                                <option>Prasarana</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm text-gray-600 whitespace-nowrap">Lokasi:</label>
                            <select class="bg-gray-100 border-none text-gray-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2">
                                <option>Semua</option>
                                <option>Gedung A</option>
                                <option>Gedung B</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">NAMA ITEM</th>
                                <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">KODE</th>
                                <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">KEGIATAN</th>
                                <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider">STATUS</th>
                                <th class="py-4 px-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (!empty($returns)) : ?>
                                <?php foreach ($returns as $item) : ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="py-4 px-4 text-sm font-semibold text-gray-800">
                                            <?= esc($item['nama_item']); ?>
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            <?= esc($item['kode']); ?>
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            <?= esc($item['kegiatan']); ?>
                                        </td>
                                        <td class="py-4 px-4 text-sm text-gray-600">
                                            <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">
                                                <?= esc($item['status']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-sm text-center">
                                            <a href="<?= site_url('peminjam/histori-pengembalian/detail/' . esc($item['kode'])) ?>"
                                                class="inline-flex items-center px-3 py-1.5 bg-neutral-100 text-neutral-600 hover:bg-neutral-300 border border-neutral-600 rounded-lg text-xs font-medium transition-colors">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-500">Tidak ada data pengembalian.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-center mt-8 pt-4 border-t border-gray-100">
                    <div class="text-sm text-gray-500 mb-4 md:mb-0">
                        Menampilkan <span class="font-bold text-gray-800">1-5</span> dari <span class="font-bold text-gray-800">100</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-4 py-1.5 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">Sebelumnya</button>
                        <button class="w-8 h-8 flex items-center justify-center text-sm border rounded bg-white text-gray-600 hover:bg-gray-50">1</button>
                        <button class="w-8 h-8 flex items-center justify-center text-sm border rounded bg-blue-600 text-white border-blue-600">2</button>
                        <button class="w-8 h-8 flex items-center justify-center text-sm border rounded bg-white text-gray-600 hover:bg-gray-50">3</button>
                        <button class="px-4 py-1.5 text-sm text-gray-600 border border-gray-300 rounded hover:bg-gray-50">Berikutnya</button>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
<?= $this->endSection(); ?>