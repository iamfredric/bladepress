<?php

namespace Bladepress;

use Bladepress\Engines\BladeTemplateEngine;

class Engine
{
    public static function start($viewsPath, $cachePath, $composerRoutes = [], $directives = [], $shared = [])
    {
        new TemplateLocator(
            $viewsPath,
            '.blade.php'
        );

        add_filter('template_include', function ($path) use ($viewsPath, $cachePath, $composerRoutes, $directives, $shared) {
            $template = TemplateEngine::make($path, [
                'provider' => BladeTemplateEngine::class,
                'views_path' => $viewsPath,
                'cache_path' => $cachePath
            ], $composerRoutes, $directives, $shared);

            return $template->make();
        });

    }
}
