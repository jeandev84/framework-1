<?php
namespace Jan\Foundation\Console;


use Jan\Component\Console\Input\Contract\InputInterface;
use Jan\Component\Console\Output\Contract\OutputInterface;
use Jan\Contract\Console\Kernel as ConsoleKernelContract;


/**
 * Class Kernel
 * @package Jan\Foundation\Console
*/
class Kernel implements ConsoleKernelContract
{

    public function handle(InputInterface $input, OutputInterface $output)
    {
        // TODO: Implement handle() method.
    }

    public function terminate(InputInterface $input, $status)
    {
        // TODO: Implement terminate() method.
    }
}