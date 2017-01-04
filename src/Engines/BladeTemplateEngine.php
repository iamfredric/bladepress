<?php

namespace Bladepress\Engines;

use Bladepress\Contracts\Templating;
use duncan3dc\Laravel\BladeInstance;

class BladeTemplateEngine implements Templating
{
    /**
     * The requested path
     *
     * @var string
     */
    protected $path;

    /**
     * The path to the views directory
     * @var string
     */
    protected $viewsPath;

    /**
     * The path to the cache directory
     *
     * @var string
     */
    protected $cachePath;

    /**
     * @var
     */
    private $data = [];

    /**
     * BladeTemplateEngine constructor
     *
     * @param string $path      The requested path
     * @param string $viewsPath The path to the views directory
     * @param string $cachePath The path to the cache directory
     */
    public function __construct($path, $viewsPath, $cachePath)
    {
        $this->path = $path;
        $this->viewsPath = $viewsPath;
        $this->cachePath = $cachePath;
    }

    /**
     * The method that executes
     *
     * @return string | void
     */
    public function make()
    {
        if (! $this->isBladeFile()) {
            return $this->path;
        }

        $path = $this->getParsedPath();

        $blade = $this->setUpBlade();

        $this->setupComposers($blade);

        echo $blade->render(
            $path,
            $this->getData()
        );
    }

    /**
     * View data setter
     *
     * @param $data
     */
    public function setData($data)
    {
        $this->data = $data->get();
    }

    private function setupComposers(BladeInstance $blade)
    {
        foreach (config('routes') as $route => $class) {
            $blade->composer($route, function ($view) use ($class) {
                return (new $class)->compose($view);
            });
        }
    }

    /**
     * View data getter
     *
     * @return TemplateData
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Checks if current path is a blade file
     *
     * @return boolean
     */
    protected function isBladeFile()
    {
        return preg_match("/\.blade\.php/", $this->path);
    }

    /**
     * Parses the given path to point to the views path
     *
     * @return string
     */
    protected function getParsedPath()
    {
        return trim(
            str_replace([$this->viewsPath, '.blade.php'], '', $this->path),
            '/'
        );
    }

    /**
     * Initialize blade
     *
     * @return \duncan3dc\Laravel\BladeInstance
     */
    protected function setUpBlade()
    {
        return new BladeInstance(
            $this->viewsPath,
            $this->cachePath
        );
    }
}
