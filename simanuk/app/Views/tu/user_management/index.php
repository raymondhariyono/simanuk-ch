<?= $this->extend('layout/template') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Kelola Akun' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="bg-white p-6 rounded-lg shadow-md">
   <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Daftar Akun Pengguna</h1>
      <a href="<?= site_url('tu/kelola/akun/new') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
         + Tambah Akun Baru
      </a>
   </div>

   <?= $this->include('components/alert') ?>

   <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
         <thead class="bg-gray-50">
            <tr>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email/Username</th>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
               <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
         </thead>
         <tbody class="bg-white divide-y divide-gray-200">
            <?php foreach ($users as $user): ?>
               <tr>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $user['id'] ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= $user['nama_lengkap'] ?></td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                     <?= $user['email'] ?> (<?= $user['username'] ?>)
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                     <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $user['nama_role'] === 'Admin' ? 'bg-red-100 text-red-800' : ($user['nama_role'] === 'TU' ? 'bg-blue-100 text-blue-800' : ($user['nama_role'] === 'Peminjam' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800')) ?>">
                        <?= $user['nama_role'] ?>
                     </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                     <?= $user['deleted_at'] ? '<span class="text-red-500">Non-aktif</span>' : '<span class="text-green-500">Aktif</span>' ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                     <a href="<?= site_url('tu/kelola/akun/edit/' . $user['id']) ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>

                     <!-- Tombol Hapus/Non-aktif -->
                     <?php if (empty($user['deleted_at']) && $user['id'] !== auth()->user()->id): ?>
                        <form action="<?= site_url('tu/kelola/akun/delete/' . $user['id']) ?>" method="post" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun ini?');">
                           <?= csrf_field() ?>
                           <button type="submit" class="text-red-600 hover:text-red-900">Non-aktif</button>
                        </form>
                     <?php elseif ($user['id'] !== auth()->user()->id) : ?>
                        <!-- TODO: Tambahkan tombol aktivasi jika diperlukan -->
                        <span class="text-gray-400">Non-aktif</span>
                     <?php endif; ?>
                  </td>
               </tr>
            <?php endforeach; ?>
         </tbody>
      </table>
   </div>
</div>
<?= $this->endSection() ?>