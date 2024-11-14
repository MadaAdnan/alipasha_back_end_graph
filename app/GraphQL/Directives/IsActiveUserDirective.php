<?php

namespace App\GraphQL\Directives;

use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Execution\ResolveInfo;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class IsActiveUserDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
"""
Return the currently authenticated user as the result of a query.
"""
directive @isActive(
  """
  Specify which active to use, e.g.
  When not defined, the default from `lighthouse.php` is used.
  """
  active: Boolean
) on FIELD_DEFINITION
GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $fieldValue->wrapResolver(fn (callable $resolver) => function (mixed $root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
            $active = $this->directiveArgValue('active')
                // Throw in case of an invalid schema definition to remind the developer
                ?? throw new DefinitionException("Missing argument 'requiredRole' for directive '@canAccess'.");

            $user = $context->user();
            if (
                // Unauthenticated users don't get to see anything
                ! $user
                // The user's role has to match have the required role
                || $user->is_active !== $active
            ) {
                return null;
            }

            return $resolver($root, $args, $context, $resolveInfo);
        });
    }
}
