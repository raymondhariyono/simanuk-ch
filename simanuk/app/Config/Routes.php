<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::redirect');
// shield GET & POST
service('auth')->routes($routes);

// Shield routes dengan filter guest
$routes->group('', ['filter' => 'guest'], static function ($routes) {
   service('auth')->routes($routes, ['except' => ['logout']]); // Exclude logout dari filter guest
});

// Logout route (tanpa filter guest)
$routes->get('logout', '\CodeIgniter\Shield\Controllers\LoginController::logoutAction', ['as' => 'logout']);

// redirect setelah login
$routes->get('/auth/redirect', 'AuthController::redirect', ['filter' => 'session']);

// ----------------------------------------------------
// Rute untuk Admin
// ----------------------------------------------------
$routes->group('admin', ['filter' => ['session', 'role:Admin']], static function ($routes) {
   $routes->get('dashboard', 'Admin\DashboardController::index');

   // --- INVENTARISASI ---
   // SARANA
   $routes->get('inventaris', 'Admin\InventarisasiController::index');
   $routes->get('inventaris/sarana/create', 'Admin\SaranaController::create');
   $routes->post('inventaris/sarana/save', 'Admin\SaranaController::save');
   $routes->delete('inventaris/sarana/(:num)', 'Admin\SaranaController::delete/$1');
   $routes->get('inventaris/sarana/edit/(:num)', 'Admin\SaranaController::edit/$1');
   $routes->post('inventaris/sarana/update/(:num)', 'Admin\SaranaController::update/$1');

   // delete foto
   $routes->get('inventaris/sarana/foto/delete/(:num)', 'Admin\SaranaController::deleteFoto/$1');

   // PRASARANA
   $routes->get('inventaris', 'Admin\InventarisasiController::index');
   $routes->get('inventaris/prasarana/create', 'Admin\PrasaranaController::create');
   $routes->post('inventaris/prasarana/save', 'Admin\PrasaranaController::save');
   $routes->delete('inventaris/prasarana/(:num)', 'Admin\PrasaranaController::delete/$1');
   $routes->get('inventaris/prasarana/edit/(:num)', 'Admin\PrasaranaController::edit/$1');
   $routes->post('inventaris/prasarana/update/(:num)', 'Admin\PrasaranaController::update/$1');

   // delete foto
   $routes->get('inventaris/prasarana/foto/delete/(:num)', 'Admin\PrasaranaController::deleteFoto/$1');

   // --- KELOLA PEMINJAMAN ---
   $routes->get('peminjaman', 'Admin\PeminjamanController::index');
   $routes->get('peminjaman/detail/(:num)', 'Admin\PeminjamanController::detail/$1');
   // Route Proses Peminjaman
   $routes->post('peminjaman/approve/(:num)', 'Admin\PeminjamanController::approve/$1');
   $routes->post('peminjaman/reject/(:num)', 'Admin\PeminjamanController::reject/$1');
   // tolak foto SEBELUM dan SESUDAH
   $routes->post('peminjaman/tolak-foto/(:segment)/(:segment)/(:num)', 'Admin\PeminjamanController::tolakFoto/$1/$2/$3');

   // --- KELOLA PENGEMBALIAN ---
   $routes->get('pengembalian', 'Admin\PengembalianController::index');
   $routes->get('pengembalian/detail/(:num)', 'Admin\PengembalianController::detail/$1');
   $routes->post('pengembalian/selesai/(:num)', 'Admin\PengembalianController::prosesKembali/$1');

   // --- LAPORAN KERUSAKAN ---
   $routes->get('laporan-kerusakan', 'Admin\LaporanKerusakanController::index');
   $routes->post('laporan-kerusakan/update/(:num)', 'Admin\LaporanKerusakanController::updateStatus/$1');

   $routes->get('laporan-kerusakan/new', 'Admin\LaporanKerusakanController::new');
   $routes->post('laporan-kerusakan/create', 'Admin\LaporanKerusakanController::create');

   // --- MANAJEMEN AKUN PENGGUNA ---
   $routes->get('manajemen-akun', 'Admin\ManajemenAkunController::index');
   $routes->get('manajemen-akun/new', 'Admin\ManajemenAkunController::new');
   $routes->post('manajemen-akun/create', 'Admin\ManajemenAkunController::create');
   $routes->get('manajemen-akun/edit/(:num)', 'Admin\ManajemenAkunController::edit/$1');
   $routes->post('manajemen-akun/update/(:num)', 'Admin\ManajemenAkunController::update/$1');
   $routes->post('manajemen-akun/delete/(:num)', 'Admin\ManajemenAkunController::delete/$1');

   // data master (KATEGORI & LOKASI)
   $routes->get('master', 'Admin\MasterDataController::index');

   $routes->post('master/kategori/store', 'Admin\MasterDataController::storeKategori');
   $routes->post('master/kategori/delete/(:num)', 'Admin\MasterDataController::deleteKategori/$1');

   $routes->post('master/lokasi/store', 'Admin\MasterDataController::storeLokasi');
   $routes->post('master/lokasi/delete/(:num)', 'Admin\MasterDataController::deleteLokasi/$1');
});

// ----------------------------------------------------
// Rute untuk Peminjam (UKM & Ormawa) 
// ----------------------------------------------------
$routes->group('peminjam', ['filter' => ['session', 'role:Peminjam']], static function ($routes) {
   $routes->get('dashboard', 'Peminjam\DashboardController::index');
   // --- KATALOG SAPRAS ---
   $routes->get('sarpras', 'Peminjam\KatalogSarprasController::index');
   // detail dari tiap katalog sarpras
   $routes->get('sarpras/detail/(:segment)', 'Peminjam\KatalogSarprasController::detail/$1');
   $routes->get('sarpras/filter', 'Peminjam\KatalogSarprasController::index');

   // --- HISTORI PEMINJAMAN ---
   $routes->get('histori-peminjaman', 'Peminjam\HistoriPeminjamanController::index');

   // $routes->get('histori-peminjaman2', 'Peminjam\HistoriPeminjamanController::index2');

   // detail histori peminjaman
   // Untuk melihat halaman detail
   $routes->get('histori-peminjaman/detail/(:num)', 'Peminjam\HistoriPeminjamanController::detail/$1');

   // Untuk proses aksi form pengembalian
   $routes->post('peminjaman/kembalikan-item/(:segment)/(:num)', 'Peminjam\PeminjamanController::kembalikanItem/$1/$2');

   // CRUD peminjaman
   $routes->get('peminjaman/new', 'Peminjam\PeminjamanController::new'); // Form view
   $routes->post('peminjaman/create', 'Peminjam\PeminjamanController::create'); // Save
   $routes->post('peminjaman/delete-item/(:segment)/(:num)', 'Peminjam\PeminjamanController::deleteItem/$1/$2'); // batal (delete) untuk item tertentu

   // API Check Availability
   $routes->get('api/check-prasarana/(:num)', 'Peminjam\PeminjamanController::checkPrasaranaAvailability/$1');

   // upload bukti foto (SEBELUM)
   $routes->post('peminjaman/upload-bukti-sebelum/(:segment)/(:num)', 'Peminjam\PeminjamanController::uploadBuktiSebelum/$1/$2');
   // upload bukti foto (SESUDAH)
   $routes->post('peminjaman/upload-bukti-sesudah/(:segment)/(:num)', 'Peminjam\PeminjamanController::uploadBuktiSesudah/$1/$2');

   // --- LAPORAN KERUSAKAN ---
   $routes->get('laporan-kerusakan', 'Peminjam\LaporanKerusakanController::index');
   // Memproses pengiriman laporan (submit form)
   $routes->get('laporan-kerusakan/new', 'Peminjam\LaporanKerusakanController::new');
   $routes->post('laporan-kerusakan/create', 'Peminjam\LaporanKerusakanController::create');
});

$routes->group('pimpinan', ['filter' => ['session', 'role:Pimpinan']], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
   $routes->get('lihat-laporan/cetak', 'Pimpinan\LaporanController::cetak');
});

// ----------------------------------------------------
// Rute untuk Tata Usaha 
// ----------------------------------------------------
$routes->group('tu', ['filter' => ['session', 'role:TU']], static function ($routes) {
   $routes->get('dashboard', 'TU\DashboardController::index');

   // ** KELOLA AKUN PENGGUNA **

   $routes->get('peminjaman', 'TU\VerifikasiPeminjamanController::index');
   $routes->get('peminjaman/detail/(:num)', 'TU\VerifikasiPeminjamanController::detail/$1');
   $routes->post('peminjaman/approve/(:num)', 'TU\VerifikasiPeminjamanController::approve/$1');
   $routes->post('peminjaman/reject/(:num)', 'TU\VerifikasiPeminjamanController::reject/$1');
   $routes->get('verifikasi-pengembalian', 'TU\PengembalianController::index');

   $routes->get('peminjaman', 'TU\PeminjamanController::index');
   // tolak foto SEBELUM dan SESUDAH
   $routes->post('peminjaman/tolak-foto/(:segment)/(:segment)/(:num)', 'TU\VerifikasiPeminjamanController::tolakFoto/$1/$2/$3');

   $routes->get('kelola-laporan-kerusakan', 'TU\LaporanKerusakanController::index');

   // --- LAPORAN KERUSAKAN ---
   $routes->get('laporan-kerusakan', 'TU\LaporanKerusakanController::index');
   $routes->post('laporan-kerusakan/update/(:num)', 'TU\LaporanKerusakanController::updateStatus/$1');

   $routes->get('generate-laporan', 'TU\LaporanController::index');
   $routes->get('pengembalian/detail/(:num)', 'TU\PengembalianController::detail/$1');
   $routes->post('pengembalian/selesai/(:num)', 'TU\PengembalianController::prosesKembali/$1');
   $routes->get('download-laporan', 'TU\DashboardController::downloadLaporan');
   $routes->get('laporan/excel', 'TU\DashboardController::downloadExcel');
   $routes->get('laporan/pdf', 'TU\DashboardController::downloadLaporan');
});

// ----------------------------------------------------
// Rute untuk Pimpinan 
// ----------------------------------------------------
$routes->group('pimpinan', ['filter' => ['session', 'role:Pimpinan']], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
   $routes->get('lihat-laporan', 'Pimpinan\LaporanController::index');
   $routes->get('lihat-laporan/detail', 'Pimpinan\LaporanController::detail');
   $routes->get('lihat-laporan/cetak', 'Pimpinan\LaporanController::cetak');
   $routes->get('lihat-laporan/excel', 'Pimpinan\LaporanController::excel');
});



// Rute Umum (Profil Saya)
$routes->group('profile', ['filter' => 'session'], static function ($routes) {
   $routes->get('/', 'ProfileController::index', ['as' => 'profile-view']);
   $routes->post('update', 'ProfileController::update');
   $routes->post('password', 'ProfileController::changePassword');
});
