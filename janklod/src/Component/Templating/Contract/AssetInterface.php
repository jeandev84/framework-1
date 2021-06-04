<?php
namespace Jan\Component\Templating\Contract;


/**
 * Interface AssetInterface
 * @package Jan\Component\Templating\Contract
*/
interface AssetInterface
{

    /**
     * Get css data
     *
     * @return array
    */
    public function getStyleSheets(): array;


    /**
     * Get css data
     *
     * @return array
    */
    public function getScripts(): array;

}