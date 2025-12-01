<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4 md:p-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Riwayat Laporan Kerusakan</h1>
                    <?php if (isset($breadcrumbs)) : ?>
                        <div class="mt-2 overflow-x-auto">
                            <?= render_breadcrumb($breadcrumbs); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?= site_url('peminjam/laporan-kerusakan/new') ?>"
                    class="w-full md:w-auto px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 flex justify-center items-center gap-2 shadow-sm transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>Laporkan Kerusakan</span>
                </a>
            </div>

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span><?= session()->getFlashdata('message') ?></span>
                </div>
            <?php endif; ?>

            <div class="w-full overflow-hidden rounded-lg shadow-lg border border-gray-200 bg-white">
                <div class="w-full overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead>
                            <tr class="text-xs font-bold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                                <th class="px-4 py-3 whitespace-nowrap">Aset / Barang</th>
                                <th class="px-4 py-3 whitespace-nowrap">Detail Laporan</th>
                                <th class="px-4 py-3 whitespace-nowrap">Status</th>
                                <th class="px-4 py-3 whitespace-nowrap">Tanggal Lapor</th>
                                <th class="px-4 py-3 whitespace-nowrap">Tindak Lanjut (Admin)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php if (empty($riwayat)) : ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <p>Belum ada riwayat laporan kerusakan.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($riwayat as $row) : ?>
                                    <tr class="text-gray-700 hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex flex-col">
                                                <p class="font-bold text-gray-900 text-sm"><?= esc($row['nama_aset']) ?></p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded border border-gray-200 font-mono"><?= esc($row['kode_aset']) ?></span>
                                                </p>
                                                <p class="text-xs text-gray-400 mt-0.5 uppercase"><?= esc($row['tipe_aset']) ?></p>
                                            </div>
                                        </td>

                                        <td class="px-4 py-4 align-top" style="min-width: 250px;">
                                            <p class="text-sm font-semibold text-gray-800 mb-1"><?= esc($row['judul_laporan']) ?></p>
                                            <p class="text-sm text-gray-600 leading-snug line-clamp-2" title="<?= esc($row['deskripsi_kerusakan']) ?>">
                                                <?= esc($row['deskripsi_kerusakan']) ?>
                                            </p>
                                        </td>

                                        <td class="px-4 py-4 align-top whitespace-nowrap">
                                            <?php
                                            $statusClass = 'bg-gray-100 text-gray-700';
                                            if ($row['status_laporan'] == 'Diajukan') $statusClass = 'bg-yellow-100 text-yellow-800';
                                            elseif ($row['status_laporan'] == 'Diproses') $statusClass = 'bg-blue-100 text-blue-800';
                                            elseif ($row['status_laporan'] == 'Selesai') $statusClass = 'bg-green-100 text-green-800';
                                            elseif ($row['status_laporan'] == 'Ditolak') $statusClass = 'bg-red-100 text-red-800';
                                            ?>
                                            <span class="px-2.5 py-1 text-xs font-bold rounded-full <?= $statusClass ?>">
                                                <?= esc($row['status_laporan']) ?>
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 align-top whitespace-nowrap text-sm text-gray-500">
                                            <?= date('d M Y', strtotime($row['created_at'])) ?>
                                            <div class="text-xs text-gray-400 mt-1"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                                        </td>

                                        <td class="px-4 py-4 align-top" style="min-width: 200px;">
                                            <?php if (!empty($row['tindak_lanjut'])): ?>
                                                <div class="p-3 bg-blue-50 text-xs text-blue-900 border border-blue-100 rounded-lg">
                                                    <b class="block mb-1 text-blue-700">Tanggapan Admin:</b>
                                                    <?= esc($row['tindak_lanjut']) ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-xs text-gray-400 italic">- Belum ada tanggapan -</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                </div>
            </div>

        </main>
    </div>
</div>

<?= $this->endSection(); ?>