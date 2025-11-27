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

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Manajemen Inventarisasi Sarana Prasarana</h1>
                    <?php if (isset($breadcrumbs)) : ?>
                        <div class="mt-2">
                            <?= render_breadcrumb($breadcrumbs); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center space-x-2">
                    <a href="<?= site_url('admin/inventaris/sarana/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Sarana</span>
                    </a>

                    <a href="<?= site_url('admin/inventaris/prasarana/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        <span>Tambah Prasarana</span>
                    </a>
                </div>
            </div>

            <?= $this->include('components/filter_bar', []) ?>

            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-max">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Item</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (empty($sarana) && empty($prasarana)) : ?>
                                <tr>
                                    <td colspan="5" class="py-6 px-6 text-center text-gray-500">
                                        Belum ada data inventaris (sarana & prasarana).
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($sarana as $barang) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="font-medium text-gray-900"><?= esc($barang['nama_sarana']); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($barang['kode_sarana']); ?>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($barang['nama_kategori']); ?>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="text-sm font-medium px-3 py-1 rounded-full
                                                <?php
                                                switch ($barang['status_ketersediaan']) {
                                                    case 'Tersedia':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'Dipinjam':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Perawatan':
                                                        echo 'bg-indigo-100 text-indigo-800';
                                                        break;
                                                    case 'Selesai':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'Tidak Tersedia':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= esc($barang['status_ketersediaan']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <a href="<?= site_url('admin/inventaris/sarana/edit/' . $barang['id_sarana']) ?>" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100">
                                                <svg class="w-6 h-6 text-gray-800 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                                </svg>
                                            </a>

                                            <form action="<?= site_url('admin/inventaris/sarana/' . $barang['id_sarana']); ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus sarana ini?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="inline-flex items-center px-1 py-1 text-red-500 rounded hover:bg-red-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php foreach ($prasarana as $ruangan) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="font-medium text-gray-900"><?= esc($ruangan['nama_prasarana']); ?></span>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($ruangan['kode_prasarana']); ?>
                                        </td>
                                        <td class="py-4 px-6 text-gray-700 whitespace-nowrap">
                                            <?= esc($ruangan['nama_kategori']); ?>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            <span class="text-sm font-medium px-3 py-1 rounded-full
                                                <?php
                                                switch ($ruangan['status_ketersediaan']) {
                                                    case 'Tersedia':
                                                        echo 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'Dipinjam':
                                                        echo 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    case 'Renovasi':
                                                        echo 'bg-indigo-100 text-indigo-800';
                                                        break;
                                                    case 'Selesai':
                                                        echo 'bg-gray-100 text-gray-800';
                                                        break;
                                                    case 'Tidak Tersedia':
                                                        echo 'bg-red-100 text-red-800';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= esc($ruangan['status_ketersediaan']); ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap text-sm font-medium">
                                            <a href="<?= site_url('admin/inventaris/prasarana/edit/' . $ruangan['id_prasarana']) ?>" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100">
                                                <svg class="w-6 h-6 text-gray-800 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                                </svg>
                                            </a>

                                            <form action="<?= site_url('admin/inventaris/prasarana/' . $ruangan['id_prasarana']); ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus prasarana ini?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="inline-flex items-center px-1 py-1 text-red-500 rounded hover:bg-red-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

                <?= $pager_sarana->links('sarana', 'tailwind_pagination') ?>
            </div>

        </main>
    </div>
</div>
<?= $this->endSection(); ?>