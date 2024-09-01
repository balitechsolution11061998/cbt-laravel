<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('home'));
});

Breadcrumbs::for('order', function (BreadcrumbTrail $trail) {
    $trail->push('Order', route('po.index'));
});

Breadcrumbs::for('permissions', function (BreadcrumbTrail $trail) {
    $trail->push('Permissions', route('permissions.index'));
});

Breadcrumbs::for('roles', function (BreadcrumbTrail $trail) {
    $trail->push('Roles', route('roles.index'));
});

Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->push('User', route('users.index'));
});


Breadcrumbs::for('items', function (BreadcrumbTrail $trail) {
    $trail->push('Items', route('items.index'));
});

Breadcrumbs::for('price-change', function (BreadcrumbTrail $trail) {
    $trail->push('PriceChange', route('price-change.index'));
});

Breadcrumbs::for('settings', function (BreadcrumbTrail $trail) {
    $trail->push('Settings', route('settings.priceChange.index'));
});

Breadcrumbs::for('jam-kerja', function (BreadcrumbTrail $trail) {
    $trail->push('Jam Kerja', route('jam_kerja.index'));
});

Breadcrumbs::for('departments', function (BreadcrumbTrail $trail) {
    $trail->push('Department', route('departments.index'));
});

Breadcrumbs::for('kantor-cabang', function (BreadcrumbTrail $trail) {
    $trail->push('Kantor Cabang', route('kantor_cabang.index'));
});

Breadcrumbs::for('cuti', function (BreadcrumbTrail $trail) {
    $trail->push('Cuti', route('cuti.index'));
});

Breadcrumbs::for('paketsoal', function (BreadcrumbTrail $trail) {
    $trail->push('Paket Soal', route('paket-soal.index'));
});

Breadcrumbs::for('kelas', function (BreadcrumbTrail $trail) {
    $trail->push('Kelas', route('kelas.index'));
});

Breadcrumbs::for('rombel', function (BreadcrumbTrail $trail) {
    $trail->push('Rombongan Belajar', route('rombel.index'));
});

Breadcrumbs::for('siswa', function (BreadcrumbTrail $trail) {
    $trail->push('Siswa', route('siswa.index'));
});

Breadcrumbs::for('mata-pelajaran', function (BreadcrumbTrail $trail) {
    $trail->push('Mata Pelajaran', route('mata-pelajaran.index'));
});

Breadcrumbs::for('manajementsoal', function (BreadcrumbTrail $trail) {
    $trail->push('Management Soal', route('soal.index'));
});

Breadcrumbs::for('ujian', function (BreadcrumbTrail $trail) {
    $trail->push('Ujian', route('ujian.index'));
});

Breadcrumbs::for('manajementguru', function (BreadcrumbTrail $trail) {
    $trail->push('Management Guru', route('guru.index'));
});

