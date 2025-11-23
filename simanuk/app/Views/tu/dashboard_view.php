<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Tata Usaha</h1>
                    <p class="text-gray-500 mt-1">Pantau aktivitas peminjaman dan inventaris fakultas di sini.</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                    <span class="text-sm font-medium text-gray-600"><?= date('d F Y') ?></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 border border-orange-100 shadow-sm relative overflow-hidden">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-orange-500 uppercase tracking-wider">Perlu Verifikasi</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['menunggu_verifikasi'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-orange-50 rounded-lg text-orange-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Pengajuan baru hari ini</p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Sedang Dipinjam</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['sedang_dipinjam'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Total barang keluar</p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-red-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Laporan Kerusakan</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['laporan_rusak'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Perlu tindak lanjut segera</p>
                </div>

                <div class="bg-white rounded-xl p-6 border border-green-100 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-green-500 uppercase tracking-wider">Total Aset</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['total_aset'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg text-green-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-4">Sarana & Prasarana terdata</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="font-bold text-gray-800">Permintaan Peminjaman Terbaru</h3>
                                <p class="text-xs text-gray-500">Daftar pengajuan yang menunggu tindakan Anda.</p>
                            </div>
                            <a href="<?= site_url('tu/verifikasi-peminjaman') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Lihat Semua &rarr;</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 font-medium">Peminjam</th>
                                        <th class="px-6 py-3 font-medium">Barang</th>
                                        <th class="px-6 py-3 font-medium">Tanggal</th>
                                        <th class="px-6 py-3 font-medium">Status</th>
                                        <th class="px-6 py-3 font-medium text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php if (!empty($pendingApprovals)): ?>
                                        <?php foreach ($pendingApprovals as $item) : ?>
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4">
                                                <p class="font-semibold text-gray-800"><?= esc($item['peminjam']) ?></p>
                                                <p class="text-xs text-gray-500"><?= esc($item['kegiatan']) ?></p>
                                            </td>
                                            <td class="px-6 py-4 text-gray-600"><?= esc($item['barang']) ?></td>
                                            <td class="px-6 py-4 text-gray-600"><?= esc($item['tgl_ajukan']) ?></td>
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    <?= esc($item['status']) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <div class="flex justify-center space-x-2">
                                                    <a href="<?= site_url('tu/verifikasi-peminjaman/detail/'.$item['id']) ?>" class="p-1.5 bg-blue-50 text-blue-600 rounded hover:bg-blue-100" title="Detail">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada permintaan pending.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="font-bold text-gray-800 mb-4">Akses Cepat</h3>
                        <div class="space-y-3">
                            <a href="<?= site_url('tu/verifikasi-peminjaman') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-600 transition group">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-500 group-hover:border-blue-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Verifikasi Peminjaman</p>
                                    <p class="text-xs text-gray-500">Cek pengajuan masuk</p>
                                </div>
                            </a>

                            <a href="<?= site_url('tu/verifikasi-pengembalian') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-green-50 hover:text-green-600 transition group">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-green-500 group-hover:border-green-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Cek Pengembalian</p>
                                    <p class="text-xs text-gray-500">Validasi barang kembali</p>
                                </div>
                            </a>

                            <a href="<?= site_url('tu/kelola-laporan-kerusakan') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-600 transition group">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-red-500 group-hover:border-red-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Laporan Kerusakan</p>
                                    <p class="text-xs text-gray-500">Tindak lanjuti laporan</p>
                                </div>
                            </a>

                            <a href="<?= site_url('tu/generate-laporan') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-purple-50 hover:text-purple-600 transition group">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-purple-500 group-hover:border-purple-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Generate Laporan</p>
                                    <p class="text-xs text-gray-500">Unduh rekap data</p>
                                </div>
                            </a>

                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>