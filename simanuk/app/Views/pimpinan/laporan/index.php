<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="mb-6">
                <div class="text-sm text-gray-500 mb-1">Dashboard / Laporan</div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Bulanan</h1>
                <p class="text-gray-500 mt-1 text-sm">Pilih bulan untuk melihat detail atau mengunduh laporan lengkap.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-end gap-4 justify-between">
                    
                    <form action="" method="get" class="flex flex-col md:flex-row gap-4 md:items-end w-full md:w-auto">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">Pilih Periode Bulan</label>
                            <input type="month" name="bulan" value="<?= esc($filterBulan) ?>" 
                                class="w-full md:w-64 border-gray-300 rounded-lg text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 shadow-sm transition flex items-center justify-center gap-2 h-[38px]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Tampilkan
                        </button>
                    </form>

                    <div class="flex gap-2"> <a href="<?= site_url('pimpinan/lihat-laporan/cetak') ?>?bulan=<?= esc($filterBulan) ?>" target="_blank"
                        class="flex items-center justify-center px-5 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition shadow-sm h-[38px]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        PDF
                        </a>

                        <a href="<?= site_url('pimpinan/lihat-laporan/excel') ?>?bulan=<?= esc($filterBulan) ?>" target="_blank"
                        class="flex items-center justify-center px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition shadow-sm h-[38px]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Excel
                        </a>

                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-700 text-sm">Rincian Laporan Periode: <?= date('F Y', strtotime($filterBulan)) ?></h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-no-wrap text-left">
                        <thead class="bg-white text-gray-500 border-b border-gray-100 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Jenis Laporan</th>
                                <th class="px-6 py-4 font-semibold">Ringkasan Data</th>
                                <th class="px-6 py-4 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php foreach ($laporan as $row): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="p-2 rounded-lg 
                                                <?= $row['jenis'] == 'Inventaris' ? 'bg-blue-50 text-blue-600' : 
                                                   ($row['jenis'] == 'Peminjaman' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600') ?> mr-3">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <span class="text-sm font-bold text-gray-800"><?= esc($row['judul']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= esc($row['ringkasan']) ?>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="<?= site_url('pimpinan/lihat-laporan/detail') ?>?tipe=<?= $row['tipe_data'] ?>&bulan=<?= esc($filterBulan) ?>&judul=<?= urlencode($row['judul']) ?>"
                                            class="inline-block px-4 py-2 bg-white border border-gray-300 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-50 hover:text-blue-600 transition shadow-sm">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>