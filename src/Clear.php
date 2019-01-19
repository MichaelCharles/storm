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
class Clear extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('clear')
            ->setDescription("Bring down Docker containers and remove 'docker-compose.yml' file.");
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        if ($io->confirm("Are you sure? This will delete your 'workspaces' folder \n".
                         " in addition to bringing down the containers. In order to \n".
                         " only bring down the containers, run 'storm down' instead.", false)) {
            $fs = new Filesystem;
            $term = new Terminal("./workspaces");
            
            if ($fs->exists('./workspaces/docker-compose.yml')) {
                $e = $term->run('docker-compose down');
                if (!$e["status"] == 0) {
                    throw new RuntimeException("Aborting cleanup due to error encountered when running 'docker-compose down'.");
                }
            }
            
            $fs->remove('./workspaces');
        } else {
            $output->writeln("Canceling...");
        }
    }
}
