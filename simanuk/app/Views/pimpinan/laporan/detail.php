<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-4 md:p-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <?php if (isset($breadcrumbs)) : ?>
                        <?= render_breadcrumb($breadcrumbs); ?>
                    <?php endif; ?>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2"><?= esc($judul_laporan) ?></h1>
                    <p class="text-gray-500 text-sm mt-1">Detail data lengkap periode <?= esc($periode ?? '-') ?>.</p>
                </div>
                <a href="<?= site_url('pimpinan/lihat-laporan') . (isset($filterBulan) ? '?bulan=' . $filterBulan : '') ?>" 
                   class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition-colors">
                    &larr; Kembali
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">No</th>
                                <?php foreach ($columns as $col) : ?>
                                    <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                        <?= esc($col) ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="<?= count($columns) + 1 ?>" class="px-6 py-8 text-center text-gray-500 text-sm">
                                        <div class="flex flex-col items-center justify-center p-4">
                                            <span class="text-gray-400 mb-2">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </span>
                                            Data detail tidak tersedia untuk periode ini.
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1;
                                foreach ($rows as $row) : ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"><?= $no++ ?></td>

                                        <?php foreach ($row as $key => $val) : ?>
                                            <?php if (strpos($key, 'id_') !== false) continue; ?>

                                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap align-middle">
                                                <?php
                                                // 1. Cek apakah kolom ini adalah Foto/Gambar
                                                // Kita cek nama key mengandung kata 'foto' atau 'bukti'
                                                if ((strpos($key, 'foto') !== false || strpos($key, 'bukti') !== false)) {
                                                    if (!empty($val)) {
                                                        // Tampilkan Gambar Thumbnail
                                                        ?>
                                                        <a href="<?= base_url($val) ?>" target="_blank" class="group relative block w-16 h-16 overflow-hidden rounded-lg border border-gray-200">
                                                            <img src="<?= base_url($val) ?>" 
                                                                 alt="Bukti" 
                                                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                                 loading="lazy">
                                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-opacity"></div>
                                                        </a>
                                                        <?php
                                                    } else {
                                                        echo '<span class="text-gray-400 italic text-xs bg-gray-100 px-2 py-1 rounded">Tidak ada foto</span>';
                                                    }
                                                }
                                                // 2. Format Tanggal
                                                elseif (strtotime($val) && strlen($val) > 10 && !is_numeric($val)) {
                                                    echo '<span class="text-gray-600">' . date('d M Y H:i', strtotime($val)) . '</span>';
                                                } 
                                                // 3. Teks Biasa
                                                else {
                                                    echo esc($val);
                                                }
                                                ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-xs text-gray-500">
                    Menampilkan <?= count($rows) ?> data baris.
                </div>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>