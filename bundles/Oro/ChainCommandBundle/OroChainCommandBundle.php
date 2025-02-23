<?php

namespace Oro\ChainCommandBundle;

use Oro\ChainCommandBundle\DependencyInjection\OroChainCommandExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class OroChainCommandBundle extends Bundle
{
    public function getContainerExtension(): ?OroChainCommandExtension
    {
        return new OroChainCommandExtension();
    }
}