<?php

namespace App\Exceptions;

use App\Traits\HttpResponseTrait;
use Exception;

class TransactionProcessingFailed extends Exception
{
    use HttpResponseTrait;

    protected $message;
    protected $statusCode;
    protected $errors;

    public function __construct($message = null, $statusCode = 400, $errors = null)
    {
        $this->message = $message ?: 'Transaction error';
        $this->statusCode = $statusCode;
        $this->errors = $errors;
        parent::__construct($message, $statusCode);
    }

    public function render($request)
    {
        return $this->error('', $this->message, $this->statusCode);
    }
}
