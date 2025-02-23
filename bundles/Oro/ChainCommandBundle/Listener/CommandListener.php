<?php

namespace Oro\ChainCommandBundle\Listener;

use Oro\ChainCommandBundle\Exception\ChainCommandException;
use Oro\ChainCommandBundle\Service\ChainCommandExecution;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

readonly class CommandListener
{
    public function __construct(private ChainCommandExecution $chainCommandExecution, private LoggerInterface $logger)
    {
    }

    /**
     * @throws ChainCommandException
     */
    #[AsEventListener(event: ConsoleEvents::COMMAND)]
    public function onConsoleCommand(ConsoleCommandEvent $event): void
    {
        $command = $event->getCommand();

        if ($command) {
            $rootCommandName = $this->chainCommandExecution->getRootCommand($command);
            if ($rootCommandName) {
                throw new ChainCommandException("{$command->getName()} command is a member of {$rootCommandName} command chain and cannot be executed on its own");
            }
            $chain = $this->chainCommandExecution->getCommandChain($command);
            if (count($chain) > 0) {
                $this->logger->info("{$command->getName()} is a master command of a command chain that has registered member commands");
                foreach ($chain as $chainCommand) {
                    $this->logger->info("{$chainCommand->getName()} registered as a member of foo:hello command chain");
                }
                $this->logger->info("Executing {$command->getName()} command itself first:");
            }

        }
    }

    /**
     * @throws ChainCommandException
     */
    #[AsEventListener(event: ConsoleEvents::TERMINATE)]
    public function onConsoleTerminate(ConsoleTerminateEvent $event): void
    {
        /** @var ?Command $command */
        $command = $event->getCommand();

        if ($command && $event->getExitCode() === Command::SUCCESS) {
            $chain = $this->chainCommandExecution->getCommandChain($command);
            $this->logger->info("Executing {$command->getName()} chain members:");
            foreach ($chain as $chainCommand) {
                $chainCommand->run($event->getInput(), $event->getOutput());
            }
            $this->logger->info("Execution of {$command->getName()} chain completed:");
        }
    }
}