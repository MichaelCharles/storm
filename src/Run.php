<?php
namespace Storm;
use RuntimeException;
use Storm\Terminal;
use Symfony\Component\Process\Process;
use Storm\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
class Run extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription("Runs the passed command to docker-compose in the workspaces folder.")
            ->addArgument('docker-compose-command', InputArgument::REQUIRED);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        $fs = new Filesystem;
        $term = new Terminal($fs->getHome()."workspaces");
        if ($fs->exists($fs->getHome()."workspaces/docker-compose.yml")) {
            $term->run("docker-compose ".$input->getArgument('docker-compose-command'));
        } else {
            throw new RuntimeException("Cannot find a 'docker-compose.yml' file in \n".
                                        "your 'workspaces' directory. Have you run\n".
                                        "'storm conjure' yet?");
        }
    }
}
