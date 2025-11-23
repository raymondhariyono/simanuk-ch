<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-semibold text-gray-700">Detail Pengembalian</h2>
        <span class="px-3 py-1 text-sm font-semibold text-blue-800 bg-blue-100 rounded-full">
            <?= esc($peminjaman['status_peminjaman_global']) ?>
        </span>
    </div>

    <?php if (isset($breadcrumbs)) : ?>
        <?= render_breadcrumb($breadcrumbs); ?>
    <?php endif; ?>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Informasi Peminjam</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Nama Lengkap</span>
                    <span class="font-medium"><?= esc($peminjaman['nama_lengkap']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">NIM / Username</span>
                    <span class="font-medium"><?= esc($peminjaman['username']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Organisasi</span>
                    <span class="font-medium"><?= esc($peminjaman['organisasi']) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Kontak</span>
                    <span class="font-medium"><?= esc($peminjaman['kontak']) ?></span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Detail Peminjaman</h3>
            <div class="space-y-3 text-sm">
                <div class="flex flex-col">
                    <span class="text-gray-500 text-xs uppercase font-bold">Kegiatan</span>
                    <span class="font-medium text-gray-900"><?= esc($peminjaman['kegiatan']) ?></span>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-2">
                    <div>
                        <span class="text-gray-500 text-xs uppercase font-bold">Mulai</span>
                        <p class="font-medium"><?= date('d M Y H:i', strtotime($peminjaman['tgl_pinjam_dimulai'])) ?></p>
                    </div>
                    <div>
                        <span class="text-gray-500 text-xs uppercase font-bold">Selesai (Target)</span>
                        <p class="font-medium text-red-600"><?= date('d M Y H:i', strtotime($peminjaman['tgl_pinjam_selesai'])) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Bukti Pengembalian (Dari Peminjam)</h3>
        <?php if (!empty($peminjaman['bukti_pengembalian'])) : ?>
            <div class="w-full md:w-1/3">
                <img src="<?= base_url('uploads/peminjaman/bukti_akhir/' . $peminjaman['bukti_pengembalian']) ?>" 
                     alt="Bukti Pengembalian" 
                     class="rounded-lg shadow-md border hover:opacity-90 transition cursor-pointer"
                     onclick="window.open(this.src)">
                <p class="text-xs text-center text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
            </div>
        <?php else : ?>
            <div class="p-4 bg-yellow-50 text-yellow-700 rounded border border-yellow-100 text-sm">
                Belum ada bukti foto pengembalian yang diunggah oleh peminjam. Pastikan cek fisik barang secara langsung.
            </div>
        <?php endif; ?>
    </div>

    <form action="<?= site_url('tu/pengembalian/proses/' . $peminjaman['id_peminjaman']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin semua barang telah kembali dan sesuai kondisinya? Stok akan dikembalikan ke sistem.');">
        <?= csrf_field() ?>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Daftar Barang & Cek Kondisi</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-xs uppercase text-gray-500 font-bold border-b">
                            <th class="px-6 py-3">Barang / Ruangan</th>
                            <th class="px-6 py-3 text-center">Jumlah Pinjam</th>
                            <th class="px-6 py-3">Kondisi Awal</th>
                            <th class="px-6 py-3">Kondisi Akhir (Verifikasi)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        <?php foreach ($itemsSarana as $item) : ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ml-0">
                                            <p class="font-bold text-gray-900"><?= esc($item['nama_sarana']) ?></p>
                                            <p class="text-xs text-gray-500"><?= esc($item['kode_sarana']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-blue-600">
                                    <?= esc($item['jumlah']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                        <?= esc($item['kondisi_awal']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <select name="kondisi_akhir[<?= $item['id'] ?>]" class="form-select text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <option value="Baik" selected>Baik</option>
                                        <option value="Rusak Ringan">Rusak Ringan</option>
                                        <option value="Rusak Berat">Rusak Berat</option>
                                        <option value="Hilang">Hilang</option>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($itemsPrasarana as $item) : ?>
                            <tr class="hover:bg-gray-50 bg-gray-50/50">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?= esc($item['nama_prasarana']) ?></div>
                                    <div class="text-xs text-gray-500">Ruangan</div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-400">-</td>
                                <td class="px-6 py-4 text-gray-500">Baik</td>
                                <td class="px-6 py-4 text-gray-400 italic">
                                    Cek Kebersihan
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="<?= site_url('tu/pengembalian') ?>" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 font-medium transition">
                Batal / Kembali
            </a>
            <button type="submit" class="px-6 py-3 rounded-lg bg-green-600 text-white hover:bg-green-700 font-bold shadow-lg transition transform hover:-translate-y-0.5">
                <i class="fas fa-check-circle mr-2"></i> Verifikasi & Selesai
            </button>
        </div>
    </form>
</div>
<?= $this->endSection(); ?>