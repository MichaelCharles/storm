<?php
namespace Storm;
use RuntimeException;
use Storm\Terminal;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
class Conjure extends Command
{
    
    protected function configure()
    {
        $this
            ->setName('conjure')
            ->setDescription('Create one or more Cloud9 Docker containers.')
            ->addArgument('count', InputArgument::OPTIONAL);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $count = (int)$input->getArgument('count');
        if ($count === 1) {
            $output->writeln('<info>Conjuring up a storm of a single Cloud9 instance...</info>');
        } elseif ($count > 1 && is_int($count)) {
            $output->writeln('<info>Conjuring up a storm of '.$count.' Cloud9 instances...</info>');
        } else {
            throw new RuntimeException('Count must be a positive number.');
        }
        $fs = new Filesystem;
        $term = new Terminal("./workspaces");
        
        if ($fs->exists('./workspaces/docker-compose.yml')) {
            throw new RuntimeException("A 'docker-compose.yml' file already exists in the workspaces directory.\n".
                                       "Please run 'storm conjure clear' or remove the file manually.");
        }
        
        $this->makeDockerComposeFile($count, $fs);
        
        $term->run('docker-compose up -d');
    }
    
    protected function makeDockerComposeFile($count, Filesystem $fs)
        {
            $fs->appendToFile('./workspaces/docker-compose.yml', "version: '3'"."\n");
            $fs->appendToFile('./workspaces/docker-compose.yml', "services:"."\n");
            
            $usersArray = $this->makeUsersArray($count);
            
            foreach ($usersArray as $user) {
                $fs->appendToFile('./workspaces/docker-compose.yml', "  c9u".$user.":\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "    container_name: c9u".$user."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "    image: mcaubrey/cloud9-s2"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "    ports:"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "      - 8".$user.":80"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "      - 9".$user.":8080"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "    environment:"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "      WORKSPACE: '/var/www/html'"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "    volumes:"."\n");
                $fs->appendToFile('./workspaces/docker-compose.yml', "      - ./".$user."\n");
            }
        }
    
    protected function makeUsersArray($count)
    {
        for ($x = 1; $x <= $count; $x++) {
            if (strlen($x) == 1) {
                $usersArray[] = "00".$x;
            } elseif (strlen($count) == 2) {
                $usesrArray[] = "0".$x;
            } elseif (strlen($count) === 3) {
                $usersArray[] = $x;
            } else {
                throw new RuntimeException('The number of Cloud9 instances must be under 1000.');
            }
        } 
        return $usersArray;
    }
}
