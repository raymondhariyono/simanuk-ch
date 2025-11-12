<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
// shield GET & POST
service('auth')->routes($routes);
// redirect setelah login
$routes->get('/auth/redirect', 'Auth::redirect', ['filter' => 'session']);

// 3. Rute untuk dasbor yang berbeda (juga dilindungi)
$routes->group('admin', ['filter' => 'role:Admin'], static function ($routes) {
   $routes->get('dashboard', 'Admin\DashboardController::index');
});
$routes->group('peminjam', ['filter' => 'role:Peminjam'], static function ($routes) {
   $routes->get('dashboard', 'Peminjam\DashboardController::index');
});

$routes->group('admin', ['filter' => 'session', 'group:Admin'], static function ($routes) {
   $routes->get('dashboard', 'Admin\DashboardController::index');
});

// ----------------------------------------------------
// DILINDUNGI: Rute untuk Tata Usaha (UC03)
// Hanya dapat diakses oleh user yang berada di grup 'TU'
// ----------------------------------------------------
$routes->group('tu', ['filter' => 'session', 'group:TU'], static function ($routes) {
   $routes->get('dashboard', 'TU\DashboardController::index');

   // ** KELOLA AKUN PENGGUNA **
   $routes->get('kelola/akun', 'TU\UserController::index', ['as' => 'tu-users']); // READ (Daftar Pengguna)
   $routes->get('kelola/akun/new', 'TU\UserController::create'); // CREATE - Tampil Form
   $routes->post('kelola/akun/save', 'TU\UserController::save'); // CREATE - Proses Simpan
   $routes->get('kelola/akun/edit/(:num)', 'TU\UserController::edit/$1'); // UPDATE - Tampil Form
   $routes->post('kelola/akun/update/(:num)', 'TU\UserController::update/$1'); // UPDATE - Proses Update
   $routes->post('kelola/akun/delete/(:num)', 'TU\UserController::delete/$1'); // DELETE
});

$routes->group('peminjam', ['filter' => 'session', 'group:Peminjam'], static function ($routes) {
   $routes->get('dashboard', 'Peminjam\DashboardController::index');
});

$routes->group('pimpinan', ['filter' => 'session', 'group:Pimpinan'], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
});

// Rute Umum (Profil Saya)
$routes->group('profile', ['filter' => 'session'], static function ($routes) {
   $routes->get('/', 'ProfileController::index', ['as' => 'profile-view']);
   $routes->post('update', 'ProfileController::update');
   $routes->post('password', 'ProfileController::changePassword');
});
