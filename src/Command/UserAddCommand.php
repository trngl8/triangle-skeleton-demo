<?php

namespace App\Command;

use App\Service\UserService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;;

#[AsCommand(
    name: 'app:user:add',
    description: 'Add system user',
)]
class UserAddCommand extends Command
{
    private $userManager;

    public function __construct(UserService $userManager, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userManager = $userManager;


        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setHelp(<<<'HELP'
<info>Add User</info>
Arguments: username, name, email
HELP
            )
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addOption('v', null, InputOption::VALUE_NONE, 'Verbose')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $plaintextPassword = $input->getArgument('password');
        $username = $input->getArgument('username');

        $user = $this->userManager->create($username, $plaintextPassword);

        $this->userManager->save($user);

        $io->success('User successfully generated!');

        return Command::SUCCESS;
    }


}
