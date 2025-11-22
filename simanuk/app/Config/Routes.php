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

   // PRASARANA
   $routes->get('inventaris', 'Admin\InventarisasiController::index');
   $routes->get('inventaris/prasarana/create', 'Admin\PrasaranaController::create');
   $routes->post('inventaris/prasarana/save', 'Admin\PrasaranaController::save');
   $routes->delete('inventaris/prasarana/(:num)', 'Admin\PrasaranaController::delete/$1');
   $routes->get('inventaris/prasarana/edit/(:num)', 'Admin\PrasaranaController::edit/$1');
   $routes->post('inventaris/prasarana/update/(:num)', 'Admin\PrasaranaController::update/$1');

   // --- KELOLA PEMINJAMAN ---
   $routes->get('peminjaman', 'Admin\PeminjamanController::index');
   $routes->get('peminjaman/detail/(:num)', 'Admin\PeminjamanController::detail/$1');
   // Route Proses Peminjaman
   $routes->post('peminjaman/approve/(:num)', 'Admin\PeminjamanController::approve/$1');
   $routes->post('peminjaman/reject/(:num)', 'Admin\PeminjamanController::reject/$1');

   // --- KELOLA PENGEMBALIAN ---

   // --- LAPORAN KERUSAKAN ---
   $routes->get('laporan-kerusakan', 'Admin\LaporanKerusakanController::index');
   // --- MANAJEMEN AKUN PENGGUNA ---
   $routes->get('manajemen-akun', 'Admin\ManajemenAkunController::index');
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

   // --- HISTORI PEMINJAMAN ---
   $routes->get('histori-peminjaman', 'Peminjam\HistoriPeminjamanController::index');
   // detail histori peminjaman
   $routes->get('histori-peminjaman/detail/(:segment)', 'Peminjam\HistoriPeminjamanController::detail/$1');
   // CRUD peminjaman
   $routes->get('peminjaman/new', 'Peminjam\PeminjamanController::new'); // Form view
   $routes->post('peminjaman/create', 'Peminjam\PeminjamanController::create'); // Save
   $routes->post('peminjaman/delete-item/(:segment)/(:num)', 'Peminjam\PeminjamanController::deleteItem/$1/$2'); // batal (delete) untuk item tertentu
   // $routes->post('peminjaman/delete/(:num)', 'Peminjam\PeminjamanController::delete/$1'); // Batal (Delete)

   // --- HISTORI PENGEMBALIAN ---
   $routes->get('histori-pengembalian', 'Peminjam\HistoriPengembalianController::index');
   // detail histori pengembalian
   $routes->get('histori-pengembalian/detail/(:segment)', 'Peminjam\HistoriPengembalianController::detail/$1');

   // --- LAPORAN KERUSAKAN ---
   $routes->get('laporan-kerusakan', 'Peminjam\LaporanKerusakanController::index');
   // Memproses pengiriman laporan (submit form)
   $routes->post('laporan-kerusakan/save', 'Peminjam\LaporanKerusakanController::store');
});

$routes->group('pimpinan', ['filter' => ['session', 'role:Pimpinan']], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
});

// ----------------------------------------------------
// Rute untuk Tata Usaha 
// ----------------------------------------------------
$routes->group('tu', ['filter' => ['session', 'role:TU']], static function ($routes) {
   $routes->get('dashboard', 'TU\DashboardController::index');

   // ** KELOLA AKUN PENGGUNA **
   $routes->get('kelola/akun', 'TU\UserController::index', ['as' => 'tu-users']); // READ (Daftar Pengguna)
   $routes->get('kelola/akun/new', 'TU\UserController::create'); // CREATE - Tampil Form
   $routes->post('kelola/akun/save', 'TU\UserController::save'); // CREATE - Proses Simpan
   $routes->get('kelola/akun/edit/(:num)', 'TU\UserController::edit/$1'); // UPDATE - Tampil Form
   $routes->post('kelola/akun/update/(:num)', 'TU\UserController::update/$1'); // UPDATE - Proses Update
   $routes->post('kelola/akun/delete/(:num)', 'TU\UserController::delete/$1'); // DELETE
});

// ----------------------------------------------------
// Rute untuk Pimpinan 
// ----------------------------------------------------
$routes->group('pimpinan', ['filter' => ['session', 'role:Pimpinan']], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
});



// Rute Umum (Profil Saya)
$routes->group('profile', ['filter' => 'session'], static function ($routes) {
   $routes->get('/', 'ProfileController::index', ['as' => 'profile-view']);
   $routes->post('update', 'ProfileController::update');
   $routes->post('password', 'ProfileController::changePassword');
});
