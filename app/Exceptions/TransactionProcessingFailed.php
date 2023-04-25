<?php

namespace App\Exceptions;

use App\Traits\HttpResponseTrait;
use Exception;

class TransactionProcessingFailed extends Exception
{
    use HttpResponseTrait;

    public function __construct(protected $message = 'Transaction error', protected $statusCode = 401, protected $errors = null)
    {
        parent::__construct($message, $statusCode);
    }

    public function render($request)
    {
        return $this->error('', $this->message, $this->statusCode);
    }
}
