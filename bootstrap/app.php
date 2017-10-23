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
} catch (Exception $e) {
    echo $e->getMessage();
    die;
}

if (!function_exists( 'hostURL')) {
    /**
     * Returns the host url including server protocol
     *
     * @return string
     */
    function hostURL()
    {
        return serverProtocol() . $_SERVER['HTTP_HOST'];
    }
}

if (!function_exists('serverProtocol')) {
    /**
     * Returns the http protocol part of the request URL
     *
     * @return string
     */
    function serverProtocol()
    {
        if(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
            return 'https://';
        }

        if($_SERVER['SERVER_PORT'] === 443) {
            return 'https://';
        }

        return 'http://';
    }
}


if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable with default fallback.
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }

        if (strlen($value) > 1 && stringStartsWith($value, '"') && stringEndsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}


if (!function_exists('stringStartsWith')) {
    /**
     * Return boolean if the haystack has a needle as the first character
     *
     * @param $haystack
     * @param $needles
     * @return bool
     */
    function stringStartsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('stringEndsWith')) {
    /**
     * Return boolean if the haystack has a needle as the last character
     *
     * @param $haystack
     * @param $needles
     * @return bool
     */
    function stringEndsWith($haystack, $needles)
    {
        foreach ((array)$needles as $needle) {
            if (substr($haystack, -strlen($needle)) === (string)$needle) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param  mixed $value
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param $path
     * @param string $manifestDirectory
     * @return HtmlString|string
     *
     * @throws Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifests = [];
        if (! stringStartsWith($path, '/')) {
            $path = "/{$path}";
        }
        if ($manifestDirectory && ! stringStartsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            return ("//localhost:8080{$path}");
        }
        $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');
        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }
        $manifest = $manifests[$manifestPath];
        if (! isset($manifest[$path])) {
            report(new Exception("Unable to locate Mix file: {$path}."));
            if (! app('config')->get('app.debug')) {
                return $path;
            }
        }
        return new HtmlString($manifestDirectory.$manifest[$path]);
    }
}
