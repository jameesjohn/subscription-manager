<?php

namespace App\Services\MailerLite;

use Throwable;

class MailerLiteException extends \Exception
{
    public array $data;

    public function __construct(
        string $message = "",
        int $code = 0,
        array $data = [],
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->data = $data;
    }

}
