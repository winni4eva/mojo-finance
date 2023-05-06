<?php

namespace App\Exceptions;

use App\Traits\HttpResponseTrait;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HttpResponseTrait;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {
            return $this->handleException($request, $e);
        });
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof TransactionProcessingFailed) {
            return $exception->render($request);
        } elseif ($exception instanceof Authentication) {
            return $exception->render($request);
        } elseif ($exception instanceof ValidationException || $exception instanceof HttpException) {
            return $this->error('', $exception->getMessage(), $exception->getCode() ?: Response::HTTP_FORBIDDEN);
        }

        return parent::render($request, $exception);
    }
}
