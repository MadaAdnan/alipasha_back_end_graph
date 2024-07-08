<?php declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

/** Read more about scalars here: https://webonyx.github.io/graphql-php/type-definitions/scalars. */
final class CustomDate extends ScalarType
{
    /** Serializes an internal value to include in a response. */
    public function serialize(mixed $value): mixed
    {
        return $value?->format('Y-m-d');
    }

    /** Parses an externally provided value (query variable) to use as an input. */
    public function parseValue(mixed $value): mixed
    {
        return Carbon::parse($value);
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * Should throw an exception with a client friendly message on invalid value nodes, @param \GraphQL\Language\AST\ValueNode&\GraphQL\Language\AST\Node $valueNode
     * @param array<string, mixed>|null $variables
     * @see \GraphQL\Error\ClientAware.
     *
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        if (!$valueNode->value) {
            throw new \Error("تاريخ غير صحيح");
        }
        return Carbon::parse($valueNode->value);
    }
}
