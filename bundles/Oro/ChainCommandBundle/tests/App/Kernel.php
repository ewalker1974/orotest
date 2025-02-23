<?php

namespace Oro\ChainCommandBundle\Tests\App;

use Exception;
use Oro\ChainCommandBundle\OroChainCommandBundle;
use Override;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[Override]
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new OroChainCommandBundle()
        ];
    }

    /**
     * @throws Exception
     */
    #[Override]
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getConfigDir().'/services.yaml');
    }

    private function getConfigDir(): string
    {
        return $this->getProjectDir() . '/tests/config';
    }
}
