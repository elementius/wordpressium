<?php
/**
 * Define path to main directory of the project as well as the web
 * accessible directory. Also define that Wordpress should display the
 * rendered theme files instead of just simply parsing the URL.
 */
define('WEBROOT', ROOTPATH . '/public');
define('WP_USE_THEMES', true);

/** Define your environment or default to production */
define('WPENV', env('WPENV', 'production'));

/**
 * Define WP Home and Site URL based on the requesting URL.
 * This allows for easier moving between local, staging, and production
 * environments without having to worry about adjusting the database.
 */
define('WP_HOME', serverProtocol() . $_SERVER['HTTP_HOST']);
define('WP_SITEURL', serverProtocol() . $_SERVER['HTTP_HOST'] . '/app');
