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
                    <p class="text-gray-500 mt-1">Selamat datang, <?= esc($user->username) ?>. Berikut ringkasan operasional hari ini.</p>
                </div>
                <div class="mt-4 md:mt-0 flex items-center bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                    <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h6m-3-3v6m6-6h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V9a2 2 0 012-2h2"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-600"><?= date('d F Y') ?></span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl p-6 border border-orange-100 shadow-sm relative overflow-hidden group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-orange-500 uppercase tracking-wider">Perlu Verifikasi</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['menunggu_verifikasi'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-orange-50 rounded-lg text-orange-600 group-hover:bg-orange-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="<?= site_url('tu/peminjaman') ?>" class="text-xs text-orange-600 mt-4 inline-block hover:underline">Lihat pengajuan &rarr;</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-blue-100 shadow-sm group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Sedang Dipinjam</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['sedang_dipinjam'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="<?= site_url('tu/-verifikasi/pengembalian') ?>" class="text-xs text-blue-600 mt-4 inline-block hover:underline">Cek pengembalian &rarr;</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-red-100 shadow-sm group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Laporan Aset Rusak</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['laporan_rusak'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg text-red-600 group-hover:bg-red-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="<?= site_url('tu/laporan-kerusakan') ?>" class="text-xs text-red-600 mt-4 inline-block hover:underline">Tindak lanjuti &rarr;</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-green-100 shadow-sm group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-green-600 uppercase tracking-wider">Total Aset</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['total_aset'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg text-green-600 group-hover:bg-green-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-xs text-green-600 mt-4">Sarana & Prasarana</div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg">Laporan Bulanan</h3>
                            <p class="text-sm text-gray-500">Unduh rekapitulasi peminjaman dan kondisi aset.</p>
                        </div>
                    </div>
                    
                    <form id="formLaporan" method="get" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">
                        <div class="relative w-full sm:w-auto">
                            <input type="month" name="bulan" id="bulanInput" value="<?= date('Y-m') ?>" 
                                class="w-full sm:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm shadow-sm">
                        </div>
                        
                        <button type="button" onclick="downloadLaporan('pdf')" 
                            class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition shadow-sm text-sm font-medium group">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export PDF
                        </button>

                        <button type="button" onclick="downloadLaporan('excel')" 
                            class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition shadow-sm text-sm font-medium group">
                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Excel
                        </button>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <div>
                                <h3 class="font-bold text-gray-800">Permintaan Perlu Verifikasi</h3>
                                <p class="text-xs text-gray-500">Daftar pengajuan terbaru yang menunggu persetujuan Anda.</p>
                            </div>
                            <a href="<?= site_url('tu/peminjaman') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center group">
                                Lihat Semua <span class="ml-1 group-hover:translate-x-1 transition-transform">&rarr;</span>
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead class="bg-white text-gray-500 border-b border-gray-100">
                                    <tr>
                                        <th class="px-6 py-3 font-medium uppercase text-xs">Peminjam</th>
                                        <th class="px-6 py-3 font-medium uppercase text-xs">Barang</th>
                                        <th class="px-6 py-3 font-medium uppercase text-xs">Tanggal</th>
                                        <th class="px-6 py-3 font-medium uppercase text-xs text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <?php if (!empty($pendingApprovals)): ?>
                                        <?php foreach ($pendingApprovals as $item) : ?>
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-4">
                                                    <p class="font-semibold text-gray-800"><?= esc($item['peminjam']) ?></p>
                                                    <p class="text-xs text-gray-500 truncate w-32" title="<?= esc($item['kegiatan']) ?>"><?= esc($item['kegiatan']) ?></p>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600">
                                                    <span class="truncate block w-32" title="<?= esc($item['barang']) ?>"><?= esc($item['barang']) ?></span>
                                                </td>
                                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap"><?= esc($item['tgl_ajukan']) ?></td>
                                                <td class="px-6 py-4 text-center">
                                                    <a href="<?= site_url('tu/peminjaman/detail/' . $item['id']) ?>" class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition text-xs font-bold border border-blue-200">
                                                        Verifikasi
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                                Tidak ada permintaan pending saat ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Akses Cepat</h3>
                        <div class="space-y-3">
                            
                            <a href="<?= site_url('tu/peminjaman') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-600 transition group border border-transparent hover:border-blue-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Verifikasi Peminjaman</p>
                                    <p class="text-xs text-gray-500">Cek pengajuan masuk</p>
                                </div>
                            </a>

                            <a href="<?= site_url('tu/verifikasi-pengembalian') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 transition group border border-transparent hover:border-indigo-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-indigo-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Cek Pengembalian</p>
                                    <p class="text-xs text-gray-500">Verifikasi barang kembali</p>
                                </div>
                            </a>

                            <a href="<?= site_url('tu/laporan-kerusakan') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-600 transition group border border-transparent hover:border-red-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Laporan Kerusakan</p>
                                    <p class="text-xs text-gray-500">Tindak lanjuti laporan</p>
                                </div>
                            </a>

                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<script>
function downloadLaporan(type) {
    const bulan = document.getElementById('bulanInput').value;
    
    // Pastikan user sudah memilih bulan (meskipun input date defaultnya sudah ada)
    if (!bulan) {
        alert("Silakan pilih bulan laporan terlebih dahulu.");
        return;
    }

    let url = '';
    // Tentukan URL berdasarkan tipe tombol yang diklik
    if (type === 'pdf') {
        url = '<?= site_url('tu/laporan/pdf') ?>';
    } else {
        url = '<?= site_url('tu/laporan/excel') ?>';
    }
    
    // Buka link di tab baru dengan parameter bulan
    // Contoh hasil: http://localhost:8080/tu/laporan/excel?bulan=2025-11
    window.open(url + '?bulan=' + bulan, '_blank');
}
</script>

<?= $this->endSection(); ?>