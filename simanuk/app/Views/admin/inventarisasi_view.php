<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <?php if (session()->getFlashdata('message')) : ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= session()->getFlashdata('message') ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Inventarisasi</h1>
                    <?php if (isset($breadcrumbs)) : ?>
                        <div class="mt-2">
                            <?= render_breadcrumb($breadcrumbs); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <?= $this->include('components/filter_bar', []) ?>

            <div class="mb-4">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="inventoryTab" data-tabs-toggle="#inventoryTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-btn transition-colors duration-200"
                            id="sarana-tab"
                            data-target="sarana-content"
                            type="button"
                            role="tab"
                            aria-selected="<?= $activeTab === 'sarana' ? 'true' : 'false' ?>">
                            Sarana
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 tab-btn transition-colors duration-200"
                            id="prasarana-tab"
                            data-target="prasarana-content"
                            type="button"
                            role="tab"
                            aria-selected="<?= $activeTab === 'prasarana' ? 'true' : 'false' ?>">
                            Prasarana
                        </button>
                    </li>
                </ul>
            </div>

            <div id="inventoryTabContent">

                <div id="sarana-content" role="tabpanel" aria-labelledby="sarana-tab">

                    <div class="flex justify-end mb-4">
                        <a href="<?= site_url('admin/inventaris/sarana/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex justify-center items-center space-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Tambah Sarana</span>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Sarana</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (empty($sarana)) : ?>
                                        <tr>
                                            <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                                Belum ada data sarana.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($sarana as $item) : ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <span class="font-medium text-gray-900"><?= esc($item['nama_sarana']); ?></span>
                                                </td>
                                                <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                    <?= esc($item['kode_sarana']); ?>
                                                </td>
                                                <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                    <?= esc($item['nama_kategori']); ?>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <?php
                                                    $statusClass = match ($item['status_ketersediaan']) {
                                                        'Tersedia' => 'bg-green-100 text-green-800',
                                                        'Dipinjam' => 'bg-yellow-100 text-yellow-800',
                                                        'Perawatan' => 'bg-indigo-100 text-indigo-800',
                                                        'Selesai' => 'bg-gray-100 text-gray-800',
                                                        'Tidak Tersedia' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                    ?>
                                                    <span class="text-sm font-medium px-3 py-1 rounded-full <?= $statusClass ?>">
                                                        <?= esc($item['status_ketersediaan']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                                    <a href="<?= site_url('admin/inventaris/sarana/edit/' . $item['id_sarana']) ?>" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="<?= site_url('admin/inventaris/sarana/' . $item['id_sarana']); ?>" method="post" class="inline-block" onsubmit="return confirm('Hapus sarana ini?');">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="inline-flex items-center px-1 py-1 text-red-500 rounded hover:bg-red-100">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-end p-4">
                            <?= $pager_sarana->links('sarana', 'tailwind_pagination') ?>
                        </div>
                    </div>
                </div>

                <div id="prasarana-content" role="tabpanel" aria-labelledby="prasarana-tab">

                    <div class="flex justify-end mb-4">
                        <a href="<?= site_url('admin/inventaris/prasarana/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex justify-center items-center space-x-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            <span>Tambah Prasarana</span>
                        </a>
                    </div>

                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-max">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Prasarana</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <?php if (empty($prasarana)) : ?>
                                        <tr>
                                            <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                                Belum ada data prasarana.
                                            </td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($prasarana as $item) : ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <span class="font-medium text-gray-900"><?= esc($item['nama_prasarana']); ?></span>
                                                </td>
                                                <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                    <?= esc($item['kode_prasarana']); ?>
                                                </td>
                                                <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                                    <?= esc($item['nama_kategori']); ?>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    <?php
                                                    $statusClass = match ($item['status_ketersediaan']) {
                                                        'Tersedia' => 'bg-green-100 text-green-800',
                                                        'Dipinjam' => 'bg-yellow-100 text-yellow-800',
                                                        'Renovasi' => 'bg-indigo-100 text-indigo-800',
                                                        'Selesai' => 'bg-gray-100 text-gray-800',
                                                        'Tidak Tersedia' => 'bg-red-100 text-red-800',
                                                        default => 'bg-gray-100 text-gray-800',
                                                    };
                                                    ?>
                                                    <span class="text-sm font-medium px-3 py-1 rounded-full <?= $statusClass ?>">
                                                        <?= esc($item['status_ketersediaan']); ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                                    <a href="<?= site_url('admin/inventaris/prasarana/edit/' . $item['id_prasarana']) ?>" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </a>
                                                    <form action="<?= site_url('admin/inventaris/prasarana/' . $item['id_prasarana']); ?>" method="post" class="inline-block" onsubmit="return confirm('Hapus prasarana ini?');">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="inline-flex items-center px-1 py-1 text-red-500 rounded hover:bg-red-100">
                                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="flex justify-end p-4">
                            <?= $pager_prasarana->links('prasarana', 'tailwind_pagination') ?>
                        </div>
                    </div>
                </div>

            </div>

        </main>
    </div>
</div>

<script src="<?= base_url('js/inventaris.js') ?>"></script>

<?= $this->endSection(); ?>