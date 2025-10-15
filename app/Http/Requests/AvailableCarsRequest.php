<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;


class AvailableCarsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_time' => 'required|date',
            'end_time'   => 'required|date',
            'model_id'   => 'nullable|integer|exists:car_models,id',
            'category'   => 'nullable|integer',
        ];
    }

    public function validationData()
    {
        return $this->query();
    }


    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $start = $this->query('start_time');
            $end   = $this->query('end_time');

            if ($start && $end) {
                $startTime = Carbon::parse($start);
                $endTime   = Carbon::parse($end);

                if ($endTime->lt($startTime->addHour())) {
                    $validator->errors()->add('end_time', 'минимальное время бронирования авто составляет 1 час');
                }
            }
        });
    }
}
