<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse implements Responsable
{
    protected int $statusCode;
    protected array $response;

    public function __construct(int $statusCode, array $meta = [])
    {
        $this->statusCode = $statusCode;
        if (!empty($meta)) {
            $this->response['meta'] = $meta;
        }
    }

    /**
     * Создаёт HTTP-ответ для запроса.
     *
     * @param mixed $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Response
    {
        if ($this->statusCode === Response::HTTP_NO_CONTENT) {
            return response()->noContent();
        }

        return response()->json($this->utf8ize($this->response), $this->statusCode);
    }

    /**
     * Рекурсивно преобразует все строки массива в UTF-8
     */
    protected function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, 'UTF-8', 'UTF-8');
        }
        return $mixed;
    }
}
