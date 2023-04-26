<?php

namespace App\Services;

class HttpResponseModel
{
    public bool $status;
    public int $statusCode;
    public $data;

    public function __construct(bool $status, int $statusCode, $data)
    {
        $this->status = $status;
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function toArray(): array
    {
        return [
            "status" => $this->status,
            "statusCode" => $this->statusCode,
            "data" => $this->data,
        ];
    }
}
