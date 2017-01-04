<?php

namespace Bladepress;

use Bladepress\Engines\BladeTemplateEngine;

class Engine
{
    public static function start($viewsPath, $cachePath)
    {
        new TemplateLocator(
            $viewsPath,
            '.blade.php'
        );

        add_filter('template_include', function ($path) use ($viewsPath, $cachePath) {
            $template = TemplateEngine::make($path, [
                'provider' => BladeTemplateEngine::class,
                'views_path' => $viewsPath,
                'cache_path' => $cachePath
            ]);

            return $template->make();
        });

    }
}
