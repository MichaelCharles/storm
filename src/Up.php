<?php
namespace Storm;
use RuntimeException;
use Storm\Terminal;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
class Up extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('up')
            ->setDescription("Runs 'docker-compose up -d' within your 'workspaces' directory.");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $term = new Terminal("./workspaces");
        $fs = new Filesystem;
        if ($fs->exists("./workspaces/docker-compose.yml")) {
            $term->run("docker-compose up -d");
        } else {
            throw new RuntimeException("Cannot find a 'docker-compose.yml' file in \n".
                                        "your 'workspaces' directory. Have you run\n".
                                        "'storm conjure' yet?");
        }
    }
}
