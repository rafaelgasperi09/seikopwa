<?php

return [
    'name' => 'GMPCheck',
    'manifest' => [
        'name' => env('APP_NAME', 'GMPCheck'),
        'short_name' => 'GMPCheck',
        'start_url' => '/',
        'background_color' => '#e3ac13',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation'=> 'any',
        'status_bar'=> 'black',
        'icons' => [
            '48x48' => [
                'path' => '/images/AppImages/android/icon-48x48.png',
                'purpose' => 'any',
                "sizes"=> "48x48",
            ],
            '72x72' => [
                'path' => '/images/AppImages/android/icon-72x72.png',
                'purpose' => 'any',
                "sizes"=> "72x72",
            ],
            '96x96' => [
                'path' => '/images/AppImages/android/icon-96x96.png',
                'purpose' => 'any',
                "sizes"=> "96x96",
            ],
            /*'128x128' => [
                'path' => '/images/AppImages/android/icon-128x128.png',
                'purpose' => 'any',
                "sizes"=> "128x128",
            ],*/
            '144x144' => [
                'path' => '/images/AppImages/android/icon-144x144.png',
                'purpose' => 'any',
                "sizes"=> "144x144",
            ],/*
            '152x152' => [
                'path' => '/images/AppImages/icon-152x152.png',
                'purpose' => 'any'
            ],*/
            '192x192' => [
                'path' => '/images/AppImages/android/icon-192x192.png',
                'purpose' => 'any',
                "sizes"=> "192x192",
            ],
            /*'384x384' => [
                'path' => '/images/AppImages/icon-384x384.png',
                'purpose' => 'any'
            ],*/
            '512x512' => [
                'path' => '/images/AppImages/android/icon-512x512.png',
                'purpose' => 'any',
                "sizes"=> "512x512",
            ],
        ],
        'splash' => [
            '640x1136' => '/images/icons/splash-640x1136.png',
            '750x1334' => '/images/icons/splash-750x1334.png',
            '828x1792' => '/images/icons/splash-828x1792.png',
            '1125x2436' => '/images/icons/splash-1125x2436.png',
            '1242x2208' => '/images/icons/splash-1242x2208.png',
            '1242x2688' => '/images/icons/splash-1242x2688.png',
            '1536x2048' => '/images/icons/splash-1536x2048.png',
            '1668x2224' => '/images/icons/splash-1668x2224.png',
            '1668x2388' => '/images/icons/splash-1668x2388.png',
            '2048x2732' => '/images/icons/splash-2048x2732.png',
        ],
        'shortcuts' => [
            [
                'name' => 'Ingresar al app',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/login',
                'icons' => [
                    "src" => "/images/AppImages/android/icon-72x72.png",

                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Mis equipos',
                'description' => 'Ver la lista de mis equipos',
                'url' => '/equipos'
            ]
        ],
        'custom' => []
    ]
];
