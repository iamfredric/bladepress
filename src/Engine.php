<?php

namespace Bladepress;

use Bladepress\Engines\BladeTemplateEngine;

class Engine
{
    public static function start($viewsPath, $cachePath, $composerRoutes = [])
    {
        new TemplateLocator(
            $viewsPath,
            '.blade.php'
        );

        add_filter('template_include', function ($path) use ($viewsPath, $cachePath, $composerRoutes) {
            $template = TemplateEngine::make($path, [
                'provider' => BladeTemplateEngine::class,
                'views_path' => $viewsPath,
                'cache_path' => $cachePath
            ], $composerRoutes);

            return $template->make();
        });

    }
}
