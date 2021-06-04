<?php
namespace Jan\Component\Templating;


use Jan\Component\Templating\Contract\RendererInterface;
use Jan\Component\Templating\Exception\ViewException;


/**
 * Class Renderer
 * @package Jan\Component\Templating
 */
class Renderer implements RendererInterface
{

    /**
     * view directory
     *
     * @var string
    */
    protected $targetResource;



    /**
     * file template
     *
     * @var string
    */
    protected $template;



    /**
     * view data
     *
     * @var array
    */
    protected $variables = [];



    /**
     * Renderer constructor.
     *
     * @param string $targetResource
    */
    public function __construct(string $targetResource = '')
    {
        if($targetResource) {
            $this->resource($targetResource);
        }
    }


    /**
     * @param $targetResource
     * @return $this
    */
    public function resource($targetResource): Renderer
    {
        $this->targetResource = rtrim($targetResource, '\\/');

        return $this;
    }



    /**
     * @param array $variables
     * @return $this
    */
    public function setVariables(array $variables): Renderer
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }


    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template): Renderer
    {
        $this->template = $template;

        return $this;
    }




    /**
     * Render view template and optional data
     *
     * @return false|string
     * @throws ViewException
    */
    public function renderHtml(): string
    {
        extract($this->variables, EXTR_SKIP);

        ob_start();
        require_once($this->resourcePath($this->template));
        return ob_get_clean();
    }



    /**
     * Render html template with availables variables
     *
     *  @param string $template
     *  @param array $variables
     *  @return false|string
     *  @throws ViewException
     */
    public function render(string $template, array $variables = [])
    {
        return $this->setTemplate($template)
            ->setVariables($variables)
            ->renderHtml();
    }


    /**
     * @param string $template
     * @return string
     * @throws ViewException
    */
    public function resourcePath(string $template): string
    {
        $templatePath = $this->targetResource . DIRECTORY_SEPARATOR . $this->resolvePath($template);

        if(! file_exists($templatePath)) {
            throw new ViewException(sprintf('view file %s does not exist!', $templatePath));
        }

        return $templatePath;
    }


    /**
     * @param $targetPath
     * @return string|string[]
    */
    protected function resolvePath($targetPath)
    {
        return str_replace(['\\', '/'], DIRECTORY_SEPARATOR, ltrim($targetPath, '\\/'));
    }
}