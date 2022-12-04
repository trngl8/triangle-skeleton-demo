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
    name: 'app:user:remove',
    description: 'Remove system user',
)]
class UserRemoveCommand extends Command
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
Arguments: username
HELP
            )
            ->addArgument('username', InputArgument::REQUIRED)
            ->addOption('v', null, InputOption::VALUE_NONE, 'Verbose')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');

        $user = $this->userManager->findOneBy(['username' => $username]);

        $this->userManager->remove($user);

        $io->success(sprintf('User %s successfully removed!', $username));

        return Command::SUCCESS;
    }


}
