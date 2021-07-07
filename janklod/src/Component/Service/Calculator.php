<?php
namespace Jan\Component\Service;


use Exception;

/**
 * Class Calculator
 * @package Jan\Component\Service
*/
class Calculator
{
    /**
     * @param $a
     * @param $b
     * @param string $operator
     * @return float|int|mixed
     * @throws Exception
     */
    public function calculate($a, $b, string $operator = '+')
    {
        switch ($operator) {
            case '+':
                return $a + $b;
                break;

            case '-':
                return $a - $b;
                break;

            case '*':
                return $a * $b;
                break;

            case '/':
                if ($b == 0) {
                    throw new Exception('Cannot divide number by zero.');
                }

                return  $a / $b;
            default:
                throw new \Exception('Cannot resolve operator '. $operator);
        }
    }



    /**
     * @param $a
     * @param $b
     * @return float|int|mixed
     * @throws Exception
    */
    public function sum($a, $b)
    {
        return $this->calculate($a, $b);
    }


    /**
     * @param $a
     * @param $b
     * @return float|int|mixed
    */
    public function multiple($a, $b)
    {
        return $this->calculate($a, $b, '*');
    }


    /**
     * @param $a
     * @param $b
     * @return float|int|mixed
     * @throws Exception
    */
    public function divide($a, $b)
    {
        return $this->calculate($a, $b, '/');
    }


    /**
     * @param $a
     * @param $b
     * @return float|int|mixed
     * @throws Exception
    */
    public function subtract($a, $b)
    {
        return $this->calculate($a, $b, '-');
    }
}