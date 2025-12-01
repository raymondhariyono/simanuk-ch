<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Verifikasi Pengembalian (TU)</h2>
    </div>

    <?php if (isset($breadcrumbs)) : ?>
        <?= render_breadcrumb($breadcrumbs); ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <div class="w-full overflow-hidden rounded-lg shadow-xs bg-white border border-gray-200">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Peminjam</th>
                        <th class="px-4 py-3">Kegiatan</th>
                        <th class="px-4 py-3">Tenggat Waktu</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (empty($peminjaman)) : ?>
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Tidak ada barang yang sedang dipinjam saat ini.
                            </td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($peminjaman as $row) : ?>
                            <tr class="text-gray-700 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold"><?= esc($row['nama_lengkap']) ?></p>
                                            <p class="text-xs text-gray-600"><?= esc($row['organisasi']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <?= esc($row['kegiatan']) ?>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <?php 
                                        $tglSelesai = strtotime($row['tgl_pinjam_selesai']);
                                        $now = time();
                                        $isLate = $now > $tglSelesai;
                                    ?>
                                    <span class="<?= $isLate ? 'text-red-600 font-bold' : '' ?>">
                                        <?= date('d M Y H:i', $tglSelesai) ?>
                                    </span>
                                    <?php if($isLate): ?>
                                        <span class="text-xs text-red-500 block">(Terlambat)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <span class="px-2 py-1 font-semibold leading-tight text-indigo-700 bg-indigo-100 rounded-full">
                                        <?= esc($row['status_peminjaman_global']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="<?= site_url('tu/pengembalian/detail/' . $row['id_peminjaman']) ?>"
                                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none shadow-sm transition-colors duration-150">
                                        Proses Kembali
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>