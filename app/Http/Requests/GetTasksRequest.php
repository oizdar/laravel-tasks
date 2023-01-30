<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use OpenApi\Annotations as OA;

class GetTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'completed' => 'in:true,false',
            'due_date.from' => 'date',
            'due_date.to' => 'date',
            'order_by.completed' => 'in:asc,desc',
            'order_by.due_date' => 'in:asc,desc',
            'order_by.activities' => 'in:asc,desc'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ])->setStatusCode(400));
    }
}
