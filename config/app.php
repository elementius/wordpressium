<?php
/**
 * Define path to main directory of the project as well as the web
 * accessible directory. Also define that Wordpress should display the
 * rendered theme files instead of just simply parsing the URL.
 */
define('ROOTPATH', dirname(__DIR__));
define('WEBROOT', ROOTPATH . '/public');
define('WP_USE_THEMES', true);


/**
 * Throw exception if you are missing your .env file
 */
if (!file_exists(ROOTPATH . '/.env')) {
    echo 'You are missing an environment file.';
    throw new exception('You are missing an environment file.');
}