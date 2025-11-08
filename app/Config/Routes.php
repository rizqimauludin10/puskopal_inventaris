<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// Rute dasar ('/') akan menampilkan halaman login (Auth::index)
$routes->get('/', 'Auth::index'); 
$routes->get('login', 'Auth::index'); // Alias ke halaman login
$routes->post('auth/attemptLogin', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');


// =========================================================================
// 2. RUTE APLIKASI UTAMA (DILINDUNGI OLEH FILTER 'auth')
// Semua rute di sini hanya dapat diakses jika pengguna sudah login.
// =========================================================================

$routes->group('', ['filter' => 'auth'], function($routes) {

    // --- DASHBOARD ---
    // Setelah login berhasil, user diarahkan ke sini
    $routes->get('dashboard', 'Dashboard::index'); 

    // --- MANAJEMEN ASET KENDARAAN ---
    $routes->get('asetkendaraan', 'AsetKendaraan::index');
    $routes->get('asetkendaraan/create', 'AsetKendaraan::create');
    $routes->post('asetkendaraan/store', 'AsetKendaraan::store');
    $routes->get('asetkendaraan/detail/(:num)', 'AsetKendaraan::show/$1');
    $routes->get('asetkendaraan/edit/(:num)', 'AsetKendaraan::edit/$1');
    $routes->post('asetkendaraan/update/(:num)', 'AsetKendaraan::update/$1');
    $routes->get('asetkendaraan/delete/(:num)', 'AsetKendaraan::delete/$1');
    $routes->get('asetkendaraan/exportExcel', 'AsetKendaraan::exportExcel');

    // --- MANAJEMEN ASET TANAH & BANGUNAN ---
    $routes->get('asettanahbangunan', 'AsetTanahBangunan::index');
    $routes->get('asettanahbangunan/create', 'AsetTanahBangunan::create');
    $routes->post('asettanahbangunan/store', 'AsetTanahBangunan::store');
    $routes->get('asettanahbangunan/detail/(:num)', 'AsetTanahBangunan::show/$1');
    $routes->get('asettanahbangunan/edit/(:num)', 'AsetTanahBangunan::edit/$1');
    $routes->post('asettanahbangunan/update/(:num)', 'AsetTanahBangunan::update/$1');
    $routes->get('asettanahbangunan/delete/(:num)', 'AsetTanahBangunan::delete/$1');
    $routes->get('asettanahbangunan/exportExcel', 'AsetTanahBangunan::exportExcel');

    // --- MANAJEMEN PENGGUNA (USERS) ---
    $routes->get('users', 'User::index');
    $routes->get('users/create', 'User::create');
    $routes->post('users/store', 'User::store');
    $routes->get('users/edit/(:num)', 'User::edit/$1');
    $routes->post('users/update/(:num)', 'User::update/$1');
    $routes->get('users/delete/(:num)', 'User::delete/$1');

    // --- MANAJEMEN PERSONEL (PERSONEL) ---
    $routes->get('personel', 'Personel::index');
    $routes->get('personel/create', 'Personel::create');
    $routes->post('personel/store', 'Personel::store');
    $routes->get('personel/edit/(:num)', 'Personel::edit/$1');
    $routes->post('personel/update/(:num)', 'Personel::update/$1');
    $routes->get('personel/delete/(:num)', 'Personel::delete/$1');
    $routes->get('personel/getJabatanByPenempatan/(:num)', 'Personel::getJabatanByPenempatan/$1');


    // --- MANAJEMEN MITRA KERJA ---
    $routes->get('mitra', 'MitraKerja::index');
    $routes->get('mitra/create', 'MitraKerja::create');
    $routes->post('mitra/store', 'MitraKerja::store');
    $routes->get('mitra/edit/(:num)', 'MitraKerja::edit/$1');
    $routes->post('mitra/update/(:num)', 'MitraKerja::update/$1');
    $routes->get('mitra/delete/(:num)', 'MitraKerja::delete/$1');
    $routes->get('mitra/detail/(:num)', 'MitraKerja::detail/$1');

    // --- MANAJEMEN PERJANJIAN KERJASAMA (PKS) ---
    $routes->get('pks', 'Pks::index');
    $routes->get('pks/create/(:num)', 'Pks::create/$1');
    $routes->post('pks/store', 'Pks::store');
    $routes->get('pks/edit/(:num)', 'Pks::edit/$1');
    $routes->post('pks/update/(:num)', 'Pks::update/$1');
    $routes->get('pks/delete/(:num)', 'Pks::delete/$1');
    $routes->get('pks/detail/(:num)', 'Pks::detail/$1');
    
    // --- Rute Khusus Admin/Pengurus (Placeholder) ---
    // Karena filter role spesifik belum dibuat, kita masukkan ke grup 'auth' umum.
    $routes->get('admin', 'Admin::index');
    $routes->get('pengurus', 'Pengurus::index');
});