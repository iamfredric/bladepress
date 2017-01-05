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
    public function __construct($path, $viewsPath, $cachePath, $composerRoutes, $directives);

    /**
     * Executor of the setup
     *
     * @return string | void
     */
    public function make();

    /**
     * Sets the data on the instance
     *
     * @param $data
     */
    public function setData($data);

    /**
     * Gets the data on the instance
     *
     * @return TemplateData
     */
    public function getData();
}
