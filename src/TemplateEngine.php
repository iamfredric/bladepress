<?php

namespace Bladepress;

use Bladepress\Contracts\Templating;

class TemplateEngine
{
    /**
     * Initialize template engine
     *
     * @param  string $path
     * @param  array  $config
     *
     * @return \App\Contracts\Wordpress\Templating\Templating
     */
    public static function make($path, array $config = [], $composerRoutes = [], $directives = [], $shared = [])
    {
        $viewsPath = self::getTemplateViewsPath($config);
        $cachePath = self::getTemplateCachePath($config);

        return self::initializeTemplateProvider($config, $path, $viewsPath, $cachePath, $composerRoutes, $directives, $shared);
    }

    /**
     * News up the template provider
     *
     * @param  array $config
     * @param  string $path
     * @param  string $viewsPath
     * @param  string $cachePath
     *
     * @return \App\Contracts\Wordpress\Templating\Templating
     * @throws \Exception
     */
    public static function initializeTemplateProvider($config, $path, $viewsPath, $cachePath, $composerRoutes, $directives, $shared)
    {
        if (! isset($config['provider'])) {
            throw new \Exception(
                "No templating provider was provided"
            );
        }

        $class = new $config['provider']($path, $viewsPath, $cachePath, $composerRoutes, $directives, $shared);

        if (! $class instanceof Templating) {
            throw new \Exception(
                "Templating provider must implement Bladepress\\Contracts\\Templating"
            );
        }

        return $class;
    }

    /**
     * Gets the template view
     *
     * @param  array $config
     *
     * @return string
     * @throws \Exception
     */
    public static function getTemplateViewsPath($config)
    {
        if (! isset($config['views_path'])) {
            throw new \Exception(
                "No views path was provided"
            );
        }

        return $config['views_path'];
    }

    /**
     * Gets the cache path
     *
     * @param  array $config
     *
     * @return string
     * @throws \Exception
     */
    public static function getTemplateCachePath($config)
    {
        if (! isset($config['cache_path'])) {
            throw new \Exception(
                "No cache path was provided"
            );
        }

        return $config['cache_path'];
    }
}
