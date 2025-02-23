<?php

namespace Oro\ChainCommandBundle\Service;

use Oro\ChainCommandBundle\Attribute\ChainCommand;
use Oro\ChainCommandBundle\Exception\ChainCommandException;
use ReflectionClass;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

class ChainCommandExecution
{
    /** @var ?array<string, array[Command]>  */
    private ?array $chainedCommands = null;

    /** @var ?array<string, string>  */
    private ?array $reverseChainedCommands = null;


    /**
     * @throws ChainCommandException
     */
    public function getRootCommand(Command $command): ?string
    {
        if ($this->chainedCommands === null) {
            $this->createCommandChains($command->getApplication());
        }
        $commandClass = get_class($command);

        if (isset($this->reverseChainedCommands[$commandClass])) {
            return $this->reverseChainedCommands[$commandClass];
        }

        return null;
    }

    /**
     * @throws ChainCommandException
     */
    public function getCommandChain(Command $command): array
    {
        if ($this->chainedCommands === null) {
            $this->createCommandChains($command->getApplication());
        }

        $commandClass = get_class($command);

        if (isset($this->chainedCommands[$commandClass])) {
            return $this->chainedCommands[$commandClass];
        }

        return [];
    }

    /**
     * @throws ChainCommandException
     */
    private function createCommandChains(Application $application): void
    {
        $this->reverseChainedCommands = [];
        $this->chainedCommands = [];

        $commands = $application->all();

        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $reflection  = new ReflectionClass($command);
                $attributes = $reflection->getAttributes(ChainCommand::class);
                if (count($attributes) > 0) {
                    $rootClass = $attributes[0]->newInstance()->rootClass;

                    $rootCommand = $this->getRootCommandInstance($rootClass, $commands);

                    if (!$rootCommand) {
                        throw new ChainCommandException("{$command->getName()} has registered for {$rootClass} but it is not implemented");
                    }

                    $this->reverseChainedCommands[get_class($command)] = $rootCommand->getName();
                    $this->chainedCommands[$rootClass][] = $command;
                }
            }
        }
    }

    private function getRootCommandInstance(string $commandClass, array $commands): ?Command
    {
        return array_find($commands, fn($command) => get_class($command) === $commandClass);

    }
}