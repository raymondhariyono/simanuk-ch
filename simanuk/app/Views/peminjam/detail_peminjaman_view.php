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

    <h3 class="text-lg font-bold text-gray-700 mb-4">Daftar Sarana</h3>
    <div class="grid grid-cols-1 gap-6">
        <?php if (!empty($itemsSarana)): ?>
            <?php foreach ($itemsSarana as $item) : ?>
                <div class="bg-white rounded-lg shadow p-6
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

                    <?php if ($peminjaman['status_peminjaman_global'] == 'Dipinjam' && empty($item['foto_sesudah'])) : ?>
                        <div class="mt-2">
                            <a href="<?= site_url('peminjam/laporan-kerusakan/new') ?>?tipe=Sarana&id=<?= $item['id_sarana'] ?>&peminjaman=<?= $peminjaman['id_peminjaman'] ?>"
                                class="text-xs text-red-600 hover:text-red-800 hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Lapor Kerusakan Sarana Ini
                            </a>
                        </div>

                    <?php elseif (!empty($item['foto_sesudah'])) : ?>
                        <div class="flex items-center text-green-700 bg-green-50 p-3 rounded border border-green-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <p class="font-bold text-sm">Sarana telah diserahkan</p>
                                <p class="text-xs">Menunggu verifikasi admin untuk penyelesaian akhir.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic">Menunggu status 'Dipinjam' untuk melakukan pengembalian.</p>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-sm text-gray-400 italic">Tidak ada sarana yang dipinjam.</p>
        <?php endif; ?>

        <?php if (!empty($itemsPrasarana)) : ?>
            <h3 class="text-lg font-bold text-gray-700 mt-6 mb-2">Daftar Prasarana</h3>
            <?php foreach ($itemsPrasarana as $item) : ?>
                <div class="bg-white rounded-lg shadow p-6">
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
                        <div class="mt-2">
                            <a href="<?= site_url('peminjam/laporan-kerusakan/new') ?>"
                                class="text-xs text-red-600 hover:text-red-800 hover:underline flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                Lapor Kerusakan Prasarana Ini
                            </a>
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
        <?php else: ?>
            <p class="text-sm text-gray-400 italic">Tidak ada prasarana yang dipinjam.</p>
        <?php endif; ?>

    </div>
</div>

<script>
    const SITE_URL = "<?= site_url() ?>";
</script>

<script src="<?= base_url('js/peminjam/histori_peminjaman.js') ?>"></script>

<?= $this->endSection(); ?>