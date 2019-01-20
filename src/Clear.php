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
            ->setDescription("Bring down Docker containers and remove 'workspaces' folder. In order to run without confirmation, use a '-y' flag.")
            ->addArgument('options', InputArgument::OPTIONAL)
            ->addOption(
                'yes',
                'y',
                InputOption::VALUE_OPTIONAL,
                'Skip the prompt asking you to confirm the action.',
                false
            );
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('yes') === NULL;
        $io = new SymfonyStyle($input, $output);
        if ($force ||
            $io->confirm("Are you sure? This will delete your 'workspaces' folder \n".
                         " in addition to bringing down the containers. In order to \n".
                         " only bring down the containers, run 'storm down' instead.", false)) {
            $fs = new Filesystem;
            $term = new Terminal("./workspaces");
            
            $output->writeln("<info>Attempting to bring down Docker containers...</info>");
            if ($fs->exists('./workspaces/docker-compose.yml')) {
                $e = $term->run('docker-compose down');
                if (!$e["status"] == 0) {
                    throw new RuntimeException("Aborting cleanup due to error encountered when running 'docker-compose down'.");
                }
            }
            $output->writeln("<info>Attempting to remove workspaces...</info>");
            $fs->remove('./workspaces');
        } else {
            $output->writeln("<info>Canceling...</info>");
        }
    }
}
