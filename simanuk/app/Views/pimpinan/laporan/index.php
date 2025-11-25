<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="mb-6">
                <div class="text-sm text-gray-500 mb-1">Dashboard / Laporan</div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Sistem</h1>
                <p class="text-gray-500 mt-1 text-sm">Lihat, filter, dan pantau laporan inventaris serta peminjaman.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <h3 class="text-sm font-semibold text-gray-800 mb-4">Filter Laporan</h3>
                <form action="" method="get" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-4">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Rentang Tanggal</label>
                        <input type="date" class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <div class="md:col-span-4">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5">Jenis Laporan</label>
                        <select name="jenis" class="w-full border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="Semua Jenis">Semua Jenis</option>
                            <option value="Inventaris">Inventaris</option>
                            <option value="Peminjaman">Peminjaman</option>
                            <option value="Kerusakan">Kerusakan</option>
                        </select>
                    </div>

                    <div class="md:col-span-4 flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition">Terapkan</button>
                        <a href="<?= site_url('pimpinan/lihat-laporan') ?>" class="bg-white border border-gray-300 text-gray-600 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Reset</a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap text-left">
                        <thead class="bg-white border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul Laporan</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Jenis Laporan</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Dibuat</th>
                                <th class="px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($laporan)): ?>
                                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">Tidak ada laporan ditemukan.</td></tr>
                            <?php else: ?>
                                <?php foreach($laporan as $row): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-800"><?= esc($row['judul']) ?></td>
                                    <td class="px-6 py-4">
                                        <?php 
                                            $badge = 'bg-gray-100 text-gray-600';
                                            if($row['jenis'] == 'Inventaris') $badge = 'bg-blue-100 text-blue-700';
                                            if($row['jenis'] == 'Peminjaman') $badge = 'bg-green-100 text-green-700';
                                            if($row['jenis'] == 'Kerusakan') $badge = 'bg-red-100 text-red-700';
                                        ?>
                                        <span class="px-2.5 py-1 rounded-md text-xs font-medium <?= $badge ?>"><?= esc($row['jenis']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500"><?= esc($row['tanggal']) ?></td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="<?= site_url('pimpinan/lihat-laporan/detail') ?>?tipe=<?= $row['tipe_data'] ?>&judul=<?= urlencode($row['judul']) ?>" 
                                           class="inline-block px-3 py-1.5 bg-white border border-gray-300 text-gray-600 text-xs font-medium rounded hover:bg-gray-50 transition">
                                           Lihat Detail
                                        </a>
                                        <!-- <button type="button" onclick="alert('Fitur unduh PDF belum tersedia.')" 
                                           class="inline-block px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition shadow-sm">
                                           <span class="mr-1">↓</span> Unduh
                                        </button> -->
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                    <span class="text-xs text-gray-500">Menampilkan <?= count($laporan) ?> dari 100</span>
                    <div class="flex space-x-1">
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-gray-300 text-gray-500 text-xs hover:bg-gray-100"><</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-blue-600 text-white text-xs shadow-sm">1</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-gray-300 text-gray-500 text-xs hover:bg-gray-100">2</button>
                        <button class="w-8 h-8 flex items-center justify-center rounded bg-white border border-gray-300 text-gray-500 text-xs hover:bg-gray-100">></button>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>