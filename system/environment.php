<?php
    function registerEnvironmentVariables ()
    {
        /** Register application root directory */
        $rootDir = dirname(__DIR__);
        putenv("APP_ROOT=$rootDir");
        /** ------- -- -- -- -- -- -- -- ------ */

        $configDir = $rootDir . "/config";
        putenv("CONFIG_DIR=$configDir");

        $assetsDir = $rootDir . "/public/assets";
        putenv("ASSETS_DIR=$assetsDir");

        $avatarDir = $assetsDir . "/images/uploads/avatars";
        putenv("AVATAR_DIR=$avatarDir");

        $userDir = $rootDir . "/app";
        putenv("USER_DIR=$userDir");

        $controllerDir = $userDir . "/Controllers";
        putenv("CONTROLLERS_DIR=$controllerDir");

        $viewsDir = $userDir . "/Views";
        putenv("VIEW_DIR=$viewsDir");

        $modelsDir = $userDir . "/Models";
        putenv("MODELS_DIR=$modelsDir");

        $migrationsDir = $userDir . "/Database/Migrations";
        putenv("MIGRATIONS_DIR=$migrationsDir");

        $seedsDir = $userDir . "/Database/Seeds";
        putenv("SEEDS_DIR=$seedsDir");

        $config = parse_ini_file($userDir . "/config.ini");
        putenv("DB_HOST={$config['host']}");
        putenv("DB_USER={$config['username']}");
        putenv("DB_PASS={$config['password']}");
        putenv("DB_NAME={$config['name']}");

        putenv("APP_MODE={$config['status']}");
        putenv("APP_ERRORS={$config['errors']}");
        putenv("CONFIG_TABLE={$config['config_table']}");

        putenv("SITE_URL={$config['siteurl']}");
        putenv("SESSION_PREFIX={$config['sessionprefix']}");

    };

