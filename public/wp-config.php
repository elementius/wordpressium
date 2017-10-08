<?php
/**
 * This file is required in the root of the public folder. Wordpress looks at the root of its folder,
 * or one folder above for a wp-config file. This file will include any composer packages for
 * leveraging autoloading as well as setting up the configuration and environment for wordpress.
 */

require_once(dirname(__DIR__) . '/vendor/autoload.php');
require_once(dirname(__DIR__) . '/bootstrap/config.php');
require_once(dirname(__DIR__) . '/config/app.php');
require_once(ABSPATH . 'wp-settings.php');