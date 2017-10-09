<?php
/**
 * Define path to main directory of the project as well as the web
 * accessible directory. Also define that Wordpress should display the
 * rendered theme files instead of just simply parsing the URL.
 */
define('WEBROOT', dirname(__DIR__) . '/public');
define('WP_USE_THEMES', true);

/**
 * Define your environment or default to production
 */
define('WPENV', env('WPENV', 'production'));

/**
 * Define WP Home and Site URL based on the requesting URL.
 * This allows for easier moving between local, staging, and production
 * environments without having to worry about adjusting the database.
 */
define('WP_HOME', hostURL() );
define('WP_SITEURL', hostURL() . '/app');

/**
 * Authentication Keys and Salts from ENV file. For now this has to be set
 * manually, but an automated system is being worked on.
 */
define('AUTH_KEY', env('AUTH_KEY'));
define('SECURE_AUTH_KEY', env('SECURE_AUTH_KEY'));
define('LOGGED_IN_KEY', env('LOGGED_IN_KEY'));
define('NONCE_KEY', env('NONCE_KEY'));
define('AUTH_SALT', env('AUTH_SALT'));
define('SECURE_AUTH_SALT', env('SECURE_AUTH_SALT'));
define('LOGGED_IN_SALT', env('LOGGED_IN_SALT'));
define('NONCE_SALT', env('NONCE_SALT'));

/**
 * Database settings. Collate is set to Wordpress default, and charset is
 * set to best support enhanced unicode.
 */
define('DB_NAME', env('DB_NAME', 'wordpressium'));
define('DB_USER', env('DB_USER', 'root'));
define('DB_PASSWORD', env('DB_PASSWORD', 'root'));
define('DB_HOST', env('DB_HOST', 'localhost'));
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

/**
 * Set the database table prefix, defaulting to "yoursite"
 */
$table_prefix = env('DB_PREFIX', 'ys_');

/**
 * Whether or not to allow access to the repair administration view. This
 * should be left to false, unless there is a dire need to repair an error.
 * This is set via the ENV file to avoid having to search for its initial
 * declaration.
 */
define('WP_ALLOW_REPAIR', env('ALLOW_REPAIR', false));

/**
 * A multisite network is a collection of sites that all share the same
 * WordPress installation. They can also share plugins and themes. This allows
 * you to enable or disable this via the ENV file.
 */
define('WP_ALLOW_MULTISITE', env('MULTISITE', false));


/**
 * Wordpress by default saves everything for all time. The following settings
 * tell Wordpress to handle garbage collection automatically. This limits the
 * number of post revisions saved to 5 and will automatically remove trash items
 * older than 30 days. This also enables the media library trash instead of
 * automatically deleting files, but only saves the most recent revisions of
 * an image to ensure that multiple versions aren't being saved and wasting space.
 */
define('WP_POST_REVISIONS', 5);
define('EMPTY_TRASH_DAYS', 30);
define('MEDIA_TRASH', true);
define('IMAGE_EDIT_OVERWRITE', true);

/**
 * Wordpress leaves a particularly nasty exploit by default which is the editor.
 * This disables the editor while also allowing Wordpress to complete minor
 * security updates and localization updates.
 */
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', 'minor');

/**
 * One of the primary things this Wordpress aims to solve is the file structure
 * mimicking some of Laravel's setup. This moves around Wordpress' folders to a
 * more sane structure while leaving the core files in a subfolder the way it
 * should be.
 */
define('WP_CONTENT_DIR', WEBROOT);
define('WP_CONTENT_URL', WP_HOME);
define('WPMU_PLUGIN_DIR', WEBROOT . '/plugins/required');
define('WPMU_PLUGIN_URL', WP_HOME . '/plugins');
define('UPLOADS', '../uploads');
