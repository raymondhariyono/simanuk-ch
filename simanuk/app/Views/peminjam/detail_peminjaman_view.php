<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?><?= esc($title); ?><?= $this->endSection(); ?>

<?= $this->section('content'); ?>

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
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase">Jadwal Pinjam Dimulai</span>
                <div class="mt-1">
                    <p class="font-medium text-gray-900"><?= esc($peminjaman['tgl_pinjam_dimulai']) ?></p>
                </div>
            </div>
            <div>
                <span class="text-xs font-bold text-gray-500 uppercase">Jadwal Pinjam Selesai</span>
                <div class="mt-1">
                    <p class="font-medium text-gray-900"><?= esc($peminjaman['tgl_pinjam_selesai']) ?></p>
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

                    <?php if ($peminjaman['status_peminjaman_global'] == 'Selesai') : ?>

                        <div class="bg-gray-50 p-4 rounded-lg mt-4 border border-gray-200">
                            <h5 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Laporan Pengembalian</h5>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Kondisi Awal</p>
                                    <?php if ($item['foto_sebelum']) : ?>
                                        <a href="<?= base_url($item['foto_sebelum']) ?>" target="_blank">
                                            <img src="<?= base_url($item['foto_sebelum']) ?>" class="h-24 mx-auto object-cover rounded border hover:opacity-75">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Kondisi Akhir</p>
                                    <?php if ($item['foto_sesudah']) : ?>
                                        <a href="<?= base_url($item['foto_sesudah']) ?>" target="_blank">
                                            <img src="<?= base_url($item['foto_sesudah']) ?>" class="h-24 mx-auto object-cover rounded border hover:opacity-75">
                                        </a>
                                        <p class="text-xs font-bold mt-1 <?= $item['kondisi_akhir'] == 'Baik' ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= esc($item['kondisi_akhir']) ?>
                                        </p>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

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

                    <?php if ($peminjaman['status_peminjaman_global'] == 'Selesai') : ?>

                        <div class="bg-gray-50 p-4 rounded-lg mt-4 border border-gray-200">
                            <h5 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Laporan Pengembalian</h5>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Kondisi Awal</p>
                                    <?php if ($item['foto_sebelum']) : ?>
                                        <a href="<?= base_url($item['foto_sebelum']) ?>" target="_blank">
                                            <img src="<?= base_url($item['foto_sebelum']) ?>" class="h-24 mx-auto object-cover rounded border hover:opacity-75">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>

                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Kondisi Akhir</p>
                                    <?php if ($item['foto_sesudah']) : ?>
                                        <a href="<?= base_url($item['foto_sesudah']) ?>" target="_blank">
                                            <img src="<?= base_url($item['foto_sesudah']) ?>" class="h-24 mx-auto object-cover rounded border hover:opacity-75">
                                        </a>
                                        <p class="text-xs font-bold mt-1 <?= $item['kondisi_akhir'] == 'Baik' ? 'text-green-600' : 'text-red-600' ?>">
                                            <?= esc($item['kondisi_akhir']) ?>
                                        </p>
                                    <?php else: ?>
                                        <span class="text-xs text-gray-400">-</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php endif; ?>

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
                                <p class="font-bold text-sm">Prasarana telah diserahkan</p>
                                <p class="text-xs">Menunggu verifikasi admin untuk penyelesaian akhir.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic">Menunggu status 'Dipinjam' untuk melakukan pengembalian.</p>
                    <?php endif; ?>

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