<?= $this->extend('layout/template'); ?>

<?= $this->section('title'); ?>
Profil Saya
<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="container px-4 py-6 md:px-6 md:py-8 mx-auto">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-gray-600 text-sm mt-1">Kelola informasi akun dan kata sandi Anda.</p>
    </div>

    <?php if (session('message')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session('errors')) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                <?php foreach (session('errors') as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 text-center">
                <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-3xl font-bold mx-auto mb-4 border-2 border-white shadow-sm">
                    <?= strtoupper(substr(auth()->user()->nama_lengkap ?? 'U', 0, 1)) ?>
                </div>

                <h3 class="text-lg font-bold text-gray-800"><?= esc(auth()->user()->nama_lengkap) ?></h3>
                <p class="text-sm text-gray-500 mb-2"><?= esc(auth()->user()->username) ?></p>

                <div class="mt-4 flex justify-center space-x-2">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                        <?= auth()->user()->organisasi ?? 'Umum' ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-800">Perbarui Profil dan Informasi</h3>
                </div>

                <div class="p-6">
                    <!-- Form Update Profil -->
                    <form action="<?= base_url('profile/update'); ?>" method="post" class="space-y-5">
                        <?= csrf_field(); ?>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-600 mb-2">Alamat Email</label>
                            <input type="email" id="email" name="email" value="<?= esc($user->email); ?>" readonly
                                class="w-full p-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-medium text-gray-600 mb-2">Username</label>
                            <input type="text" id="username" name="username" value="<?= esc($user->username); ?>"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <div>
                            <label for="nama_lengkap" class="block text-sm font-medium text-gray-600 mb-2">Nama Lengkap</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= esc($user->nama_lengkap); ?>"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <div>
                            <label for="organisasi" class="block text-sm font-medium text-gray-600 mb-2">Organisasi / Unit</label>
                            <input type="text" id="organisasi" name="organisasi" value="<?= esc($user->organisasi); ?>"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <div>
                            <label for="kontak" class="block text-sm font-medium text-gray-600 mb-2">No. Kontak / WA</label>
                            <input type="text" id="kontak" name="kontak" value="<?= esc($user->kontak); ?>"
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <button type="submit"
                            class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-semibold">
                            Update Profil
                        </button>
                    </form>

                    <!-- Form Ganti Password -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubah Kata Sandi</h3>
                    <form action="<?= base_url('profile/password'); ?>" method="post" class="space-y-5">
                        <?= csrf_field(); ?>

                        <div>
                            <label for="password_lama" class="block text-sm font-medium text-gray-600 mb-2">Kata Sandi Lama</label>
                            <input type="password" id="password_lama" name="password_lama" required
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <div>
                            <label for="password_baru" class="block text-sm font-medium text-gray-600 mb-2">Kata Sandi Baru</label>
                            <input type="password" id="password_baru" name="password_baru" required
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <div>
                            <label for="konfirmasi_password" class="block text-sm font-medium text-gray-600 mb-2">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="konfirmasi_password" name="konfirmasi_password" required
                                class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>

                        <button type="submit"
                            class="w-full sm:w-auto bg-yellow-400 hover:bg-yellow-500 text-black py-2 px-4 rounded-md font-semibold">
                            Ubah Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
<?= $this->endSection(); ?>