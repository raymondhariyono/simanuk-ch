<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="flex justify-between items-end mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Eksekutif</h1>
                    <p class="text-gray-500 mt-1 text-sm">Ringkasan performa dan aktivitas Sarana Prasarana.</p>
                </div>
                <div class="text-sm text-gray-500">
                    <?= date('d F Y') ?>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 rounded-lg bg-blue-50 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Total Aset Terdaftar</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['total_aset']) ?></h3>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 rounded-lg bg-yellow-50 text-yellow-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h6m-3-3v6m6-6h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h2"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Peminjaman Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['peminjaman_aktif']) ?></h3>
                        <p class="text-xs text-yellow-600 mt-1">Sedang berjalan</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 flex items-center">
                    <div class="p-3 rounded-lg bg-red-50 text-red-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Laporan Kerusakan</p>
                        <h3 class="text-2xl font-bold text-gray-800"><?= number_format($stats['laporan_rusak']) ?></h3>
                        <p class="text-xs text-red-500 mt-1">Perlu Perhatian</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-semibold text-gray-800">Aktivitas Terkini</h3>
                    <a href="<?= site_url('pimpinan/lihat-laporan') ?>" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Detail Laporan &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white text-gray-500 border-b border-gray-100 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-3 font-medium">Peminjam</th>
                                <th class="px-6 py-3 font-medium">Kegiatan</th>
                                <th class="px-6 py-3 font-medium">Tanggal</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <?php if(empty($recentActivity)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada aktivitas terbaru.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($recentActivity as $row) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <span class="font-semibold text-gray-900"><?= esc($row['nama_lengkap']) ?></span><br>
                                            <span class="text-xs text-gray-500"><?= esc($row['organisasi']) ?></span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600"><?= esc($row['kegiatan']) ?></td>
                                        <td class="px-6 py-4 text-gray-500">
                                            <?= date('d M Y', strtotime($row['tgl_pinjam_dimulai'])) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php 
                                            $statusClass = match($row['status_peminjaman_global']) {
                                                'Diajukan' => 'bg-yellow-100 text-yellow-800',
                                                'Disetujui', 'Dipinjam' => 'bg-blue-100 text-blue-800',
                                                'Selesai' => 'bg-green-100 text-green-800',
                                                'Ditolak', 'Dibatalkan' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            ?>
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusClass ?>">
                                                <?= esc($row['status_peminjaman_global']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>