<?php

namespace Oro\BarBundle;

use Oro\BarBundle\DependencyInjection\OroBarExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class OroBarBundle extends Bundle
{
    public function getContainerExtension(): ?OroBarExtension
    {
        return new OroBarExtension();
    }
}