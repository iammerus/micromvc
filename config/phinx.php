<?php

require "../system/bootstrap.php";
return [
    'paths' => [
        'migrations' => userDir() . "/database/Migrations",
        'seeds' => userDir() . "/database/Seeds",
    ],
    'migration_base_class' => "\\ECensus\\Core\\Migration",
    'environments' => [
        'default_migration_table' => 'ecensus_migrations',
        'default_database' => 'dev',
        'dev' => [
            'adapter' => 'mysql',
            'host' => getenv("DB_HOST"),
            'name' => getenv("DB_NAME"),
            'user' => getenv("DB_USER"),
            'pass' => getenv("DB_PASS"),
            'port' => 3306
        ]
    ]
];
