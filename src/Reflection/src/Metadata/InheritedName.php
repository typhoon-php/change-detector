<?php

declare(strict_types=1);

namespace Typhoon\Reflection\Metadata;

use Typhoon\Type\Type;

final class InheritedName
{
    /**
     * @param non-empty-string $class
     * @param list<Type> $templateArguments
     */
    public function __construct(
        public readonly string $class,
        public readonly array $templateArguments = [],
    ) {}
}
