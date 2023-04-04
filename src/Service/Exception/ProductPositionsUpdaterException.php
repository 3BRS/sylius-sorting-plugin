<?php

declare(strict_types=1);

namespace ThreeBRS\SortingPlugin\Service\Exception;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProductPositionsUpdaterException extends Exception
{
    private const MESSAGE_PREFIX = 'ProductPositionsUpdater internal error';

    public function __construct(
        string $message = '',
        int $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        ?Throwable $previous = null,
    )
    {
        parent::__construct(
            sprintf(
                '%s %s %s',
                self::MESSAGE_PREFIX,
                trim($message) === '' ? '.' : ': ',
                $message,
            ),
            $code,
            $previous
        );
    }
}
