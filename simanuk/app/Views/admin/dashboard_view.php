<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Dashboard' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-lg shadow-md">
   <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>
   <p>Selamat datang di halaman admin, <?= auth()->user()->nama_lengkap ?>.</p>
   <p>Di sini Anda dapat mengelola inventaris, melihat laporan kerusakan, dan memverifikasi pengembalian barang.</p>

   
</div>
<?= $this->endSection() ?>