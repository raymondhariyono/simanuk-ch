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
                                    <a href="<?= site_url('admin/manajemen-akun/edit/' . $user->id) ?>" class="inline-flex items-center px-1 py-1 text-blue-500 rounded hover:bg-blue-100">
                                        <svg class="w-6 h-6 text-gray-800 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.779 17.779 4.36 19.918 6.5 13.5m4.279 4.279 8.364-8.643a3.027 3.027 0 0 0-2.14-5.165 3.03 3.03 0 0 0-2.14.886L6.5 13.5m4.279 4.279L6.499 13.5m2.14 2.14 6.213-6.504M12.75 7.04 17 11.28" />
                                        </svg>
                                    </a>

                                    <form action="<?= site_url('admin/manajemen-akun/delete/' . $user->id) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="inline-flex items-center px-1 py-1 text-red-500 rounded hover:bg-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
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