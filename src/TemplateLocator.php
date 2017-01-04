<?php

namespace Bladepress;

class TemplateLocator
{
    /**
     * The path to the views directory
     *
     * @var string
     */
    protected $viewsPath;

    /**
     * The file extension to be served
     *
     * @var string
     */
    protected $extension;

    /**
     * TemplateLocator constructor
     *
     * @param string $viewsPath The path to the views directory
     * @param string $extension The file extension to be served
     */
    public function __construct($viewsPath, $extension = '.php')
    {
        $this->viewsPath = $viewsPath;
        $this->extension = $extension;

        $templates = $this->getRoutes();

        foreach ($templates as $type => $template) {
            $this->addFilterFor($type, $template);
        }
    }

    /**
     * Adds a filter for all the templates so that
     * wordpress would be able to locate the
     * correnct template in the given path
     *
     * @param string $type
     * @param array  $templates
     *
     * @return  void
     */
    private function addFilterFor($type, $templates = [])
    {
        add_filter("{$type}_template", function ($template, $test = null) use ($type, $templates) {

            if (is_page_template() and preg_match('/\.blade\.php/', $template)) {
                return $template;
            }

            return $this->getQueryTemplate($type, $templates) ?: $template;
        });
    }

    /**
     * Fetches the highest ranked template and
     * returns the path to the template
     *
     * @param  string $type
     * @param  array  $templates
     *
     * @return string
     */
    private function getQueryTemplate($type, $templates = [])
    {
        // Removes everything but letters,
        // numbers and dashes
        $type = preg_replace('|[^a-z0-9-]+|', '', $type);

        $parsedTemplates = [];

        if ($object = get_queried_object()) {
            // Loops through the given templates
            // and replaces the placeholders
            // with the correct strings
            foreach ($templates as $template) {
                $parsedTemplates[] = $this->parseTemplateName($template, $object);
            }
        } else {
            $parsedTemplates = $templates;
        }

        if (empty($parsedTemplates)) {
            $parsedTemplates = ["{$type}.php"];
        }

        $template = $this->locateTemplate($parsedTemplates);

        return $template;
    }

    /**
     * Loops throught the given templates and returns
     * the path to the first one that exists
     *
     * @param  array  $templates
     * @param  boolean $load
     * @param  boolean $require_once
     *
     * @return string
     */
    private function locateTemplate($templates, $load = false, $require_once = true)
    {
        foreach ($templates as $template) {
            if (file_exists(
                $this->viewsPath . '/' . str_replace('.php', $this->extension, $template)
            )) {
                return $this->viewsPath . '/' . str_replace('.php', $this->extension, $template);
            }

            if (file_exists(
                $this->viewsPath . '/' . $template
            )) {
                return $this->viewsPath . '/' . $template;
            }
        }

        return locate_template($templates, $load = false, $require_once = true);
    }

    /**
     * Replaces template placeholders with actual value
     *
     * @param  string $template
     * @param  Object $object
     *
     * @return string
     */
    private function parseTemplateName($template, $object)
    {
        foreach ([
                'POST_TYPE',
                'POST_NAME',
                'USER_NICENAME',
                'ID',
                'SLUG',
                'TERM_ID',
                'TERM_TAXONOMY',
                'PAGENAME',
            ] as $placeholder) {
            $variable = strtolower($placeholder);

            if ($placeholder == 'PAGENAME') {
                $variable = 'post_name';
            }

            if (preg_match("/\[" . $placeholder . "\]/", $template) and isset($object->query_var)) {
                $template = str_replace("[{$placeholder}]", $object->query_var, $template);
            }

            if (preg_match("/\[" . $placeholder . "\]/", $template) and isset($object->$variable)) {
                $template = str_replace("[{$placeholder}]", $object->$variable, $template);
                break;
            }
        }

        if (preg_match("/\[ATTACHMENT_TYPE\]/", $template) and isset($object->post_mime_type)) {
            if (strpos($object->post_mime_type, '/')) {
                list($type, $subtype) = explode('/', $object->post_mime_type);
            } else {
                list($type, $subtype) = array($object->post_mime_type, '');
            }

            $template = str_replace(
                ['ATTACHMENT_TYPE','ATTACHMENT_SUBTYPE'],
                [$type, $subtype],
                $template
            );
        }

        return $template;
    }

    private function getRoutes()
    {
        return [
            'frontpage' => [
                'front-page.php'
            ],

            'index' => [
                'index.php'
            ],

            '404' => [
                '404.php'
            ],

            'archive' => [
                'archive-[POST_TYPE].php',
                'archive.php'
            ],

            'author' => [
                'author-[USER_NICENAME].php',
                'author-[ID].php',
                'author.php'
            ],

            'category' => [
                'category-[SLUG].php',
                'category-[TERM_ID].php',
                'category.php'
            ],

            'tag' => [
                'tag-[SLUG].php',
                'tag-[TERM_ID].php',
                'tag.php'
            ],

            'taxonomy' => [
                'taxonomy-[TERM_TAXONOMY]-[SLUG].php',
                'taxonomy-[TERM_TAXONOMY].php',
                'taxonomy.php'
            ],

            'date' => [
                'date.php'
            ],

            'home' => [
                'home.php',
                'index.php'
            ],

            'page' => [
                'page-[PAGENAME].php',
                'page-[ID].php',
                'page.php'
            ],

            'paged' => [
                'paged.php'
            ],

            'search' => [
                'search.php'
            ],

            'single' => [
                'single-[POST_TYPE]-[POST_NAME].php',
                'single-[POST_TYPE].php',
                'single.php'
            ],

            'embed' => [
                'embed-[POST_TYPE]-[POST_FORMAT].php',
                'embed-[POST_TYPE].php',
                'embed.php'
            ],

            'singular' => [
                'singular.php'
            ],

            'attachment' => [
                '[ATTACHMENT_TYPE]-[ATTACHMENT_SUBTYPE].php',
                '[ATTACHMENT_SUBTYPE].php',
                '[ATTACHMENT_TYPE].php',
                'attachment.php'
            ]
        ];
    }
}
