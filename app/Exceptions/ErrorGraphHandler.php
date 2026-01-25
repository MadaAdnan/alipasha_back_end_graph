<?php

namespace App\Exceptions;

use Nuwave\Lighthouse\Execution\ErrorHandler;

use GraphQL\Error\Error;
class ErrorGraphHandler implements ErrorHandler
{
    public function __invoke(?Error $error, \Closure $next): ?array
    {

        if ($error !== null && $error->getPrevious() instanceof GraphQLExceptionHandler) {
            $customError = $error->getPrevious();

            // Convert the error to an array and merge custom extensions
            $errorArray = collect($error->jsonSerialize())->toArray();
            $errorArray['status']="error";
            $errorArray['code']=401;
            $errorArray['message']=$customError->getMessage();
            $errorArray['extensions'] = array_merge([],
                //$errorArray['extensions'] ?? [],
                [$customError->getMessage()]
            );

            return $errorArray;
        }

        return $next($error);
    }



}
