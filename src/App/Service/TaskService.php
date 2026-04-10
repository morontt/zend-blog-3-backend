<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\AppException;
use App\LogTrait;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class TaskService
{
    use LogTrait;

    public function __construct(
        private KernelInterface $kernel,
        LoggerInterface $logger,
    ) {
        $this->setLogger($logger);
    }

    /**
     * @param array<string, mixed> $params
     *
     * @throws AppException
     */
    public function runCommand(string $commandName, array $params = []): void
    {
        if (
            class_exists($commandName)
            && $attribute = (new ReflectionClass($commandName))->getAttributes(AsCommand::class)
        ) {
            $commandName = $attribute[0]->newInstance()->name;
        }

        $this->info('Run command', [
            'command' => $commandName,
            'params' => $params,
        ]);

        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput(array_merge(
            ['command' => $commandName],
            $params
        ));
        $input->setInteractive(false);

        $output = new BufferedOutput();
        $exitCode = $application->run($input, $output);
        if ($exitCode !== Command::SUCCESS) {
            $this->error('Error executing command', [
                'command' => $commandName,
                'params' => $params,
                'exit_code' => $exitCode,
            ]);

            throw new AppException("Error executing {$commandName} command");
        }

        $this->info('Command complete', [
            'command' => $commandName,
            'output' => $output->fetch(),
        ]);
    }
}
