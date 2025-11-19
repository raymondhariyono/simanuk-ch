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
});

// ----------------------------------------------------
// Rute untuk Peminjam (UKM & Ormawa) 
// ----------------------------------------------------
$routes->group('peminjam', ['filter' => ['session', 'role:Peminjam']], static function ($routes) {
   $routes->get('dashboard', 'Peminjam\DashboardController::index');
   $routes->get('sarpras', 'Peminjam\SarprasController::index');
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
