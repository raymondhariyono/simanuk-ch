<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
// shield GET & POST
service('auth')->routes($routes);

// shield LOGIN


$routes->get('/auth/redirect', 'Auth::redirect', ['filter' => 'session']);

// 3. Rute untuk dasbor yang berbeda (juga dilindungi)
$routes->group('admin', ['filter' => 'session', 'group:Admin'], static function ($routes) {
   $routes->get('dashboard', 'Admin\DashboardController::index');
});

$routes->group('tu', ['filter' => 'session', 'group:TU'], static function ($routes) {
   $routes->get('dashboard', 'TU\DashboardController::index');
});

$routes->group('peminjam', ['filter' => 'session', 'group:Peminjam'], static function ($routes) {
   $routes->get('dashboard', 'Peminjam\DashboardController::index');
});

$routes->group('pimpinan', ['filter' => 'session', 'group:Pimpinan'], static function ($routes) {
   $routes->get('dashboard', 'Pimpinan\DashboardController::index');
});