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
                        <p class="text-gray-500 text-lg mt-1">ID: <span class="font-medium text-gray-700"><?= esc($detail['id']) ?></span></p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                        <span class="w-2 h-2 bg-cyan-500 rounded-full mr-2"></span><?= esc($detail['status']) ?>
                    </span>
                </div>

                <?php if (isset($breadcrumbs)) : ?>
                    <div class="mt-2">
                        <?= render_breadcrumb($breadcrumbs); ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6">Informasi Peminjaman</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                                <div>
                                    <p class="text-sm text-gray-500">Peminjam</p>
                                    <p class="font-medium text-gray-900"><?= esc($detail['peminjam']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Barang</p>
                                    <p class="font-medium text-gray-900"><?= esc($detail['barang']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jadwal Pinjam</p>
                                    <p class="font-medium text-gray-900"><?= esc($detail['jadwal_pinjam']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Jadwal Kembali</p>
                                    <p class="font-medium text-gray-900"><?= esc($detail['jadwal_kembali']) ?></p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-500">Tujuan</p>
                                    <p class="font-medium text-gray-900"><?= esc($detail['tujuan']) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Status</h2>
                            <h2 class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition text-center">
                                Selesai
                            </h2>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-semibold text-gray-800 mb-6">Histori Status</h2>
                            <div class="relative border-l border-gray-200 ml-3 space-y-8">
                                <?php foreach ($histori as $log) : ?>
                                    <div class="mb-8 ml-6 relative">
                                        <span class="absolute w-3 h-3 rounded-full -left-[1.95rem] top-1.5 ring-4 ring-white <?= esc($log['color']) ?>"></span>
                                        <span class="text-xs font-semibold text-gray-500 block mb-0.5"><?= esc($log['date']) ?></span>
                                        <p class="text-sm text-gray-800"><?= esc($log['title']) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<?= $this->endSection(); ?>