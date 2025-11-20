<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="flex min-h-screen bg-gray-50">
    <div class="flex-1 flex flex-col overflow-hidden">
        <main class="flex-1 overflow-y-auto p-6 md:p-8">

            <?php if (isset($breadcrumbs)) : ?>
                <div class="mb-4">
                    <?= render_breadcrumb($breadcrumbs); ?>
                </div>
            <?php endif; ?>

            <div class="mb-8 max-w-4xl   mx-auto">
                <h1 class="text-3xl font-normal text-gray-800 mb-2">Buat Laporan Kerusakan Barang</h1>
                <p class="text-gray-500 text-sm">
                    Silakan isi formulir di bawah ini untuk melaporkan kerusakan pada barang yang Anda pinjam.
                </p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 max-w-4xl mx-auto">
                
                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        <?= session()->getFlashdata('msg') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('peminjam/laporan-kerusakan/save') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?>

                    <div class="space-y-6">
                        
                        <div>
                            <label for="item_id" class="block text-sm font-medium text-gray-600 mb-2">
                                Item yang Dilaporkan
                            </label>
                            <div class="relative">
                                <select id="item_id" name="item_id" class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg border bg-white text-gray-700 appearance-none">
                                    <option value="" disabled selected>Pilih item yang ingin Anda laporkan</option>
                                    <?php if (!empty($items)) : ?>
                                        <?php foreach ($items as $item) : ?>
                                            <option value="<?= $item['id'] ?>">
                                                <?= esc($item['nama_item']) ?> (<?= esc($item['kode']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>Tidak ada barang yang sedang dipinjam</option>
                                    <?php endif; ?>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">
                                Pilih item berdasarkan nama atau ID inventaris.
                            </p>
                        </div>

                        <div>
                            <label for="tanggal_laporan" class="block text-sm font-medium text-gray-600 mb-2">
                                Tanggal Laporan
                            </label>
                            <div class="relative">
                                <input type="date" 
                                    id="tanggal_laporan" 
                                    name="tanggal_laporan" 
                                    value="<?= date('Y-m-d') ?>"
                                    class="block w-full pl-4 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 sm:text-sm">
                            </div>
                        </div>

                        <div>
                            <label for="deskripsi" class="block text-sm font-medium text-gray-600 mb-2">
                                Deskripsi Kerusakan
                            </label>
                            <textarea 
                                id="deskripsi" 
                                name="deskripsi" 
                                rows="6" 
                                class="block w-full p-4 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-700 sm:text-sm resize-none" 
                                placeholder="Jelaskan kerusakan secara detail di sini..."></textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-3 pt-4">
                            <a href="<?= site_url('peminjam/dashboard') ?>" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-lg transition duration-200 ease-in-out">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition duration-200 ease-in-out focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Kirim Laporan
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </main>
    </div>
</div>

<?= $this->endSection(); ?>