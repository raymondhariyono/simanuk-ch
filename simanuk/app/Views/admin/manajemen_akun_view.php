<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
<?= esc($title); ?>
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container px-6 py-8 mx-auto">
    <h2 class="text-2xl font-semibold text-gray-700 mb-6">Daftar Pengguna</h2>

    <div class="flex justify-end mb-4">
        <a href="<?= site_url('admin/manajemen-akun/new') ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
            + Tambah Akun Baru
        </a>
    </div>

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('message') ?></div>
    <?php elseif (session()->getFlashdata('error')) : ?>
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded mb-4"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50">
                        <th class="px-4 py-3">Nama & Kontak</th>
                        <th class="px-4 py-3">Username & Email</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y">
                    <?php foreach ($users as $user) : ?>
                        <tr class="text-gray-700">
                            <td class="px-4 py-3">
                                <p class="font-semibold text-sm"><?= esc($user->nama_lengkap) ?></p>
                                <p class="text-xs text-gray-600"><?= esc($user->organisasi) ?></p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium"><?= esc($user->username) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($user->email) ?></p>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <span class="px-2 py-1 font-semibold leading-tight rounded-full bg-blue-100 text-blue-700">
                                    <?= esc($user->nama_role) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center space-x-2">
                                    <a href="<?= site_url('admin/manajemen-akun/edit/' . $user->id) ?>" class="text-green-600 hover:text-green-800">Edit</a>

                                    <form action="<?= site_url('admin/manajemen-akun/delete/' . $user->id) ?>" method="post" onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>