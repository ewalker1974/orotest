<?php

namespace Oro\FooBundle;

use Oro\FooBundle\DependencyInjection\OroFooExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;


class OroFooBundle extends Bundle
{
    public function getContainerExtension(): ?OroFooExtension
    {
        return new OroFooExtension();
    }
}