<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Permissions
     |--------------------------------------------------------------------------
     */

    /**
     *  Structure: [
     *      'guard' => [
     *          'permission_group' => [
     *              'single_permission' => ['role_1', 'role_2'],
     *          ],
     *      ],
     *  ]
     *  Example: [
     *      'web' => [
     *          'users' => [
     *              'create' => ['admin', 'supervisor'],
     *          ],
     *      ],
     *  ]
     */
    'permissions_seeder' => [
        'admin' => [
            'settings' => [
                'menu' => ['admin', 'supervisor'],
                'permission_list' => ['admin', 'supervisor'],
                'settings' => ['admin'],
                'testing' => ['admin'],
                'status' => ['admin'],
            ],
            'admins' => [
                'create' => ['admin'],
                'update' => ['admin'],
                'delete' => ['admin'],
                'view' => ['admin'],
                'restore' => ['admin'],
            ],
            'users' => [
                'create' => ['admin', 'supervisor'],
                'update' => ['admin', 'supervisor'],
                'delete' => ['admin'],
                'view' => ['admin', 'supervisor'],
                'restore' => ['admin'],
                'password' => ['admin', 'supervisor'],
            ],
        ],
        'web' => [
            'user_settings' => [
                'view' => ['customer'],
            ],
            'pages' => [
                'view' => ['customer'],
            ],
            'users' => [
                'profile' => ['customer'],
                'password' => ['customer'],
            ],
        ],
    ],

    /**
     * Mapping ['permission_name' => 'permission_readable_name']
     */
    'permission_readable_names' => [
        'settings.status' => 'Stato sistema',
    ],

    /*
     |--------------------------------------------------------------------------
     | Roles
     |--------------------------------------------------------------------------
     */
    /**
     * Use this array only for role without permission
     *
     *  Structure: [
     *      'guard' => [
     *          'role_name',
     *      ],
     *  ]
     *  Example: [
     *      'web' => [
     *          'admin',
     *      ],
     *  ]
     */
    'roles_seeder' => [
        'admin' => [
            'api',
        ],
    ],

    /**
     * Mapping ['role_name' => 'role_readable_name']
     */
    'role_readable_names' => [
        'admin' => 'Amministratore',
        'supervisor' => 'Supervisore',
        'customer' => 'Cliente',
        'api' => 'API',
    ],

    /*
     |--------------------------------------------------------------------------
     | Crud setting
     |--------------------------------------------------------------------------
     */
    'crud' => [
        'method' => 'livewire', // or 'datatable'
        'has_show' => false
    ],

    /*
     |--------------------------------------------------------------------------
     | Crud entity
     |--------------------------------------------------------------------------
     */
    'crud_entity' => [
        // \Ades4827\Sprintflow\Models\Admin::class => \App\Http\Controllers\Admin\AdminController::class,
        // \Ades4827\Sprintflow\Models\User::class => \App\Http\Controllers\Admin\UserController::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Media utils (to be used with Spatie laravel-medialibrary)
     |--------------------------------------------------------------------------
     */
    'medias' => [
        'inject_route' => false,
        'file_access_controller' => \Ades4827\Sprintflow\Controllers\FileAccessController::class,
        'find_method' => 'uuid', // id or uuid
    ]

];
