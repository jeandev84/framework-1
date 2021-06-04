<?php
namespace Jan\Component\Templating\Contract;


/**
 * Interface RendererInterface
 * @package Jan\Component\Templating\Contract
*/
interface RendererInterface
{
    /**
     * Render html
     * @return string
    */
    public function renderHtml(): string;
}