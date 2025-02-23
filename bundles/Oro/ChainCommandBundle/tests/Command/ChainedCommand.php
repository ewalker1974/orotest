<?php

namespace Oro\ChainCommandBundle\Tests\Command;

use App\Command\RootCommand;
use Oro\ChainCommandBundle\Attribute\ChainCommand;
use Oro\FooBundle\Command\HelloCommand;
use Override;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[asCommand(name: "test:chained")]
#[ChainCommand(rootClass: RootChainCommand::class)]
class ChainedCommand extends Command
{
    #[Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->success('Chained child command');

        return Command::SUCCESS;
    }
}