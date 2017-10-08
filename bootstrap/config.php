<?php
/**
 * Temporary place for these until they can be served up properly with autoloading
 */

use Dotenv\Dotenv;

define('ROOTPATH', dirname(__DIR__));

/**
 * Throw exception if you are missing your .env file
 */
if (!file_exists(ROOTPATH . '/.env')) {
    echo 'You are missing an environment file.';
    die;
}

$env = new Dotenv(ROOTPATH);
try {
    $env->load();
    $env->required(['DB_NAME', 'DB_USER', 'DB_PASSWORD']);
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}