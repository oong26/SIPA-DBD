<?php

namespace Config;

use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\DiagnosaController;
use App\Controllers\Admin\GejalaController;
use App\Controllers\Admin\PengetahuanController;
use App\Controllers\Admin\PenyakitController;
use App\Controllers\Admin\SolusiController;
use App\Controllers\Admin\UsersController;
use App\Controllers\Home;
use App\Controllers\User\LandingController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override(function () {
    return view('errors/error404');
});
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//! ADMIN
$routes->get('/dashboard', [DashboardController::class, 'index'], ['filter' => 'role:admin']);
//!RULES BASE
$routes->get('/rules-base', [DashboardController::class, 'rules'], ['filter' => 'role:admin']);

//! ROUTES PENGGUNA
$routes->get('/data-pengguna', [UsersController::class, 'index'], ['filter' => 'role:admin']);

//! ROUTES PENYAKIT
$routes->get('/data-penyakit', [PenyakitController::class, 'index'], ['filter' => 'role:admin']);
$routes->get('/data-penyakit/create', [PenyakitController::class, 'create'], ['filter' => 'role:admin']);
$routes->post('/data-penyakit/insert', [PenyakitController::class, 'insert'], ['filter' => 'role:admin']);
$routes->post('/data-penyakit/update/(:any)', [PenyakitController::class, 'update/$1'], ['filter' => 'role:admin']);
$routes->get('/data-penyakit/edit/(:segment)', [PenyakitController::class, 'edit/$1'], ['filter' => 'role:admin']);
$routes->delete('/data-penyakit/(:any)', [PenyakitController::class, 'delete'], ['filter' => 'role:admin']);

//! ROUTES GEJALA
$routes->get('/data-gejala', [GejalaController::class, 'index'], ['filter' => 'role:admin']);
$routes->get('/data-gejala/create', [GejalaController::class, 'create'], ['filter' => 'role:admin']);
$routes->post('/data-gejala/insert', [GejalaController::class, 'insert'], ['filter' => 'role:admin']);
$routes->post('/data-gejala/update/(:any)', [GejalaController::class, 'update/$1'], ['filter' => 'role:admin']);
$routes->get('/data-gejala/edit/(:segment)', [GejalaController::class, 'edit/$1'], ['filter' => 'role:admin']);
$routes->delete('/data-gejala/(:any)', [GejalaController::class, 'delete'], ['filter' => 'role:admin']);

//! ROUTES BASIS PENGETAHUAN
$routes->get('/basis-pengetahuan', [PengetahuanController::class, 'index'], ['filter' => 'role:admin']);
$routes->get('/basis-pengetahuan/create', [PengetahuanController::class, 'create'], ['filter' => 'role:admin']);
$routes->post('/basis-pengetahuan/insert', [PengetahuanController::class, 'insert'], ['filter' => 'role:admin']);
$routes->post('/basis-pengetahuan/update/(:any)', [PengetahuanController::class, 'update/$1'], ['filter' => 'role:admin']);
$routes->get('/basis-pengetahuan/edit/(:segment)', [PengetahuanController::class, 'edit/$1'], ['filter' => 'role:admin']);
$routes->delete('/basis-pengetahuan/(:any)', [PengetahuanController::class, 'delete'], ['filter' => 'role:admin']);

//! ROUTES SOLUSI
$routes->get('/data-solusi', [SolusiController::class, 'index'], ['filter' => 'role:admin']);
$routes->get('/data-solusi/create', [SolusiController::class, 'create'], ['filter' => 'role:admin']);
$routes->post('/data-solusi/insert', [SolusiController::class, 'insert'], ['filter' => 'role:admin']);
$routes->post('/data-solusi/update/(:any)', [SolusiController::class, 'update/$1'], ['filter' => 'role:admin']);
$routes->get('/data-solusi/edit/(:segment)', [SolusiController::class, 'edit/$1'], ['filter' => 'role:admin']);
$routes->delete('/data-solusi/(:any)', [SolusiController::class, 'delete'], ['filter' => 'role:admin']);

//! ROUTES DIAGNOSA
$routes->post('/insert-diagnosa', [DiagnosaController::class, 'insert'], ['filter' => 'role:admin,user']);
$routes->get('/data-diagnosa', [DiagnosaController::class, 'index'], ['filter' => 'role:admin']);
$routes->get('/data-diagnosa-user', [LandingController::class, 'diagnosaUser'], ['filter' => 'role:admin,user']);

//! USER
$routes->get('/diagnosa', [LandingController::class, 'diagnosa'], ['filter' => 'role:admin,user']);
$routes->get('/input-nilai-diagnosa', [LandingController::class, 'diagnosa2'], ['filter' => 'role:admin,user']);
$routes->get('/ubah-password', [LandingController::class, 'edit'], ['filter' => 'role:admin,user']);
$routes->get('/', [LandingController::class, 'home']);
// $routes->get('/register', [LandingController::class, 'register']);
$routes->get('/info', [LandingController::class, 'info']);
$routes->get('/kontak', [LandingController::class, 'kontak']);
$routes->get('/tentang', [LandingController::class, 'tentang']);
$routes->post('/update-password', [LandingController::class, 'updatepass']);

//! ROUTES DATA KECAMATAN
$routes->get('data-kecamatan', [DashboardController::class, 'kecamatan_index']);
$routes->post('data-kecamatan/insert', [DashboardController::class, 'kecamatan_insert']);
$routes->post('data-kecamatan/update/(:any)', [DashboardController::class, 'kecamatan_update']);
$routes->delete('data-kecamatan/(:any)', [DashboardController::class, 'kecamatan_delete']);
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
