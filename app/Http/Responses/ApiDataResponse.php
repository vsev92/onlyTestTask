<?php

namespace App\Http\Responses;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiDataResponse extends ApiResponse
{
    public function __construct(int $statusCode, $data, array $meta = [])
    {
        parent::__construct($statusCode, $meta);

        if ($data instanceof JsonResource) {
            $this->response['data'] = $data->toArray(request());

            $resource = $data->resource;
            if ($resource instanceof AbstractPaginator) {
                $this->response['links'] = [
                    'first' => $resource->url(1),
                    'last'  => $resource->url($resource->lastPage()),
                    'prev'  => $resource->previousPageUrl(),
                    'next'  => $resource->nextPageUrl(),
                ];

                $this->response['meta']['pagination'] = [
                    'current_page' => $resource->currentPage(),
                    'per_page' => $resource->perPage(),
                    'total' => $resource->total(),
                    'last_page' => $resource->lastPage(),
                ];
            }
        } else {
            $this->response['data'] = $data;
        }
    }
}
