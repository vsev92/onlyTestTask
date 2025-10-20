<?php

namespace App\Http\Responses;

use Illuminate\Http\Resources\Json\JsonResource;

class ApiDataResponse extends ApiResponse
{
    public function __construct(int $statusCode, $data, array $meta = [])
    {
        parent::__construct($statusCode, $meta);
        $this->response['data'] = $data instanceof JsonResource ? $data->toArray(request()) : $data;
    }
}
