<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <?php if (isset($breadcrumbs)) : ?>
                        <?= render_breadcrumb($breadcrumbs); ?>
                    <?php endif; ?>
                    <h1 class="text-2xl font-bold text-gray-900 mt-2"><?= esc($judul_laporan) ?></h1>
                    <p class="text-gray-500 text-sm mt-1">Detail data lengkap.</p>
                </div>
                <a href="<?= site_url('pimpinan/lihat-laporan') ?>" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">
                    Kembali
                </a>
            </div>

            

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <?php foreach ($columns as $col) : ?>
                                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider"><?= esc($col) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="<?= count($columns) + 1 ?>" class="px-6 py-8 text-center text-gray-500 text-sm">Data detail tidak tersedia.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($rows as $row) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-500"><?= $no++ ?></td>
                                        
                                        <?php foreach ($row as $key => $val) : ?>
                                            <?php if (strpos($key, 'id_') !== false) continue; ?>
                                            
                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                <?php 
                                                // Format Tanggal jika string terlihat seperti tanggal
                                                if (strtotime($val) && strlen($val) > 10) {
                                                    echo date('d/m/Y H:i', strtotime($val));
                                                } else {
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
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>