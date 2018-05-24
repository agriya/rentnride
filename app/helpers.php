<?php
/**
 * APP
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    RENT&RIDE
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
 
/**
 * Because Lumen has no config_path function, we need to add this function
 * to make JWT Auth works.
 */
if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}
if (!function_exists('public_path')) {

    /**
     * Return the path to public dir
     * @param null $path
     * @return string
     */
    function public_path($path = null)
    {
        return rtrim(app()->basePath('public/' . $path), '/');
    }
}

if (!function_exists('storage_path')) {

    /**
     * Return the path to storage dir
     * @param null $path
     * @return string
     */
    function storage_path($path = null)
    {
        return app()->storagePath($path);
    }
}

if (!function_exists('database_path')) {

    /**
     * Return the path to database dir
     * @param null $path
     * @return string
     */
    function database_path($path = null)
    {
        return app()->databasePath($path);
    }
}

if (!function_exists('resource_path')) {

    /**
     * Return the path to resource dir
     * @param null $path
     * @return string
     */
    function resource_path($path = null)
    {
        return app()->resourcePath($path);
    }
}


if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     * @param  string $path
     * @param  bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('elixir')) {
    /**
     * Get the path to a versioned Elixir file.
     * @param  string $file
     * @return string
     */
    function elixir($file)
    {
        static $manifest = null;
        if (is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path() . '/build/rev-manifest.json'), true);
        }
        if (isset($manifest[$file])) {
            return '/build/' . $manifest[$file];
        }
        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}
if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     * @param  string $value
     * @param  array $options
     * @return string
     */
    function bcrypt($value, $options = array())
    {
        return app('hash')->make($value, $options);
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     * @param  string|null $to
     * @param  int $status
     * @param  array $headers
     * @param  bool $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = array(), $secure = null)
    {
        if (is_null($to)) return app('redirect');
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     * @param  string $content
     * @param  int $status
     * @param  array $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = array())
    {
        $factory = app('Illuminate\Contracts\Routing\ResponseFactory');
        if (func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($content, $status, $headers);
    }
}

if (!function_exists('secure_asset')) {
    /**
     * Generate an asset path for the application.
     * @param  string $path
     * @return string
     */
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     * @param  string $path
     * @param  mixed $parameters
     * @return string
     */
    function secure_url($path, $parameters = array())
    {
        return url($path, $parameters, true);
    }
}


if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     * If an array is passed as the key, we will assume you want to set an array of values.
     * @param  array|string $key
     * @param  mixed $default
     * @return mixed
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) return app('session');
        if (is_array($key)) return app('session')->put($key);
        return app('session')->get($key, $default);
    }
}


if (!function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     * @param  string $name
     * @param  string $value
     * @param  int $minutes
     * @param  string $path
     * @param  string $domain
     * @param  bool $secure
     * @param  bool $httpOnly
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        $cookie = app('Illuminate\Contracts\Cookie\Factory');
        if (is_null($name)) {
            return $cookie;
        }
        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }
}

if (!function_exists('enabled_plugins')) {
    /**
     * @return array
     */
    function enabled_plugins()
    {
        $enabled_plugins = explode(',', trim(config('site.enabled_plugins')));
        $enabled_plugins = array_map('trim', $enabled_plugins);
        return $enabled_plugins;
    }
}

if (!function_exists('isPluginEnabled')) {
    /**
     * check the given plugin is enabled or not
     * @param $plugin_name
     * @return bool
     */
    function isPluginEnabled($plugin_name)
    {
        if (in_array($plugin_name, enabled_plugins())) {
            return true;
        } else {
            return false;
        }
    }
}
