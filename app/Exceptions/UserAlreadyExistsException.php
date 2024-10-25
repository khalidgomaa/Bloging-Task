<?php

namespace App\Exceptions;

use Exception;

class UserAlreadyExistsException extends Exception
{
    protected $message = 'User already exists with the provided phone number';

    public function __construct()
    {
        parent::__construct($this->message);
    }
}
