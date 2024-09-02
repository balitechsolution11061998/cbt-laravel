<?php

return [
    # Menus
    'KT_MENU_MODE' => 'auto', /** 'manual' or 'auto' */

    'KT_MENUS' => [
        [
            'label'          => 'Dashboard',
            'type'           => 'item',
            'permission'     => ['dashboard-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-chart-line', // Font Awesome icon for reports
            'iconPath'       => 7,
            'route'          => 'home',
        ],

        [
            'label' => 'MENU',
            'type' => 'heading'
        ],
        [
            'label'          => 'Guru',
            'type'           => 'item',
            'route'          => 'guru.index',
            'active'         => ['guru-show'],
            'permission'     => ['guru-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-chalkboard-teacher', // Graduate Icon, representing students
            'iconPath'       => '',
        ],
        [
            'label'          => 'Siswa',
            'type'           => 'item',
            'route'          => 'siswa.index',
            'active'         => ['siswa-show'],
            'permission'     => ['siswa-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-user-graduate', // Graduate Icon, representing students
            'iconPath'       => '',
        ],
        [
            'label'          => 'Kelas',
            'type'           => 'item',
            'permission'     => ['kelas-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-chalkboard-teacher', // Chalkboard Teacher Icon
            'iconPath'       => '',
            'route'          => 'kelas.index',
        ],
        [
            'label'          => 'Mata Pelajaran',
            'type'           => 'item',
            'permission'     => ['matapelajaran-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-book-open', // Open Book Icon, representing subjects
            'iconPath'       => '',
            'route'          => 'mata-pelajaran.index',
        ],
        [
            'label'          => 'Paket Soal',
            'type'           => 'item',
            'permission'     => ['paketsoal-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-file-alt', // File Icon, representing a collection of questions
            'iconPath'       => '',
            'route'          => 'paket-soal.index',
        ],

        [
            'label'          => 'Ujian',
            'type'           => 'item',
            'permission'     => ['ujian-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-pencil-alt', // Pencil Icon, representing exams
            'iconPath'       => '',
            'route'          => 'ujian.index',
        ],
        [
            'label'          => 'Manajemen Soal',
            'type'           => 'item',
            'permission'     => ['manajementsoal-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-tasks', // Tasks Icon, representing question management
            'iconPath'       => '',
            'route'          => 'soal.index',
        ],

        [

            'label'          => 'User',
            'type'           => 'item',
            'permission'     => ['user-show'],
            'permissionType' => 'gate',
            'icon'           => 'fas',
            'iconName'       => 'fa-users',
            'iconPath'       => 3,
            'children'       => [
                [
                    'label'          => 'Permissions',
                    'type'           => 'item',
                    'route'          => 'permissions.index',
                    'active'         => ['permissions-show'],
                    'permission'     => ['permissions-show'],
                    'permissionType' => 'gate',
                    'icon'           => 'dot',
                ],
                [
                    'label'          => 'Roles',
                    'type'           => 'item',
                    'route'          => 'roles.index',
                    'active'         => ['roles-show'],
                    'permission'     => ['roles-show'],
                    'permissionType' => 'gate',
                    'icon'           => 'dot',
                ],

                [
                    'label'          => 'User',
                    'type'           => 'item',
                    'route'          => 'users.index',
                    'active'         => ['users-show'],
                    'permission'     => ['users-show'],
                    'permissionType' => 'gate',
                    'icon'           => 'dot',
                ],

            ]
        ],




    ],
];
