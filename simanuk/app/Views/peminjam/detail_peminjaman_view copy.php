<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?><?= esc($title); ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            <div class="max-w-7xl mx-auto">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Detail Peminjaman</h1>
                    </div>

                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                    <?= $peminjaman['status_peminjaman_global'] == 'Dipinjam' ? 'bg-cyan-100 text-cyan-800' : 'bg-gray-100 text-gray-700' ?>">
                        <span class="w-2 h-2 rounded-full mr-2 
                        <?= $peminjaman['status_peminjaman_global'] == 'Dipinjam' ? 'bg-cyan-500' : 'bg-gray-400' ?>"></span>
                        <?= esc($peminjaman['status_peminjaman_global']) ?>
                    </span>
                </div>
                <?php if (isset($breadcrumbs)) : ?>
                    <?= render_breadcrumb($breadcrumbs); ?>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <!-- KOLOM KIRI -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- Informasi Peminjaman -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6">Informasi Peminjaman</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Peminjam</p>
                                    <p class="font-medium text-gray-900"><?= esc($peminjaman['nama_lengkap']) ?></p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Pinjam Dimulai</p>
                                    <p class="font-medium text-gray-900"><?= esc($peminjaman['tgl_pinjam_dimulai']) ?></p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Pinjam Berakhir</p>
                                    <p class="font-medium text-gray-900"><?= esc($peminjaman['tgl_pinjam_dimulai']) ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Barang Sarana -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800">Daftar Sarana</h3>

                            <?php if (!empty($itemsSarana)) : ?>
                                <?php foreach ($itemsSarana as $item) : ?>
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 
                                <?= empty($item['foto_sesudah']) ? 'border-cyan-500' : 'border-green-500' ?>">

                                        <div class="flex flex-col md:flex-row justify-between mb-4">
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900"><?= esc($item['nama_sarana']) ?></h4>
                                                <p class="text-sm text-gray-500">Kode. <?= esc($item['kode_sarana']) ?></p>
                                            </div>

                                            <div class="mt-2 md:mt-0">
                                                <span class="text-sm font-medium bg-gray-100 px-3 py-1 rounded">
                                                    Jumlah. <?= esc($item['jumlah']) ?>
                                                </span>
                                            </div>
                                        </div>

                                        <hr class="my-4 border-gray-100">

                                        <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>

                                            <div class="bg-cyan-50 p-4 rounded-md">
                                                <h5 class="font-semibold text-cyan-800 text-sm mb-2">
                                                    Form Pengembalian Barang
                                                </h5>

                                                <form action="<?= site_url('peminjam/peminjaman/kembalikan-item/sarana/' . $item['id_detail_sarana']) ?>" method="post" enctype="multipart/form-data">
                                                    <?= csrf_field() ?>

                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Foto Bukti</label>
                                                            <input type="file" name="foto_sesudah" required accept="image/*"
                                                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-100 file:text-cyan-700 hover:file:bg-cyan-200">
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-medium text-gray-700 mb-1">Kondisi Barang</label>
                                                            <select name="kondisi_akhir"
                                                                class="block w-full text-sm border-gray-300 rounded-lg focus:ring-cyan-500 focus:border-cyan-500">
                                                                <option value="Baik">Baik</option>
                                                                <option value="Rusak Ringan">Rusak Ringan</option>
                                                                <option value="Rusak Berat">Rusak Berat</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <button type="submit"
                                                        class="bg-cyan-600 hover:bg-cyan-700 text-white font-semibold py-2 px-4 rounded text-sm w-full md:w-auto shadow">
                                                        Kirim Bukti dan Kembalikan
                                                    </button>
                                                </form>
                                            </div>

                                        <?php elseif (!empty($item['foto_sesudah'])) : ?>
                                            <div class="flex items-center text-green-700 bg-green-50 p-3 rounded border border-green-200">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <div>
                                                    <p class="font-semibold text-sm">Barang telah diserahkan</p>
                                                    <p class="text-xs">Menunggu verifikasi admin.</p>
                                                </div>
                                            </div>

                                        <?php else: ?>
                                            <p class="text-sm text-gray-400 italic">
                                                Menunggu status Dipinjam untuk pengembalian.
                                            </p>
                                        <?php endif; ?>

                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <!-- Daftar Prasarana -->
                            <?php if (!empty($itemsPrasarana)) : ?>
                                <h3 class="text-lg font-semibold text-gray-800 mt-6">Daftar Sarana</h3>

                                <?php foreach ($itemsPrasarana as $item) : ?>
                                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 border-l-4 border-purple-500">
                                        <h4 class="text-lg font-semibold text-gray-900">
                                            <?= esc($item['nama_prasarana']) ?>
                                        </h4>

                                        <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>
                                            <form action="<?= site_url('peminjam/peminjaman/kembalikan-item/prasarana/' . $item['id_detail_prasarana']) ?>" method="post" enctype="multipart/form-data">
                                                ... form kamu di sini ...
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="lg:col-span-1 space-y-6">

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tindakan</h2>
                            <button class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm">
                                Konfirmasi Pengembalian
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="container px-4 py-6 mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Peminjaman</h1>
    </div>

    <?php if (isset($breadcrumbs)) : ?>
        <?= render_breadcrumb($breadcrumbs); ?>
    <?php endif; ?>

    <div class="bg-white shadow-sm rounded-lg p-6 mb-6 border border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase">Kegiatan</span>
                <p class="text-lg font-semibold text-gray-800"><?= esc($peminjaman['kegiatan']) ?></p>
            </div>
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase">Status</span>
                <div class="mt-1">
                    <span class="px-3 py-1 text-sm font-bold rounded-full 
                        <?= $peminjaman['status_peminjaman_global'] == 'Dipinjam' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' ?>">
                        <?= esc($peminjaman['status_peminjaman_global']) ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <h3 class="text-lg font-bold text-gray-700 mb-4">Daftar Barang</h3>
    <div class="grid grid-cols-1 gap-6">
        <?php if (!empty($itemsSarana)): ?>
            <?php foreach ($itemsSarana as $item) : ?>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 
                <?= empty($item['foto_sesudah']) ? 'border-indigo-500' : 'border-green-500' ?>">

                    <div class="flex flex-col md:flex-row justify-between mb-4">
                        <div>
                            <h4 class="text-lg font-bold text-gray-900"><?= esc($item['nama_sarana']) ?></h4>
                            <p class="text-sm text-gray-500">Kode: <?= esc($item['kode_sarana']) ?></p>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <span class="text-sm font-semibold bg-gray-100 px-2 py-1 rounded">Jumlah: <?= esc($item['jumlah']) ?></span>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-100">

                    <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>

                        <div class="bg-indigo-50 p-4 rounded-md">
                            <h5 class="font-bold text-indigo-800 text-sm mb-2">Form Pengembalian Barang</h5>
                            <form action="<?= site_url('peminjam/peminjaman/kembalikan-item/sarana/' . $item['id_detail_sarana']) ?>" method="post" enctype="multipart/form-data">
                                <?= csrf_field() ?>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Foto Bukti (Kondisi Akhir)</label>
                                        <input type="file" name="foto_sesudah" required accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-indigo-100 file:text-indigo-700 hover:file:bg-indigo-200">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Kondisi Barang</label>
                                        <select name="kondisi_akhir" class="block w-full text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="Baik">Baik</option>
                                            <option value="Rusak Ringan">Rusak Ringan</option>
                                            <option value="Rusak Berat">Rusak Berat</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm w-full md:w-auto shadow">
                                    📤 Kirim Bukti & Kembalikan
                                </button>
                            </form>
                        </div>

                    <?php elseif (!empty($item['foto_sesudah'])) : ?>
                        <div class="flex items-center text-green-700 bg-green-50 p-3 rounded border border-green-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <p class="font-bold text-sm">Barang telah diserahkan</p>
                                <p class="text-xs">Menunggu verifikasi admin untuk penyelesaian akhir.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic">Menunggu status 'Dipinjam' untuk melakukan pengembalian.</p>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($itemsPrasarana)) : ?>
            <h4 class="font-bold text-gray-700 mt-6 mb-3">Daftar Ruangan</h4>
            <?php foreach ($itemsPrasarana as $item) : ?>
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                    <h4 class="text-lg font-bold"><?= esc($item['nama_prasarana']) ?></h4>

                    <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>
                        <form action="<?= site_url('peminjam/peminjaman/kembalikan-item/prasarana/' . $item['id_detail_prasarana']) ?>" ...>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>
<?= $this->endSection(); ?>