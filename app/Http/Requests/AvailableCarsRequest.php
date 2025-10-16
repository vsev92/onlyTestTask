<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
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

            'model_ids'        => 'nullable|array',
            'model_ids.*'      => 'integer|exists:car_models,id',

            'car_comfort_class_id'   => 'nullable|array',
            'car_comfort_class_id.*' => 'integer|exists:car_comfort_classes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'model_ids.array'     => 'Поле "Модель" должно быть массивом.',
            'model_ids.*.integer' => 'Каждый элемент поля "Модель" должен быть числом.',
            'model_ids.*.exists'  => 'Одна или несколько выбранных моделей не существуют в базе.',

            'car_comfort_class_id.array'     => 'Поле "Класс комфорта" должно быть массивом.',
            'car_comfort_class_id.*.integer' => 'Каждый элемент поля "Класс комфорта" должен быть числом.',
            'car_comfort_class_id.*.exists'  => 'Один или несколько выбранных классов комфорта не существуют в базе.',
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


    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
