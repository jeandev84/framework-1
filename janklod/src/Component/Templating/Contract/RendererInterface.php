<?php
namespace Jan\Component\Templating\Contract;


/**
 * Interface RendererInterface
 * @package Jan\Component\Templating\Contract
*/
interface RendererInterface
{
    /**
     * @param string $template
     * @param array $variables
     * @return mixed
    */
    public function render(string $template, array $variables = []);
}