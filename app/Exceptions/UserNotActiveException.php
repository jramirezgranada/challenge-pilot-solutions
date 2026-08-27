<?php

namespace App\Exceptions;

use RuntimeException;

class UserNotActiveException extends RuntimeException
{
    protected $message = 'Only active users can create or edit articles.';
}
