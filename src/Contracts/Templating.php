<?php

namespace Bladepress\Contracts;

interface Templating
{
    /**
     * Templating constructor
     *
     * @param string $path
     * @param string $viewsPath
     * @param string $cachePath
     */
    public function __construct($path, $viewsPath, $cachePath, $composerRoutes, $directives, $shared);

    /**
     * Executor of the setup
     *
     * @return string | void
     */
    public function make();
}
