<?php

namespace App\Api\Controller;

use App\Api\Exception\BadRequestException;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\NoResultException;
use FOS\RestBundle\Util\ExceptionValueMap;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;

class ExceptionController
{
    const EXPORTED_EXCEPTIONS = [
        BadRequestException::class,
        BadRequestHttpException::class,
    ];

    const NOT_FOUND_EXCEPTIONS = [
        NoResultException::class,
        EntityNotFoundException::class,
        NotFoundHttpException::class,
    ];

    const DUPLICATE_RECORD_EXCEPTIONS = [
        \Doctrine\DBAL\Exception\UniqueConstraintViolationException::class,
    ];
    /**
     * @var ExceptionValueMap
     */
    private $exceptionCodes;

    private $logger;

    public function __construct(ExceptionValueMap $exceptionCodes)
    {
        $this->exceptionCodes = $exceptionCodes;
    }

    /**
     * @param \Throwable $exception
     * @param DebugLoggerInterface|null $logger
     * @return JsonResponse
     */
    public function show(\Throwable $exception, DebugLoggerInterface $logger = null)
    {
        /**
         * @todo add logs for "real" exceptions!
         */
        return new JsonResponse(
            ['error' => $this->getExportedMessage($exception)],
            $this->getStatusCode($exception)
        );
    }

    /**
     * @todo move all of it somewhere from controller
     * @param \Throwable $e
     * @return string
     */
    private function getExportedMessage(\Throwable $e): string
    {
        switch (true) {
            case $this->isExportedException($e):
                $msg = $e->getMessage();
                break;
            case $this->isNotFoundException($e):
                $msg = 'Resource not found.';
                break;
            case $this->isDuplicateRecordException($e):
                $msg = 'Can not add duplicate record.';
                break;
            default:
                $msg = 'Unknown application error.';
                break;
        }

        return $msg;
    }

    private function isExportedException(\Throwable $e): bool
    {
        foreach (self::EXPORTED_EXCEPTIONS as $exported) {
            if (is_a($e, $exported)) {
                return true;
            }
        }

        return false;
    }

    private function isNotFoundException(\Throwable $e): bool
    {
        foreach (self::NOT_FOUND_EXCEPTIONS as $exported) {
            if (is_a($e, $exported)) {
                return true;
            }
        }

        return false;
    }

    private function isDuplicateRecordException(\Throwable $e): bool
    {
        foreach (self::DUPLICATE_RECORD_EXCEPTIONS as $exported) {
            if (is_a($e, $exported)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Throwable $exception
     * @return false|int|mixed
     */
    private function getStatusCode(\Throwable $exception)
    {
        // If matched
        if ($statusCode = $this->exceptionCodes->resolveException($exception)) {
            return $statusCode;
        }
        // Otherwise, default
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }
        return 500;
    }
}
