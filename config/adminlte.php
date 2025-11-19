<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => '',
    'title_prefix' => 'Sistema | ',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => null,
    'logo_img' => 'vendor/adminlte\dist/img/Logos/LOGO-2.png',
    'logo_img_class' => 'brand-image elevation-9',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AICO',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/Logos/Logo_AICO.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration. Currently, two
    | modes are supported: 'fullscreen' for a fullscreen preloader animation
    | and 'cwrapper' to attach the preloader animation into the content-wrapper
    | element and avoid overlapping it with the sidebars and the top navbar.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/Logos/Logo_AICO.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 220,
            'height' => 180,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-navy',
    'usermenu_image' => true,
    'usermenu_desc' => true,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => true,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => 'bg-white',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */
    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => false,
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
                [
                    'type' => 'navbar-notification', // Indica que este elemento es un ícono de notificaciones en la barra superior.
                    'id' => 'my-notification',       // ID único para identificar este componente (requerido para actualizarlo vía JS/AJAX).
                    'icon' => 'fa fa-bell',          // Icono FontAwesome que se mostrará como campana.
                    'icon_color' => 'danger',        // Color inicial del icono (rojo = danger). Opcional.
                    'label' => 0,                    // Número inicial que aparece como contador (badge). Puede empezar en 0.
                    'label_color' => '',            // Color de la etiqueta del contador (badge). Ej: 'warning', 'info', 'success'.
                    'url' => 'notificacion/index',   // Ruta a donde te lleva al hacer clic en "Ver todas".
                    'topnav_right' => true,          // Lo coloca en la parte derecha del top navbar (si fuera "topnav" va del lado izquierdo).
                    'dropdown_mode' => true,         // Activa el modo desplegable con lista de notificaciones.
                    'dropdown_flabel' => 'Todas las notificaciones', // Texto del enlace al final del dropdown (footer).
                    /*'update_cfg' => [
                        'url' => 'notificaciones/update', // Ruta que AdminLTE usará para hacer AJAX y actualizar datos.
                        'period' => 60,                   // Cada cuántos segundos consultar nuevas notificaciones.
                    ],*/
                ],
                // Sidebar Administrativo:
                /*[
                    'text' => 'REPORTES',
                    'icon' => 'fas fa-file-alt',
                    'can' => 'administrador-access',  // Define una política en Laravel para controlar el acceso
                    //'topnav' => true,
                    'submenu' => [
                        [
                            'text' => 'FOR-INS-02/02',
                            'url' => '/Reporte/FOR-INS-02/02/PDF/183',
                            //can => 'nombre-ruta'
                        ],
                        [
                            'text' => 'FOR-INS-03/01',
                            'url' => '/Reporte/FOR-INS-03/01/PDF',
                            //can => 'nombre-ruta'
                        ],
                        [
                            'text' => 'FOR-INS-04/01',
                            'url' => '/Reporte/FOR-INS-04/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-05/01',
                            'url' => '/Reporte/FOR-INS-05/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-05/02',
                            'url' => '/Reporte/FOR-INS-05/02/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-06/01',
                            'url' => '/Reporte/FOR-INS-06/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-07/01',
                            'url' => '/Reporte/FOR-INS-07/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-08/01',
                            'url' => '/Reporte/FOR-INS-08/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-09/01',
                            'url' => '/Reporte/FOR-INS-09/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-10/01',
                            'url' => '/Reporte/FOR-INS-10/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-11/01',
                            'url' => '/Reporte/FOR-INS-11/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-11/02',
                            'url' => '/Reporte/FOR-INS-11/02/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-13/01',
                            'url' => '/Reporte/FOR-INS-13/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-14/01',
                            'url' => '/Reporte/FOR-INS-14/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-16/01',
                            'url' => '/Reporte/FOR-INS-16/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-16/02',
                            'url' => '/Reporte/FOR-INS-16/02/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-16/03',
                            'url' => '/Reporte/FOR-INS-16/03/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-17/01',
                            'url' => '/Reporte/FOR-INS-17/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-17_01/01',
                            'url' => '/Reporte/FOR-INS-17_01/01/PDF',
                        ],
                        [
                            'text' => 'FOR-INS-18/01',
                            'url' => '/Reporte/FOR-INS-18/01/PDF',
                        ],
                    ],
                ],*/
        
        // Sidebar Administrativo:
        /*[
            'text' => 'Planeacion',
            'icon' => 'fas fa-calendar-alt',
            'can' => 'administrador-access',  // Define una política en Laravel para controlar el acceso
            //'topnav' => true,
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url' => '#',
                    //can => 'nombre-ruta'
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                    'submenu' => 
                    [
                        [
                            'text' => 'level_two',
                            'url' => '#',
                        ],
                        [
                            'text' => 'level_two',
                            'url' => '#',
                            'submenu' => 
                            [
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                            ],
                        ],
                    ],
                ],

                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
            ],
        ],*/

            [
                'text' => 'REPORTE',
                'icon' => 'fas fa-clipboard',
                'can' => 'administrador-access',
                //'topnav' => true,
                'submenu' => [
                        [
                            'text' => 'FOR-02-PRO-INS-02',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_02_PRO_INS_02',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-03',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_03',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-04',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_04',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-02-PRO-INS-04',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_02_PRO_INS_04',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-05',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_05',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-06',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_06',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-07',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_07',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-08',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_08',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-09',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_09',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-10',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_10',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-12',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_12',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-13',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_13',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-15',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_15',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-02-PRO-INS-15',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_02_PRO_INS_15',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-03-PRO-INS-15',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_03_PRO_INS_15',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-16',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_16',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-17',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_17',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'FOR-01-PRO-INS-18',
                            'icon' => 'fa fa-file',
                            'url' => 'Reporte/FOR_01_PRO_INS_18',
                            'can' => 'equipos-access',
                        ],
                    ],
            ],

               // Sidebar Operativos:
                [
                'text' => 'Operativos',
                'icon' => 'fas fa-clipboard',
                'can' => 'tecnicos-access',
                //'topnav' => true,
                'submenu' => [
                    [
                        'text' => 'Orden de Trabajo/Servicio',
                        'icon' => 'far fa-file-alt',
                        'url' => '/Page_In_Construction',
                    ],
                    [
                        'text' => 'Crear Reportes',
                        'icon' => 'far fa-file',
                        'url' => '/Menu/Servicios',
                    ],
                    [
                        'text' => 'Reportes',
                        'icon' => 'fab fa-wpforms',

                        'url' => '/index/ContratoProyecto',
                    ],
                    [
                        'text' => 'Registro de Pruebas',
                        'icon' => 'fas fa-indent',
                        'url' => '/Pruebas/Create',
                    ],
                    [
                        'text' => 'Pruebas',
                        'icon' => 'fas fa-table',
                        'url' => '/index/Pruebas',

                    ],
                ],
            ],


                // Sidebar Clientes:
                [
                    'text' => 'Ventas',
                    'icon' => 'fas fa-money-bill-wave',
                    'can' => 'ventas-access',
                    //'topnav' => true,
                    'submenu' => [
                        [
                            'text' => 'Registro OC',
                            'icon' => 'fas fa-chart-line',
                            'url' => '/OC/createOC',
                        ],
                        [
                            'text' => 'Ordenes de Compras',
                            'icon' => 'fas fa-clipboard-list',
                            'url' => '/OC/indexOC',
                        ],
                        
                    ],
                ],

                // Sidebar Equipos y Consumibles:
                [
                    'text' => 'Equipos',
                    'icon' => 'fas fa-pencil-ruler',
                    'can' => 'tecnicos-equipos-lab-access',
                    //'topnav' => true,
                    'submenu' => [
                        [
                            'text' => 'Inventario',
                            'icon' => 'far fa-clipboard',
                            'url' => 'inventario',
                            'can' => 'equipos-lab-access',
                        ],
                        [
                            'text' => ' Certificados',
                            'icon' => 'fa fa-certificate',
                            'url' => 'Historial_certificados/index',
                            'can' => 'equipos-lab-access',
                        ],
                        [
                            'text' => 'Clientes',
                            'icon' => 'fas fa-users',
                            'url' => 'clientes/index',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'Registro Clientes',
                            'icon' => 'fas fa-user-plus',
                            'url' => 'registro/create',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => 'Registro de altas',
                            'icon' => 'fas fa-edit',
                            'url' => 'registros/createEyC',
                            'can' => 'equipos-lab-access',
                        ],
                        [
                            'text' => 'Solicitar E y C',
                            'icon' => 'far fa-file-alt',
                            'url' => 'solicitud/create',
                            'can' => 'tecnicos-equipos-access',
                        ],
                        [
                            'text' => ' Ver E/S/D',
                            'icon' => 'fas fa-exchange-alt',
                            'url' => 'Historial_Almacen/index',
                            'can' => 'equipos-lab-access',
                        ],
                        [
                            'text' => ' Ver Kits',
                            'icon' => 'fas fa-box',
                            'url' => 'index/Kits',
                            'can' => 'equipos-access',
                        ],
                        [
                            'text' => ' Ver Solicitudes',
                            'icon' => 'far fa-clipboard',
                            'url' => 'solicitud/index',
                            'can' => 'tecnicos-equipos-access',
                        ],
                        
                    ],
                ],

                 // Sidebar Administrativo:
                [
                    'text' => 'Admin',
                    'icon' => 'fas fa-universal-access',
                    'can' => 'administrador-access',
                    //'topnav' => true,
                    'submenu' => [
                        [
                            'text' => 'Ver usuarios',
                            'icon' => 'fas fa-users',
                            'url' => 'Admin/index',
                        ],
                        [
                            'text' => 'Registro de usuarios',
                            'icon' => 'fas fa-user-plus',
                            'url' => 'Admin/create',
                        ],
                        
                    ],
                ],
                [
                    'text' => 'Solicitudes AD',
                    'icon' => 'fas fa-file-alt',
                    'can' => 'tecnicos-equipos-access',  // Define una política en Laravel para controlar el acceso
                    //'topnav' => true,
                    'submenu' => [
                        [
                            'text' => 'Solicitar',
                            'url' => '/ADsolicitud/create',
                            //can => 'nombre-ruta'
                        ],
                        [
                            'text' => 'Ver solicitudes',
                            'url' => '/ADsolicitud/index',
                            //can => 'nombre-ruta'
                        ],
                    ],
                ],

    ],//Final Menu

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'BootstrapSwitch' => [
            'active' => true, // Activa el plugin
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'onColor' => 'warning',
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap3/bootstrap-switch.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
