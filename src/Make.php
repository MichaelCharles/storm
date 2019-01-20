<?php

// Based on BinaryTide's terminal function.
// https://www.binarytides.com/execute-shell-commands-php/

namespace Storm;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Storm\Conjure;

class Make extends Command {
    
    protected function configure()
    {
        $this
            ->setName('make')
            ->setDescription("This is an alias for 'conjure'.")
            ->addArgument('count', InputArgument::OPTIONAL);
    }
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
	    $stormbringer = new Conjure;
	    $stormbringer->execute($input, $output);
	}
	
}
