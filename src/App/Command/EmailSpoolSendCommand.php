<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Mailer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'mtt:email:spool-send',
    description: 'Send email from spool',
)]
class EmailSpoolSendCommand extends Command
{
    public function __construct(private Mailer $mailer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $cnt = $this->mailer->spoolSend(timeLimit: 60);

        $io->success("{$cnt} letters were sent");

        return Command::SUCCESS;
    }
}
