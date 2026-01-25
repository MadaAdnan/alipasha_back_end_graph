<?php

namespace App\Exceptions;


use Throwable;

class GraphQLExceptionHandler extends \Exception
{
    protected $message;
    protected $extensions;
    public function __construct($message = "", $code = 0, Throwable $previous = null, $extensions = [])
    {
        parent::__construct($message, $code, $previous);
        $this->extensions = $extensions;
    }

    public function getExtensions(): array
    {
        return $this->extensions;
    }
}
