<?php

declare(strict_types=1);

namespace Typhoon\TypeComparator;

use Typhoon\Type\Type;
use Typhoon\Type\types;

/**
 * @internal
 * @psalm-internal Typhoon\TypeComparator
 */
final class IsLiteral extends Comparator
{
    public function __construct(
        private readonly Type $type,
    ) {}

    public function classStringLiteral(Type $self, string $class): mixed
    {
        return isSubtype(types::string, $this->type);
    }

    public function literal(Type $self, Type $type): mixed
    {
        return isSubtype($type, $this->type);
    }

    public function literalValue(Type $self, float|bool|int|string $value): mixed
    {
        return isSubtype($self, $this->type);
    }
}
