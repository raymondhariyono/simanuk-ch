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

    <?php if (session()->getFlashdata('message')) : ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
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
                    <h3 class="font-semibold text-gray-700">Perbarui Informasi</h3>
                </div>
                
                <div class="p-6">
                    <form action="<?= site_url('profile/update') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="<?= old('nama_lengkap', auth()->user()->nama_lengkap) ?>" 
                                    class="w-full rounded-md border-gray-300 bg-gray-50 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                                <input type="text" name="username" value="<?= old('username', auth()->user()->username) ?>" 
                                    class="w-full rounded-md border-gray-300 bg-gray-50 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="<?= old('email', auth()->user()->email) ?>" 
                                    class="w-full rounded-md border-gray-300 bg-gray-50 text-gray-800 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Organisasi / Unit</label>
                                <input type="text" value="<?= esc(auth()->user()->organisasi) ?>" readonly disabled
                                    class="w-full rounded-md border-gray-300 bg-gray-200 text-gray-500 cursor-not-allowed shadow-sm py-2 px-3">
                                <p class="text-xs text-gray-400 mt-1">Hubungi admin untuk mengubah organisasi.</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6 mt-6">
                            <h4 class="text-sm font-bold text-gray-700 mb-4">Ganti Password <span class="font-normal text-gray-500 text-xs ml-1">(Biarkan kosong jika tidak ingin mengubah)</span></h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                                    <input type="password" name="password" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                    <input type="password" name="pass_confirm" 
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 py-2 px-3">
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection(); ?>