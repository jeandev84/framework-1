<?php
namespace Jan\Component\Http\Bag;


/**
 * Class InputBag
 * @package Jan\Component\Http\Bag
*/
class InputBag extends ParameterBag
{

    /**
     * @param array $items
    */
    public function replace(array $items)
    {
        $this->params = [];
        $this->add($items);
    }


    /**
     * @param array $items
    */
    public function add(array $items)
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }
}