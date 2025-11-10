

<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Dashboard' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-lg shadow-md">
   <h1 class="text-2xl font-bold mb-4">Dashboard Peminjam</h1>
   <p>Selamat datang, <?= auth()->user()->nama_lengkap ?> dari <?= auth()->user()->organisasi ?>.</p>
   <p>Silakan gunakan menu sidebar untuk melihat katalog sarpras, mengajukan peminjaman, atau melihat riwayat peminjaman Anda.</p>

   <!-- (Nanti di sini bisa ditambahkan status peminjaman yang sedang aktif) -->

</div>
<?= $this->endSection() ?>