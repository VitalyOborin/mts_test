<?php

declare(strict_types=1);

namespace App\Service\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ActionException extends HttpException
{
    public function __construct(?string $message = '', \Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(500, $message, $previous, $headers, $code);
    }
}
