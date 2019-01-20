<?php
namespace Storm;
use RuntimeException;
use Storm\Terminal;
use Storm\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Down extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('down')
            ->setDescription("Runs 'docker-compose down' within your 'workspaces' directory.");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $fs = new Filesystem;
        $term = new Terminal($fs->getHome()."workspaces");
        if ($fs->exists($fs->getHome()."workspaces/docker-compose.yml")) {
            $term->run("docker-compose down");
        } else {
            throw new RuntimeException("Cannot find a 'docker-compose.yml' file in \n".
                                        "your 'workspaces' directory. Have you run\n".
                                        "'storm conjure' yet?");
        }
    }
}
