<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ObjectNotFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class TaskService
{
    /** @var array<string, Command> */
    private array $commands = [];

    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param array<string, mixed> $params
     *
     * @throws ObjectNotFoundException
     */
    public function runCommand(string $commandClassName, array $params = []): int
    {
        $this->logger->info('Run command', [
            'command' => $commandClassName,
            'params' => $params,
        ]);

        $command = $this->find($commandClassName);

        $input = new ArrayInput($params);
        $input->setInteractive(false);

        return $command->run($input, new NullOutput());
    }

    public function add(Command $command): void
    {
        $this->commands[get_class($command)] = $command;
    }

    /**
     * @throws ObjectNotFoundException
     */
    private function find(string $className): Command
    {
        if (!isset($this->commands[$className])) {
            throw new ObjectNotFoundException("Command {$className} not found");
        }

        return $this->commands[$className];
    }
}
