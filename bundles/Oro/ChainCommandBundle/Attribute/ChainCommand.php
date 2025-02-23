<?php

namespace Oro\ChainCommandBundle\Attribute;

use Attribute;

/**
 * @Annotation
 */
#[Attribute(Attribute::TARGET_CLASS)]
readonly class ChainCommand
{
    public function __construct(public string $rootClass)
    {
    }
}
