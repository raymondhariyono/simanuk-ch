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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Administrator</h1>
                    <p class="text-gray-500 mt-1">Selamat datang, Administrator. Berikut ringkasan sistem.</p>
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
                    <a href="<?= site_url('admin/peminjaman') ?>" class="text-xs text-orange-600 mt-4 inline-block hover:underline">Lihat pengajuan &rarr;</a>
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
                    <a href="<?= site_url('admin/pengembalian') ?>" class="text-xs text-blue-600 mt-4 inline-block hover:underline">Cek pengembalian &rarr;</a>
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
                    <a href="<?= site_url('admin/laporan-kerusakan') ?>" class="text-xs text-red-600 mt-4 inline-block hover:underline">Tindak lanjuti &rarr;</a>
                </div>

                <div class="bg-white rounded-xl p-6 border border-purple-100 shadow-sm group hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-xs font-semibold text-purple-500 uppercase tracking-wider">Pengguna Sistem</p>
                            <h3 class="text-3xl font-bold text-gray-900 mt-2"><?= esc($stats['total_user'] ?? 0) ?></h3>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg text-purple-600 group-hover:bg-purple-100 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <a href="<?= site_url('admin/manajemen-akun') ?>" class="text-xs text-purple-600 mt-4 inline-block hover:underline">Kelola akun &rarr;</a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                            <div>
                                <h3 class="font-bold text-gray-800">Permintaan Peminjaman Terbaru</h3>
                                <p class="text-xs text-gray-500">Daftar pengajuan yang menunggu verifikasi.</p>
                            </div>
                            <a href="<?= site_url('admin/peminjaman') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center">
                                Lihat Semua <span class="ml-1">&rarr;</span>
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
                                                    <a href="<?= site_url('admin/peminjaman/detail/' . $item['id']) ?>" class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 transition text-xs font-bold">
                                                        Verifikasi
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">
                                                Tidak ada permintaan 'pending' saat ini.
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
                            
                            <a href="<?= site_url('admin/inventaris/sarana/create') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-600 transition group border border-transparent hover:border-blue-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Tambah Sarana</p>
                                    <p class="text-xs text-gray-500">Input sarana baru</p>
                                </div>
                            </a>
                            
                            <a href="<?= site_url('admin/inventaris/prasarana/create') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-blue-50 hover:text-blue-600 transition group border border-transparent hover:border-blue-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-blue-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Tambah Prasarana</p>
                                    <p class="text-xs text-gray-500">Input prasarana baru</p>
                                </div>
                            </a>

                            <a href="<?= site_url('admin/manajemen-akun/new') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-green-50 hover:text-green-600 transition group border border-transparent hover:border-green-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-green-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Tambah Pengguna</p>
                                    <p class="text-xs text-gray-500">Daftarkan akun baru</p>
                                </div>
                            </a>

                            <a href="<?= site_url('admin/laporan-kerusakan') ?>" class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-red-50 hover:text-red-600 transition group border border-transparent hover:border-red-100">
                                <div class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 group-hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-semibold">Laporan Kerusakan</p>
                                    <p class="text-xs text-gray-500">Cek tindak lanjut</p>
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