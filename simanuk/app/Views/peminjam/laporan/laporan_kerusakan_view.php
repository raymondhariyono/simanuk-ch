<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="container px-6 py-8 mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Riwayat Laporan Kerusakan</h2>
        <a href="<?= site_url('peminjam/laporan-kerusakan/new') ?>" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            Laporkan Kerusakan
        </a>
    </div>

    <?php if (isset($breadcrumbs)) : ?>
        <?= render_breadcrumb($breadcrumbs); ?>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <div class="w-full overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Aset</th>
                        <th class="px-4 py-3">Laporan</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php if (empty($riwayat)) : ?>
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-center text-gray-500">Belum ada laporan kerusakan.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($riwayat as $row) : ?>
                            <tr class="text-gray-700">
                                <td class="px-4 py-3">
                                    <div class="flex items-center text-sm">
                                        <div>
                                            <p class="font-semibold"><?= esc($row['nama_aset']) ?></p>
                                            <p class="text-xs text-gray-600"><?= esc($row['tipe_aset']) ?> | <?= esc($row['kode_aset']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-bold"><?= esc($row['judul_laporan']) ?></p>
                                    <p class="text-xs text-gray-500 truncate w-48"><?= esc($row['deskripsi_kerusakan']) ?></p>
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    <span class="px-2 py-1 font-semibold leading-tight rounded-full 
                                        <?= $row['status_laporan'] == 'Diajukan' ? 'bg-yellow-100 text-yellow-700' : ($row['status_laporan'] == 'Selesai' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700') ?>">
                                        <?= esc($row['status_laporan']) ?>
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <?= date('d M Y', strtotime($row['created_at'])) ?>
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