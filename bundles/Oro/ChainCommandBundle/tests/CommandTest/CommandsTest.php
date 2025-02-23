<?php

namespace Oro\ChainCommandBundle\Test;

use Exception;
use Oro\ChainCommandBundle\Listener\CommandListener;
use Override;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Tester\CommandTester;

class CommandsTest extends KernelTestCase
{
    private ?Application $application;

    #[Override]
    public function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->application = $kernel->getContainer()->get('console.messenger.application');

        if ($this->application === null) {
            throw new RuntimeException('Test console application not found.');
        }

        $this->application->setAutoExit(false);
    }

    /**
     * @throws Exception
     */
    public function testRootCommand(): void
    {
        $display = $this->runCommandAndGetOutput('test:root');

        $this->assertStringContainsString('Root of chain command', $display);
        $this->assertStringContainsString('Chained child command', $display);
    }

    /**
     * @throws Exception
     */
    public function testChainedCommand(): void
    {
        $display = $this->runCommandAndGetOutput('test:chained');

        $this->assertStringContainsString('test:chained command is a member of test:root command chain and cannot be executed on its own', $display);

    }

    /**
     * @throws Exception
     */
    public function testGeneralCommand(): void
    {
        $display = $this->runCommandAndGetOutput('test:unchained');

        $this->assertStringContainsString('General command', $display);
    }

    /**
     * @throws Exception
     */
    private function runCommandAndGetOutput(string $command): string
    {
        $input = new ArrayInput(['command' => $command]);
        $output = new BufferedOutput();

        $exitCode = $this->application->run($input, $output);

        return $output->fetch();
    }
}
